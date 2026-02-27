<?php

namespace App\Http\Controllers;

use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\DokterHewan;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeriksaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil pendaftaran yang menunggu untuk diperiksa
        $pendaftarans = Pendaftaran::with(['pemilikHewan', 'hewan', 'pegawai'])
            ->where('status', 'menunggu')
            ->orderBy('tanggal_daftar', 'desc')
            ->paginate(10);
            
        return view('pemeriksaan.index', compact('pendaftarans'));
    }

    /**
     * Display history of examinations (for doctors)
     */
    public function riwayat()
    {
        $user = auth()->user();
        
        // Jika dokter, filter berdasarkan dokter yang login
        if ($user->role === 'dokter') {
            $dokter = DokterHewan::where('nama_dokter', $user->name)->first();
            
            if (!$dokter) {
                return redirect()->route('pemeriksaan.index')
                    ->with('error', 'Data dokter tidak ditemukan');
            }
            
            $pemeriksaans = Pemeriksaan::with(['pendaftaran.pemilikHewan', 'pendaftaran.hewan', 'dokterHewan', 'pembayaran'])
                ->where('id_dokter', $dokter->id_dokter)
                ->orderBy('tanggal_periksa', 'desc')
                ->paginate(10);
        } else {
            // Admin bisa lihat semua riwayat
            $pemeriksaans = Pemeriksaan::with(['pendaftaran.pemilikHewan', 'pendaftaran.hewan', 'dokterHewan', 'pembayaran'])
                ->orderBy('tanggal_periksa', 'desc')
                ->paginate(10);
        }
        
        return view('pemeriksaan.riwayat', compact('pemeriksaans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pendaftaranId = $request->query('pendaftaran');
        
        if (!$pendaftaranId) {
            return redirect()->route('pemeriksaan.index')
                ->with('error', 'Silakan pilih pendaftaran yang akan diperiksa');
        }
        
        $pendaftaran = Pendaftaran::with('pemilikHewan')
            ->where('id_pendaftaran', $pendaftaranId)
            ->where('status', 'menunggu')
            ->firstOrFail();
        
        // Cari dokter hewan dari user yang login
        $dokterLogin = null;
        if (auth()->user()->role === 'dokter') {
            $dokterLogin = DokterHewan::where('nama_dokter', auth()->user()->name)->first();
        }
            
        $dokterHewans = DokterHewan::all();
        $obats = Obat::where(function($query) {
                $query->where('tanggal_kadaluarsa', '>', now())
                      ->orWhereNull('tanggal_kadaluarsa');
            })
            ->where('stok', '>', 0)
            ->orderBy('nama_obat')
            ->get();
        
        return view('pemeriksaan.create', compact('pendaftaran', 'dokterHewans', 'dokterLogin', 'obats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pendaftaran' => 'required|exists:pendaftaran,id_pendaftaran',
            'id_dokter' => 'required|exists:dokter_hewan,id_dokter',
            'tanggal_periksa' => 'required|date',
            'diagnosa' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'biaya_tindakan' => 'required|integer|min:0',
            'obat' => 'nullable|array',
            'obat.*.id_obat' => 'required_with:obat|exists:obat,id_obat',
            'obat.*.jumlah' => 'required_with:obat|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            // Validasi stok obat sebelum menyimpan
            if (isset($validated['obat']) && count($validated['obat']) > 0) {
                foreach ($validated['obat'] as $obatData) {
                    if (!empty($obatData['id_obat']) && $obatData['jumlah'] > 0) {
                        $obat = Obat::find($obatData['id_obat']);
                        if (!$obat) {
                            throw new \Exception("Obat dengan ID {$obatData['id_obat']} tidak ditemukan");
                        }
                        if ($obat->stok < $obatData['jumlah']) {
                            throw new \Exception("Stok obat {$obat->nama_obat} tidak mencukupi. Stok tersedia: {$obat->stok}, diminta: {$obatData['jumlah']}");
                        }
                    }
                }
            }

            $pemeriksaan = Pemeriksaan::create([
                'id_pendaftaran' => $validated['id_pendaftaran'],
                'id_dokter' => $validated['id_dokter'],
                'tanggal_periksa' => $validated['tanggal_periksa'],
                'diagnosa' => $validated['diagnosa'] ?? null,
                'tindakan' => $validated['tindakan'] ?? null,
                'biaya_tindakan' => $validated['biaya_tindakan'] ?? 0,
            ]);

            // Simpan obat yang diberikan dan kurangi stok
            if (isset($validated['obat']) && count($validated['obat']) > 0) {
                foreach ($validated['obat'] as $obatData) {
                    if (!empty($obatData['id_obat']) && $obatData['jumlah'] > 0) {
                        // Attach ke pivot table
                        $pemeriksaan->obats()->attach($obatData['id_obat'], [
                            'jumlah' => $obatData['jumlah']
                        ]);
                        
                        // Kurangi stok obat
                        $obat = Obat::find($obatData['id_obat']);
                        $obat->decrement('stok', $obatData['jumlah']);
                    }
                }
            }

            // Update status pendaftaran menjadi selesai
            $pendaftaran = Pendaftaran::find($request->id_pendaftaran);
            $pendaftaran->update(['status' => 'selesai']);

            DB::commit();

            return redirect()->route('pemeriksaan.riwayat')
                ->with('success', 'Data pemeriksaan berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan pemeriksaan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pemeriksaan = Pemeriksaan::with(['pendaftaran.pemilikHewan', 'pendaftaran.hewan', 'dokterHewan', 'pembayaran', 'obats'])
            ->findOrFail($id);
        return view('pemeriksaan.show', compact('pemeriksaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pemeriksaan = Pemeriksaan::with(['pendaftaran.pemilikHewan', 'dokterHewan'])->findOrFail($id);
        
        // Cek otorisasi: hanya dokter yang bersangkutan atau admin yang bisa edit
        $user = auth()->user();
        if ($user->role === 'dokter') {
            if ($pemeriksaan->dokterHewan->nama_dokter !== $user->name) {
                return redirect()->route('pemeriksaan.riwayat')
                    ->with('error', 'Anda tidak memiliki akses untuk mengedit pemeriksaan ini');
            }
        }
        
        return view('pemeriksaan.edit', compact('pemeriksaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        $validated = $request->validate([
            'tanggal_periksa' => 'required|date',
            'diagnosa' => 'nullable|string',
            'tindakan' => 'nullable|string'
        ]);

        $pemeriksaan->update($validated);

        return redirect()->route('pemeriksaan.riwayat')
            ->with('success', 'Data pemeriksaan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $pemeriksaan = Pemeriksaan::with(['pembayaran', 'obats'])->findOrFail($id);
        
        // Cek jika sudah ada pembayaran, tolak hapus
        if ($pemeriksaan->pembayaran) {
            return redirect()->route('pemeriksaan.riwayat')
                ->with('error', 'Pemeriksaan tidak dapat dihapus karena sudah memiliki data pembayaran. Hapus pembayaran terlebih dahulu.');
        }
        
        $aksi = $request->input('aksi', 'kembalikan');
        
        DB::beginTransaction();
        try {
            $pendaftaran = Pendaftaran::find($pemeriksaan->id_pendaftaran);
            
            // Kembalikan stok obat
            foreach ($pemeriksaan->obats as $obat) {
                $obat->increment('stok', $obat->pivot->jumlah);
            }
            
            // Hapus pemeriksaan_obat pivot
            $pemeriksaan->obats()->detach();
            
            if ($aksi === 'hapus_semua') {
                $pemeriksaan->delete();
                if ($pendaftaran) {
                    $pendaftaran->delete();
                }
                
                DB::commit();
                return redirect()->route('pemeriksaan.riwayat')
                    ->with('success', 'Data pemeriksaan dan pendaftaran berhasil dihapus');
            } else {
                $pemeriksaan->delete();
                if ($pendaftaran) {
                    $pendaftaran->update(['status' => 'menunggu']);
                }
                
                DB::commit();
                return redirect()->route('pemeriksaan.riwayat')
                    ->with('success', 'Data pemeriksaan berhasil dihapus dan status pendaftaran dikembalikan ke menunggu');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pemeriksaan.riwayat')
                ->with('error', 'Gagal menghapus pemeriksaan: ' . $e->getMessage());
        }
    }
}
