<?php

namespace App\Http\Controllers;

use App\Models\DistribusiMbg;
use App\Models\ProduksiMbg;
use Illuminate\Http\Request;

class DistribusiController extends Controller
{
    public function index()
    {
        $distribusis = DistribusiMbg::with('produksi')->latest()->get();
        $produksis = ProduksiMbg::latest('tanggal')->get();
        
        return view('distribusi.index', compact('distribusis', 'produksis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produksi_id' => 'required|exists:produksi_mbg,id',
            'sekolah_tujuan' => 'required|string|max:255',
            'jumlah_porsi' => 'required|integer|min:1',
            'jam_kirim' => 'nullable|date_format:H:i',
            'petugas_nama' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $produksi = ProduksiMbg::findOrFail($request->produksi_id);

        // Optional logic: check if total distribusi doesn't exceed produksi
        $totalDistribusi = $produksi->distribusi()->sum('jumlah_porsi');
        if (($totalDistribusi + $request->jumlah_porsi) > $produksi->jumlah_porsi) {
            return back()->with('error', 'Jumlah porsi distribusi melebihi sisa porsi produksi! Sisa porsi: ' . ($produksi->jumlah_porsi - $totalDistribusi));
        }

        DistribusiMbg::create($request->all());

        return redirect()->route('distribusi.index')->with('success', 'Data Distribusi berhasil dicatat.');
    }

    public function destroy(DistribusiMbg $distribusi)
    {
        $distribusi->delete();
        return redirect()->route('distribusi.index')->with('success', 'Data Distribusi berhasil dihapus.');
    }
}
