<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pemeriksaan;
use App\Models\DetailPembayaranObat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembayarans = Pembayaran::with(['pemeriksaan.pendaftaran.pemilikHewan', 'pemeriksaan.pendaftaran.hewan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('pembayaran.index', compact('pembayarans'));
    }

    /**
     * Daftar pemeriksaan yang belum dibayar
     */
    public function pending()
    {
        $pemeriksaans = Pemeriksaan::with(['pendaftaran.pemilikHewan', 'pendaftaran.hewan', 'dokterHewan', 'obats'])
            ->whereDoesntHave('pembayaran')
            ->orderBy('tanggal_periksa', 'desc')
            ->paginate(10);
        return view('pembayaran.pending', compact('pemeriksaans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pemeriksaanId = $request->query('pemeriksaan');
        
        if (!$pemeriksaanId) {
            return redirect()->route('pembayaran.pending')
                ->with('error', 'Silakan pilih pemeriksaan yang akan diproses pembayarannya');
        }
        
        $pemeriksaan = Pemeriksaan::with(['pendaftaran.pemilikHewan', 'pendaftaran.hewan', 'dokterHewan', 'obats'])
            ->whereDoesntHave('pembayaran')
            ->where('id_pemeriksaan', $pemeriksaanId)
            ->firstOrFail();
        
        // Biaya tindakan medis (termasuk biaya pemeriksaan)
        $biayaTindakan = $pemeriksaan->biaya_tindakan ?? 0;
        
        // Hitung total obat
        $biayaObat = 0;
        foreach ($pemeriksaan->obats as $obat) {
            $biayaObat += $obat->harga_obat * $obat->pivot->jumlah;
        }
        
        // Total keseluruhan
        $totalBayar = $biayaTindakan + $biayaObat;
        
        return view('pembayaran.create', compact('pemeriksaan', 'biayaTindakan', 'biayaObat', 'totalBayar'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pemeriksaan' => 'required|exists:pemeriksaan,id_pemeriksaan',
            'metode_bayar' => 'required|string|max:10',
        ]);

        DB::beginTransaction();
        try {
            $pemeriksaan = Pemeriksaan::with(['obats', 'dokterHewan'])->findOrFail($validated['id_pemeriksaan']);
            
            // Biaya tindakan medis (termasuk biaya pemeriksaan)
            $biayaTindakan = $pemeriksaan->biaya_tindakan ?? 0;
            
            // Hitung total obat
            $biayaObat = 0;
            foreach ($pemeriksaan->obats as $obat) {
                $biayaObat += $obat->harga_obat * $obat->pivot->jumlah;
            }
            
            // Total keseluruhan
            $totalBayar = $biayaTindakan + $biayaObat;
            
            // Buat pembayaran
            $pembayaran = Pembayaran::create([
                'id_pemeriksaan' => $validated['id_pemeriksaan'],
                'tanggal_bayar' => now(),
                'metode_bayar' => $validated['metode_bayar'],
                'total_bayar' => $totalBayar,
            ]);
            
            // Simpan detail pembayaran obat
            foreach ($pemeriksaan->obats as $obat) {
                DetailPembayaranObat::create([
                    'id_pembayaran' => $pembayaran->id_pembayaran,
                    'id_obat' => $obat->id_obat,
                    'jumlah' => $obat->pivot->jumlah,
                    'subtotal' => $obat->harga_obat * $obat->pivot->jumlah,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('pembayaran.show', $pembayaran->id_pembayaran)
                ->with('success', 'Pembayaran berhasil diproses');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pembayaran = Pembayaran::with([
            'pemeriksaan.pendaftaran.pemilikHewan',
            'pemeriksaan.pendaftaran.hewan',
            'pemeriksaan.dokterHewan',
            'detailPembayaranObat.obat'
        ])->findOrFail($id);
        
        return view('pembayaran.show', compact('pembayaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Tidak diimplementasikan - pembayaran tidak bisa diedit
        return redirect()->route('pembayaran.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Pembayaran tidak bisa diupdate untuk menjaga integritas data
        return redirect()->route('pembayaran.index')
            ->with('error', 'Pembayaran tidak dapat diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $pembayaran = Pembayaran::findOrFail($id);
            
            // Hapus detail pembayaran obat terlebih dahulu
            DetailPembayaranObat::where('id_pembayaran', $id)->delete();
            
            // Hapus pembayaran
            $pembayaran->delete();
            
            DB::commit();
            return redirect()->route('pembayaran.index')
                ->with('success', 'Data pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pembayaran.index')
                ->with('error', 'Gagal menghapus pembayaran: ' . $e->getMessage());
        }
    }
}
