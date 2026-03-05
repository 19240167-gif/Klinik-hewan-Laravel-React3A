<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Pegawai;
use App\Models\Hewan;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index()
    {
        $data = Pendaftaran::with(['pemilikHewan', 'hewan', 'pegawai'])
            ->orderBy('tanggal_daftar', 'desc')
            ->get();
        return response()->json(['data' => $data]);
    }

    public function show(string $id)
    {
        $data = Pendaftaran::with(['pemilikHewan', 'hewan', 'pegawai', 'pemeriksaan'])
            ->findOrFail($id);
        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_hewan' => 'required|exists:hewan,id_hewan',
            'tanggal_daftar' => 'required|date',
            'keluhan' => 'nullable|string',
        ]);

        // Ambil pegawai berdasarkan user yang login
        $user = $request->user();
        if ($user->role == 'admin') {
            $pegawai = Pegawai::first();
            $validated['id_pegawai'] = $pegawai ? $pegawai->id_pegawai : null;
        } else {
            $pegawai = Pegawai::where('nama_pegawai', $user->name)->first();
            if (!$pegawai) {
                return response()->json(['message' => 'Data pegawai tidak ditemukan untuk user ini'], 422);
            }
            $validated['id_pegawai'] = $pegawai->id_pegawai;
        }

        $validated['status'] = 'menunggu';

        $pendaftaran = Pendaftaran::create($validated);
        $pendaftaran->load(['pemilikHewan', 'hewan', 'pegawai']);

        return response()->json(['data' => $pendaftaran, 'message' => 'Pendaftaran berhasil ditambahkan'], 201);
    }

    public function update(Request $request, string $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $validated = $request->validate([
            'id_hewan' => 'required|exists:hewan,id_hewan',
            'id_pegawai' => 'nullable|exists:pegawai,id_pegawai',
            'tanggal_daftar' => 'required|date',
            'status' => 'nullable|string|max:10',
            'keluhan' => 'nullable|string',
        ]);

        $pendaftaran->update($validated);
        $pendaftaran->load(['pemilikHewan', 'hewan', 'pegawai']);

        return response()->json(['data' => $pendaftaran, 'message' => 'Pendaftaran berhasil diperbarui']);
    }

    public function destroy(string $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->delete();

        return response()->json(['message' => 'Pendaftaran berhasil dihapus']);
    }
}
