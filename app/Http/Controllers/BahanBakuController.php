<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Http\Requests\BahanBakuRequest;
use Illuminate\Http\Request;

class BahanBakuController extends Controller
{
    public function index(Request $request)
    {
        $query = BahanBaku::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_bahan', 'like', "%{$search}%");
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $bahanBaku = $query->orderBy('nama_bahan')->paginate(10)->withQueryString();

        return view('bahan-baku.index', compact('bahanBaku'));
    }

    public function create()
    {
        return view('bahan-baku.create');
    }

    public function store(BahanBakuRequest $request)
    {
        BahanBaku::create($request->validated());

        return redirect()->route('bahan-baku.index')
            ->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function edit(BahanBaku $bahanBaku)
    {
        return view('bahan-baku.edit', compact('bahanBaku'));
    }

    public function update(BahanBakuRequest $request, BahanBaku $bahanBaku)
    {
        $bahanBaku->update($request->validated());

        return redirect()->route('bahan-baku.index')
            ->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function destroy(BahanBaku $bahanBaku)
    {
        if ($bahanBaku->poItems()->exists()) {
            return redirect()->route('bahan-baku.index')
                ->with('error', 'Bahan baku tidak bisa dihapus karena sudah digunakan di PO.');
        }

        $bahanBaku->delete();

        return redirect()->route('bahan-baku.index')
            ->with('success', 'Bahan baku berhasil dihapus.');
    }
}
