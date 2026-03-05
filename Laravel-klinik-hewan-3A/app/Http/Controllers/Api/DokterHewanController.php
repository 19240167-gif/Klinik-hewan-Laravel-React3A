<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DokterHewan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DokterHewanController extends Controller
{
    public function index()
    {
        $data = DokterHewan::orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $data]);
    }

    public function show(string $id)
    {
        $data = DokterHewan::with('pemeriksaan')->findOrFail($id);
        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_dokter' => 'required|string|max:25',
            'no_sip' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        DB::beginTransaction();
        try {
            User::create([
                'name' => $validated['nama_dokter'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'dokter',
            ]);

            $dokter = DokterHewan::create([
                'nama_dokter' => $validated['nama_dokter'],
                'no_sip' => $validated['no_sip'],
            ]);

            DB::commit();
            return response()->json(['data' => $dokter, 'message' => 'Data dokter hewan berhasil ditambahkan'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menambahkan dokter: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $dokter = DokterHewan::findOrFail($id);

        $validated = $request->validate([
            'nama_dokter' => 'required|string|max:25',
            'no_sip' => 'nullable|string|max:20',
        ]);

        // Update nama user jika berubah
        $user = User::where('name', $dokter->nama_dokter)->where('role', 'dokter')->first();
        if ($user && $dokter->nama_dokter != $validated['nama_dokter']) {
            $user->update(['name' => $validated['nama_dokter']]);
        }

        $dokter->update($validated);

        return response()->json(['data' => $dokter, 'message' => 'Data dokter hewan berhasil diperbarui']);
    }

    public function destroy(string $id)
    {
        $dokter = DokterHewan::findOrFail($id);

        DB::beginTransaction();
        try {
            $user = User::where('name', $dokter->nama_dokter)->where('role', 'dokter')->first();
            if ($user) {
                $user->delete();
            }

            $dokter->pemeriksaan()->update(['id_dokter' => null]);
            $dokter->delete();

            DB::commit();
            return response()->json(['message' => 'Data dokter hewan berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menghapus dokter: ' . $e->getMessage()], 500);
        }
    }
}
