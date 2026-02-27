<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PemilikHewan;
use Illuminate\Http\Request;

class PemilikHewanController extends Controller
{
    public function index()
    {
        $data = PemilikHewan::orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $data]);
    }

    public function show(string $id)
    {
        $data = PemilikHewan::with(['hewan'])->findOrFail($id);
        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:25',
            'no_tlp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        $validated['jenis_pendaftaran'] = 'offline';

        $pemilik = PemilikHewan::create($validated);

        return response()->json(['data' => $pemilik, 'message' => 'Data pemilik hewan berhasil ditambahkan'], 201);
    }

    public function update(Request $request, string $id)
    {
        $pemilik = PemilikHewan::findOrFail($id);

        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:25',
            'no_tlp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        $pemilik->update($validated);

        return response()->json(['data' => $pemilik, 'message' => 'Data pemilik hewan berhasil diperbarui']);
    }

    public function destroy(string $id)
    {
        $pemilik = PemilikHewan::findOrFail($id);
        $pemilik->delete();

        return response()->json(['message' => 'Data pemilik hewan berhasil dihapus']);
    }
}
