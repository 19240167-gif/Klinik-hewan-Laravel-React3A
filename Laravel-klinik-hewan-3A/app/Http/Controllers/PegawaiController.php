<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pegawais = Pegawai::orderBy('created_at', 'desc')->paginate(10);
        return view('pegawai.index', compact('pegawais'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pegawai.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pegawai' => 'required|string|max:25',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'no_telepon_pegawai' => 'nullable|string|max:13',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        DB::beginTransaction();
        try {
            // Buat user account untuk pegawai
            $user = User::create([
                'name' => $validated['nama_pegawai'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'pegawai',
            ]);

            // Buat data pegawai - ID auto generate
            Pegawai::create([
                'nama_pegawai' => $validated['nama_pegawai'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_telepon_pegawai' => $validated['no_telepon_pegawai'],
            ]);

            DB::commit();

            return redirect()->route('pegawai.index')
                ->with('success', 'Data pegawai berhasil ditambahkan beserta akun login');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data pegawai: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pegawai = Pegawai::with('pendaftaran')->findOrFail($id);
        return view('pegawai.show', compact('pegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('pegawai.edit', compact('pegawai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $validated = $request->validate([
            'nama_pegawai' => 'required|string|max:25',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'no_telepon_pegawai' => 'nullable|string|max:13',
        ]);

        $pegawai->update($validated);

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        
        // Cek apakah pegawai masih memiliki pendaftaran
        if ($pegawai->pendaftaran()->count() > 0) {
            return redirect()->route('pegawai.index')
                ->with('error', 'Pegawai tidak dapat dihapus karena masih memiliki data pendaftaran');
        }

        $pegawai->delete();

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus');
    }
}
