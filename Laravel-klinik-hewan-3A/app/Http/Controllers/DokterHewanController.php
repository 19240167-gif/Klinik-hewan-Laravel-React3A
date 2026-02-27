<?php

namespace App\Http\Controllers;

use App\Models\DokterHewan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DokterHewanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokters = DokterHewan::orderBy('created_at', 'desc')->paginate(10);
        return view('dokter.index', compact('dokters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dokter.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_dokter' => 'required|string|max:25',
            'no_sip' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        DB::beginTransaction();
        try {
            // Buat user account untuk dokter
            $user = User::create([
                'name' => $validated['nama_dokter'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'dokter',
            ]);

            // Buat data dokter hewan - ID auto generate
            DokterHewan::create([
                'nama_dokter' => $validated['nama_dokter'],
                'no_sip' => $validated['no_sip'],
            ]);

            DB::commit();

            return redirect()->route('dokter-hewan.index')
                ->with('success', 'Data dokter hewan berhasil ditambahkan beserta akun login');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data dokter: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dokter = DokterHewan::with('pemeriksaan')->findOrFail($id);
        return view('dokter.show', compact('dokter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dokter = DokterHewan::findOrFail($id);
        return view('dokter.edit', compact('dokter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dokter = DokterHewan::findOrFail($id);

        $validated = $request->validate([
            'nama_dokter' => 'required|string|max:25',
            'no_sip' => 'nullable|string|max:20',
        ]);

        // Update juga nama user jika ada
        $user = User::where('name', $dokter->nama_dokter)->first();
        if ($user && $dokter->nama_dokter != $validated['nama_dokter']) {
            $user->update(['name' => $validated['nama_dokter']]);
        }

        $dokter->update($validated);

        return redirect()->route('dokter-hewan.index')
            ->with('success', 'Data dokter hewan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dokter = DokterHewan::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Hapus user terkait jika ada
            $user = User::where('name', $dokter->nama_dokter)->where('role', 'dokter')->first();
            if ($user) {
                $user->delete();
            }
            
            // Set null untuk pemeriksaan yang terkait
            $dokter->pemeriksaan()->update(['id_dokter' => null]);
            
            $dokter->delete();
            
            DB::commit();

            return redirect()->route('dokter-hewan.index')
                ->with('success', 'Data dokter hewan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('dokter-hewan.index')
                ->with('error', 'Gagal menghapus dokter: ' . $e->getMessage());
        }
    }
}
