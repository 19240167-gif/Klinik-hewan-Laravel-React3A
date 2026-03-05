<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function index()
    {
        $data = Pegawai::orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $data]);
    }

    public function show(string $id)
    {
        $data = Pegawai::with('pendaftaran')->findOrFail($id);
        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pegawai' => 'required|string|max:25',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'no_telepon_pegawai' => 'nullable|string|max:13',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        DB::beginTransaction();
        try {
            User::create([
                'name' => $validated['nama_pegawai'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'pegawai',
            ]);

            $pegawai = Pegawai::create([
                'nama_pegawai' => $validated['nama_pegawai'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_telepon_pegawai' => $validated['no_telepon_pegawai'],
            ]);

            DB::commit();
            return response()->json(['data' => $pegawai, 'message' => 'Data pegawai berhasil ditambahkan'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menambahkan pegawai: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $validated = $request->validate([
            'nama_pegawai' => 'required|string|max:25',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'no_telepon_pegawai' => 'nullable|string|max:13',
        ]);

        $pegawai->update($validated);

        return response()->json(['data' => $pegawai, 'message' => 'Data pegawai berhasil diperbarui']);
    }

    public function destroy(string $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        if ($pegawai->pendaftaran()->count() > 0) {
            return response()->json(['message' => 'Pegawai tidak dapat dihapus karena masih memiliki data pendaftaran'], 422);
        }

        $pegawai->delete();

        return response()->json(['message' => 'Data pegawai berhasil dihapus']);
    }
}
