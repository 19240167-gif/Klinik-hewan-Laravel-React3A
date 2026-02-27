<?php

namespace App\Http\Controllers;

use App\Models\PemilikHewan;
use Illuminate\Http\Request;

class PemilikHewanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pemilikHewans = PemilikHewan::orderBy('created_at', 'desc')
            ->paginate(10);
        return view('pemilik_hewan.index', compact('pemilikHewans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pemilik_hewan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:25',
            'no_tlp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string'
        ]);

        $validated['jenis_pendaftaran'] = 'offline';

        PemilikHewan::create($validated);

        return redirect()->route('pemilik-hewan.index')
            ->with('success', 'Data pemilik hewan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pemilikHewan = PemilikHewan::with(['hewan', 'pendaftaran'])->findOrFail($id);
        return view('pemilik_hewan.show', compact('pemilikHewan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pemilikHewan = PemilikHewan::findOrFail($id);
        return view('pemilik_hewan.edit', compact('pemilikHewan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pemilikHewan = PemilikHewan::findOrFail($id);

        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:25',
            'no_tlp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string'
        ]);

        $pemilikHewan->update($validated);

        return redirect()->route('pemilik-hewan.index')
            ->with('success', 'Data pemilik hewan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pemilikHewan = PemilikHewan::findOrFail($id);
        $pemilikHewan->delete();

        return redirect()->route('pemilik-hewan.index')
            ->with('success', 'Data pemilik hewan berhasil dihapus');
    }
}
