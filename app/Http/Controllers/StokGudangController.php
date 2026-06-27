<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenyesuaianStokRequest;
use App\Models\BahanBaku;
use App\Models\KartuStok;
use App\Models\StokGudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokGudangController extends Controller
{
    public function index(Request $request)
    {
        $query = StokGudang::with('bahanBaku');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('bahanBaku', function($q) use ($search) {
                $q->where('nama_bahan', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $stokGudang = $query->paginate(15);
        $bahanBaku = BahanBaku::where('is_active', true)->orderBy('nama_bahan')->get();

        return view('stok-gudang.index', compact('stokGudang', 'bahanBaku'));
    }

    public function penyesuaian(PenyesuaianStokRequest $request)
    {
        try {
            DB::beginTransaction();

            $bahanBaku = BahanBaku::findOrFail($request->bahan_baku_id);
            $qty = $request->qty;
            $jenis = $request->jenis_penyesuaian;
            
            // Get current stock
            $stokGudang = StokGudang::firstOrCreate(
                ['bahan_baku_id' => $bahanBaku->id],
                ['qty_aktual' => 0]
            );

            // Calculate new stock
            $newQty = $stokGudang->qty_aktual;
            if ($jenis === 'Masuk') {
                $newQty += $qty;
            } else { // Keluar
                if ($newQty < $qty) {
                    throw new \Exception("Stok tidak mencukupi untuk penyesuaian keluar. Stok saat ini: " . $stokGudang->qty_aktual);
                }
                $newQty -= $qty;
            }

            // Update stok
            $stokGudang->update(['qty_aktual' => $newQty]);

            // Create Kartu Stok
            KartuStok::create([
                'bahan_baku_id' => $bahanBaku->id,
                'jenis_transaksi' => $jenis,
                'qty' => $qty,
                'referensi_tipe' => 'Penyesuaian',
                'tanggal' => now()->toDateString(),
                'saldo_akhir' => $newQty,
                'keterangan' => 'Penyesuaian Stok (Manual): ' . $request->keterangan,
            ]);

            DB::commit();

            return redirect()->route('stok-gudang.index')
                ->with('success', 'Penyesuaian stok berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal melakukan penyesuaian stok: ' . $e->getMessage());
        }
    }
}
