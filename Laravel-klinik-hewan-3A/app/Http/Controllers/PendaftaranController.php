<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\PemilikHewan;
use App\Models\Pegawai;
use App\Models\Hewan;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pendaftarans = Pendaftaran::with(['pemilikHewan', 'hewan', 'pegawai'])
            ->orderBy('tanggal_daftar', 'desc')
            ->paginate(10);
        return view('pendaftaran.index', compact('pendaftarans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pemilikHewans = PemilikHewan::all();
        return view('pendaftaran.create', compact('pemilikHewans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_hewan' => 'required|exists:hewan,id_hewan',
            'tanggal_daftar' => 'required|date',
            'keluhan' => 'nullable|string'
        ]);

        // Ambil pegawai berdasarkan user yang login
        // Jika admin, ambil pegawai pertama atau bisa dibiarkan null
        if (auth()->user()->role == 'admin') {
            // Admin bisa tidak punya id_pegawai atau ambil pegawai pertama
            $pegawai = Pegawai::first();
            $validated['id_pegawai'] = $pegawai ? $pegawai->id_pegawai : null;
        } else {
            // Untuk role pegawai
            $pegawai = Pegawai::where('nama_pegawai', auth()->user()->name)->first();
            
            if (!$pegawai) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Data pegawai tidak ditemukan untuk user ini');
            }
            $validated['id_pegawai'] = $pegawai->id_pegawai;
        }

        $validated['status'] = 'menunggu'; // Status otomatis menunggu saat dibuat

        Pendaftaran::create($validated);

        return redirect()->route('pendaftaran.index')
            ->with('success', 'Pendaftaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pendaftaran = Pendaftaran::with(['pemilikHewan', 'pegawai', 'pemeriksaan'])
            ->findOrFail($id);
        return view('pendaftaran.show', compact('pendaftaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pendaftaran = Pendaftaran::with('hewan')->findOrFail($id);
        $pemilikHewans = PemilikHewan::all();
        $pegawais = Pegawai::all();
        
        // Load semua hewan dari pemilik yang dipilih
        $hewans = [];
        if ($pendaftaran->hewan && $pendaftaran->hewan->id_pemilik) {
            $hewans = Hewan::where('id_pemilik', $pendaftaran->hewan->id_pemilik)->get();
        }
        
        return view('pendaftaran.edit', compact('pendaftaran', 'pemilikHewans', 'pegawais', 'hewans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $validated = $request->validate([
            'id_hewan' => 'required|exists:hewan,id_hewan',
            'id_pegawai' => 'required|exists:pegawai,id_pegawai',
            'tanggal_daftar' => 'required|date',
            'status' => 'required|string|max:10',
            'keluhan' => 'nullable|string'
        ]);

        $pendaftaran->update($validated);

        return redirect()->route('pendaftaran.index')
            ->with('success', 'Pendaftaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->delete();

        return redirect()->route('pendaftaran.index')
            ->with('success', 'Pendaftaran berhasil dihapus');
    }

    /**
     * Get hewan by pemilik (AJAX endpoint)
     */
    public function getHewanByPemilik($id_pemilik)
    {
        $hewans = Hewan::where('id_pemilik', $id_pemilik)->get();
        return response()->json($hewans);
    }
}
