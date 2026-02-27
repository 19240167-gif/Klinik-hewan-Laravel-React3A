<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index()
    {
        $data = Obat::orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $data]);
    }

    public function show(string $id)
    {
        $data = Obat::findOrFail($id);
        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:20|unique:obat,nama_obat',
            'jenis_obat' => 'nullable|string|max:15',
            'harga_obat' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'tanggal_kadaluarsa' => 'nullable|date',
        ]);

        $obat = Obat::create($validated);

        return response()->json(['data' => $obat, 'message' => 'Data obat berhasil ditambahkan'], 201);
    }

    public function update(Request $request, string $id)
    {
        $obat = Obat::findOrFail($id);

        $validated = $request->validate([
            'nama_obat' => 'required|string|max:20|unique:obat,nama_obat,' . $id . ',id_obat',
            'jenis_obat' => 'nullable|string|max:15',
            'harga_obat' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'tanggal_kadaluarsa' => 'nullable|date',
        ]);

        $obat->update($validated);

        return response()->json(['data' => $obat, 'message' => 'Data obat berhasil diperbarui']);
    }

    public function destroy(string $id)
    {
        $obat = Obat::findOrFail($id);

        if ($obat->detailPembayaranObat()->count() > 0) {
            return response()->json(['message' => 'Obat tidak dapat dihapus karena sudah digunakan dalam pembayaran'], 422);
        }

        $obat->delete();

        return response()->json(['message' => 'Data obat berhasil dihapus']);
    }
}
