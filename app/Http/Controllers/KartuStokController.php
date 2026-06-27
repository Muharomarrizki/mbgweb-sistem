<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\KartuStok;
use Illuminate\Http\Request;

class KartuStokController extends Controller
{
    public function index(Request $request)
    {
        $query = BahanBaku::with('stokGudang')->where('is_active', true);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_bahan', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
        }

        $bahanBaku = $query->paginate(15);

        return view('kartu-stok.index', compact('bahanBaku'));
    }

    public function show(Request $request, BahanBaku $kartu_stok)
    {
        $bahanBaku = $kartu_stok; // We injected BahanBaku because the route uses 'kartu-stok' parameter to point to BahanBaku
        
        $query = KartuStok::where('bahan_baku_id', $bahanBaku->id)
                 ->orderBy('created_at', 'desc')
                 ->orderBy('id', 'desc');
                 
        if ($request->has('bulan') && $request->has('tahun')) {
            $query->whereMonth('created_at', $request->bulan)
                  ->whereYear('created_at', $request->tahun);
        }

        $riwayat = $query->paginate(20);

        return view('kartu-stok.show', compact('bahanBaku', 'riwayat'));
    }
}
