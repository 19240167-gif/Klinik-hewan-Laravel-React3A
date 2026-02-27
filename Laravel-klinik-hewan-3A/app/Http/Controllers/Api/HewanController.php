<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hewan;
use Illuminate\Http\Request;

class HewanController extends Controller
{
    public function index()
    {
        $data = Hewan::with('pemilikHewan')->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $data]);
    }

    public function show(string $id)
    {
        $data = Hewan::with('pemilikHewan')->findOrFail($id);
        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_hewan' => 'required|string|max:10',
            'jenis_hewan' => 'required|string|max:10',
            'jenis_kelamin' => 'required|in:jantan,betina',
            'umur' => 'nullable|string|max:2',
            'id_pemilik' => 'required|exists:pemilik_hewan,id_pemilik',
        ]);

        $hewan = Hewan::create($validated);

        return response()->json(['data' => $hewan->load('pemilikHewan'), 'message' => 'Data hewan berhasil ditambahkan'], 201);
    }

    public function update(Request $request, string $id)
    {
        $hewan = Hewan::findOrFail($id);

        $validated = $request->validate([
            'nama_hewan' => 'required|string|max:10',
            'jenis_hewan' => 'required|string|max:10',
            'jenis_kelamin' => 'required|in:jantan,betina',
            'umur' => 'nullable|string|max:2',
            'id_pemilik' => 'required|exists:pemilik_hewan,id_pemilik',
        ]);

        $hewan->update($validated);

        return response()->json(['data' => $hewan->load('pemilikHewan'), 'message' => 'Data hewan berhasil diperbarui']);
    }

    public function destroy(string $id)
    {
        $hewan = Hewan::findOrFail($id);
        $hewan->delete();

        return response()->json(['message' => 'Data hewan berhasil dihapus']);
    }

    public function getByPemilik(string $idPemilik)
    {
        $data = Hewan::where('id_pemilik', $idPemilik)->get();
        return response()->json(['data' => $data]);
    }
}
