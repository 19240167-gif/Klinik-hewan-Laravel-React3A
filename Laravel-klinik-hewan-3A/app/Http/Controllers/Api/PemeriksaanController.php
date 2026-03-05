<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\DokterHewan;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeriksaanController extends Controller
{
    public function index()
    {
        $query = Pemeriksaan::with(['pendaftaran.pemilikHewan', 'pendaftaran.hewan', 'dokterHewan', 'obats', 'pembayaran']);

        // Jika user adalah dokter, hanya tampilkan pemeriksaan miliknya
        $user = auth()->user();
        if ($user && $user->role === 'dokter') {
            $dokter = DokterHewan::where('nama_dokter', $user->name)->first();
            if ($dokter) {
                $query->where('id_dokter', $dokter->id_dokter);
            }
        }

        $data = $query->orderBy('tanggal_periksa', 'desc')->get();
        return response()->json(['data' => $data]);
    }

    /**
     * Daftar pendaftaran yang menunggu untuk diperiksa
     */
    public function pending()
    {
        $data = Pendaftaran::with(['pemilikHewan', 'hewan', 'pegawai'])
            ->where('status', 'menunggu')
            ->orderBy('tanggal_daftar', 'desc')
            ->get();
        return response()->json(['data' => $data]);
    }

    public function show(string $id)
    {
        $data = Pemeriksaan::with(['pendaftaran.pemilikHewan', 'pendaftaran.hewan', 'dokterHewan', 'obats', 'pembayaran'])
            ->findOrFail($id);
        return response()->json(['data' => $data]);
    }

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
            'obat.*.jumlah' => 'required_with:obat|integer|min:1',
        ]);

        // Jika user dokter, paksa menggunakan id_dokter miliknya sendiri
        $user = auth()->user();
        if ($user && $user->role === 'dokter') {
            $dokter = DokterHewan::where('nama_dokter', $user->name)->first();
            if ($dokter) {
                $validated['id_dokter'] = $dokter->id_dokter;
            }
        }

        DB::beginTransaction();
        try {
            // Validasi stok obat
            if (isset($validated['obat']) && count($validated['obat']) > 0) {
                foreach ($validated['obat'] as $obatData) {
                    if (!empty($obatData['id_obat']) && $obatData['jumlah'] > 0) {
                        $obat = Obat::find($obatData['id_obat']);
                        if (!$obat) {
                            throw new \Exception("Obat dengan ID {$obatData['id_obat']} tidak ditemukan");
                        }
                        if ($obat->stok < $obatData['jumlah']) {
                            throw new \Exception("Stok obat {$obat->nama_obat} tidak mencukupi. Stok: {$obat->stok}, diminta: {$obatData['jumlah']}");
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

            // Simpan obat dan kurangi stok
            if (isset($validated['obat']) && count($validated['obat']) > 0) {
                foreach ($validated['obat'] as $obatData) {
                    if (!empty($obatData['id_obat']) && $obatData['jumlah'] > 0) {
                        $pemeriksaan->obats()->attach($obatData['id_obat'], [
                            'jumlah' => $obatData['jumlah'],
                        ]);
                        $obat = Obat::find($obatData['id_obat']);
                        $obat->decrement('stok', $obatData['jumlah']);
                    }
                }
            }

            // Update status pendaftaran
            $pendaftaran = Pendaftaran::find($validated['id_pendaftaran']);
            $pendaftaran->update(['status' => 'selesai']);

            DB::commit();

            $pemeriksaan->load(['pendaftaran.pemilikHewan', 'pendaftaran.hewan', 'dokterHewan', 'obats']);

            return response()->json(['data' => $pemeriksaan, 'message' => 'Pemeriksaan berhasil ditambahkan'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan pemeriksaan: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        $validated = $request->validate([
            'tanggal_periksa' => 'required|date',
            'diagnosa' => 'nullable|string',
            'tindakan' => 'nullable|string',
        ]);

        $pemeriksaan->update($validated);
        $pemeriksaan->load(['pendaftaran.pemilikHewan', 'pendaftaran.hewan', 'dokterHewan', 'obats']);

        return response()->json(['data' => $pemeriksaan, 'message' => 'Pemeriksaan berhasil diperbarui']);
    }

    public function destroy(string $id)
    {
        $pemeriksaan = Pemeriksaan::with(['pembayaran', 'obats'])->findOrFail($id);

        if ($pemeriksaan->pembayaran) {
            return response()->json(['message' => 'Pemeriksaan tidak dapat dihapus karena sudah memiliki pembayaran'], 422);
        }

        DB::beginTransaction();
        try {
            $pendaftaran = Pendaftaran::find($pemeriksaan->id_pendaftaran);

            // Kembalikan stok obat
            foreach ($pemeriksaan->obats as $obat) {
                $obat->increment('stok', $obat->pivot->jumlah);
            }

            $pemeriksaan->obats()->detach();
            $pemeriksaan->delete();

            if ($pendaftaran) {
                $pendaftaran->update(['status' => 'menunggu']);
            }

            DB::commit();
            return response()->json(['message' => 'Pemeriksaan berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menghapus pemeriksaan: ' . $e->getMessage()], 500);
        }
    }
}
