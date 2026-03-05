<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pemeriksaan;
use App\Models\DetailPembayaranObat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index()
    {
        $data = Pembayaran::with([
            'pemeriksaan.pendaftaran.pemilikHewan',
            'pemeriksaan.pendaftaran.hewan',
            'pemeriksaan.dokterHewan',
            'detailPembayaranObat.obat',
        ])->orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    /**
     * Daftar pemeriksaan yang belum dibayar
     */
    public function pending()
    {
        $data = Pemeriksaan::with(['pendaftaran.pemilikHewan', 'pendaftaran.hewan', 'dokterHewan', 'obats'])
            ->whereDoesntHave('pembayaran')
            ->orderBy('tanggal_periksa', 'desc')
            ->get();

        return response()->json(['data' => $data]);
    }

    public function show(string $id)
    {
        $data = Pembayaran::with([
            'pemeriksaan.pendaftaran.pemilikHewan',
            'pemeriksaan.pendaftaran.hewan',
            'pemeriksaan.dokterHewan',
            'detailPembayaranObat.obat',
        ])->findOrFail($id);

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pemeriksaan' => 'required|exists:pemeriksaan,id_pemeriksaan',
            'metode_bayar' => 'required|string|max:10',
        ]);

        DB::beginTransaction();
        try {
            $pemeriksaan = Pemeriksaan::with('obats')->findOrFail($validated['id_pemeriksaan']);

            // Cek apakah sudah ada pembayaran
            if ($pemeriksaan->pembayaran) {
                return response()->json(['message' => 'Pemeriksaan ini sudah memiliki pembayaran'], 422);
            }

            $biayaTindakan = $pemeriksaan->biaya_tindakan ?? 0;

            $biayaObat = 0;
            foreach ($pemeriksaan->obats as $obat) {
                $biayaObat += $obat->harga_obat * $obat->pivot->jumlah;
            }

            $totalBayar = $biayaTindakan + $biayaObat;

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

            $pembayaran->load([
                'pemeriksaan.pendaftaran.pemilikHewan',
                'pemeriksaan.pendaftaran.hewan',
                'pemeriksaan.dokterHewan',
                'detailPembayaranObat.obat',
            ]);

            return response()->json(['data' => $pembayaran, 'message' => 'Pembayaran berhasil diproses'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal memproses pembayaran: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        return response()->json(['message' => 'Pembayaran tidak dapat diubah'], 422);
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $pembayaran = Pembayaran::findOrFail($id);

            DetailPembayaranObat::where('id_pembayaran', $id)->delete();
            $pembayaran->delete();

            DB::commit();
            return response()->json(['message' => 'Pembayaran berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menghapus pembayaran: ' . $e->getMessage()], 500);
        }
    }
}
