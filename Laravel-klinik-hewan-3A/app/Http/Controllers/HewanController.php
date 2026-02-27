<?php

namespace App\Http\Controllers;

use App\Models\Hewan;
use App\Models\PemilikHewan;
use Illuminate\Http\Request;

class HewanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hewans = Hewan::with('pemilikHewan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('hewan.index', compact('hewans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pemilikHewans = PemilikHewan::all();
        $selectedPemilik = $request->query('pemilik');
        return view('hewan.create', compact('pemilikHewans', 'selectedPemilik'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_hewan' => 'required|string|max:10',
            'jenis_hewan' => 'required|string|max:10',
            'jenis_kelamin' => 'required|in:jantan,betina',
            'umur' => 'nullable|string|max:2',
            'id_pemilik' => 'required|exists:pemilik_hewan,id_pemilik'
        ]);

        Hewan::create($validated);

        return redirect()->route('hewan.index')
            ->with('success', 'Data hewan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $hewan = Hewan::with('pemilikHewan')->findOrFail($id);
        return view('hewan.show', compact('hewan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $hewan = Hewan::findOrFail($id);
        $pemilikHewans = PemilikHewan::all();
        return view('hewan.edit', compact('hewan', 'pemilikHewans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $hewan = Hewan::findOrFail($id);

        $validated = $request->validate([
            'nama_hewan' => 'required|string|max:10',
            'jenis_hewan' => 'required|string|max:10',
            'jenis_kelamin' => 'required|in:jantan,betina',
            'umur' => 'nullable|string|max:2',
            'id_pemilik' => 'required|exists:pemilik_hewan,id_pemilik'
        ]);

        $hewan->update($validated);

        return redirect()->route('hewan.index')
            ->with('success', 'Data hewan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hewan = Hewan::findOrFail($id);
        $hewan->delete();

        return redirect()->route('hewan.index')
            ->with('success', 'Data hewan berhasil dihapus');
    }
}
