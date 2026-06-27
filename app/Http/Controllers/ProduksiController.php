<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\KartuStok;
use App\Models\ProduksiMbg;
use App\Models\ProduksiItem;
use App\Models\StokGudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduksiController extends Controller
{
    public function index()
    {
        $produksis = ProduksiMbg::with('createdBy')->latest('tanggal')->get();
        return view('produksi.index', compact('produksis'));
    }

    public function create()
    {
        // Get all bahan baku that are in stock
        $bahanBakus = BahanBaku::whereHas('stokGudang', function ($q) {
            $q->where('qty_aktual', '>', 0);
        })->get();
        
        return view('produksi.create', compact('bahanBakus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'menu_nama' => 'required|string|max:255',
            'jumlah_porsi' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.bahan_baku_id' => 'required|exists:bahan_baku,id',
            'items.*.qty_digunakan' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            $produksi = ProduksiMbg::create([
                'tanggal' => $request->tanggal,
                'menu_nama' => $request->menu_nama,
                'jumlah_porsi' => $request->jumlah_porsi,
                'catatan' => $request->catatan,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                // Insert into ProduksiItem
                ProduksiItem::create([
                    'produksi_id' => $produksi->id,
                    'bahan_baku_id' => $item['bahan_baku_id'],
                    'qty_digunakan' => $item['qty_digunakan'],
                ]);

                // Deduct from StokGudang
                $stok = StokGudang::firstOrCreate(
                    ['bahan_baku_id' => $item['bahan_baku_id']],
                    ['qty_aktual' => 0]
                );

                if ($stok->qty_aktual < $item['qty_digunakan']) {
                    throw new \Exception("Stok tidak mencukupi untuk bahan baku ID: " . $item['bahan_baku_id']);
                }

                $stok->qty_aktual -= $item['qty_digunakan'];
                $stok->save();

                // Log to KartuStok
                KartuStok::create([
                    'bahan_baku_id' => $item['bahan_baku_id'],
                    'jenis_transaksi' => 'Keluar',
                    'qty' => $item['qty_digunakan'],
                    'referensi_tipe' => 'Produksi',
                    'referensi_id' => $produksi->id,
                    'tanggal' => $request->tanggal,
                    'saldo_akhir' => $stok->qty_aktual,
                    'keterangan' => "Pemakaian bahan baku untuk produksi Menu: {$request->menu_nama}",
                ]);
            }

            DB::commit();

            return redirect()->route('produksi.index')->with('success', 'Produksi berhasil dicatat dan stok gudang telah dipotong.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mencatat produksi: ' . $e->getMessage());
        }
    }

    public function show(ProduksiMbg $produksi)
    {
        $produksi->load(['items.bahanBaku', 'createdBy', 'distribusi']);
        return view('produksi.show', compact('produksi'));
    }
}
