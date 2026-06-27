<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranOperasional;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluarans = PengeluaranOperasional::with('createdBy')->latest('tanggal')->get();
        return view('pengeluaran.index', compact('pengeluarans'));
    }

    public function create()
    {
        // For simplicity, we can use a modal in index or a separate create view.
        // Let's assume a modal or inline form on index, but provide store logic.
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0.01',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        PengeluaranOperasional::create([
            'kategori' => $validated['kategori'],
            'nominal' => $validated['nominal'],
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'] ?? null,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran operasional berhasil dicatat.');
    }

    public function destroy(PengeluaranOperasional $pengeluaran)
    {
        $pengeluaran->delete();
        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran operasional berhasil dihapus.');
    }
}
