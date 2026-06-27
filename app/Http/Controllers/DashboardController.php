<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Invoice;
use App\Models\PengeluaranOperasional;
use App\Models\PurchaseOrder;
use App\Models\Setting;
use App\Models\StokGudang;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalPoBulanIni' => PurchaseOrder::whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->count(),
            'totalSupplierAktif' => Supplier::where('is_active', true)->count(),
            'nilaiStok' => StokGudang::join('bahan_baku', 'stok_gudang.bahan_baku_id', '=', 'bahan_baku.id')
                ->selectRaw('SUM(stok_gudang.qty_aktual * bahan_baku.harga_terakhir) as total')
                ->value('total') ?? 0,
            'totalInvoiceBelumBayar' => Invoice::where('status_bayar', 'Belum Dibayar')->count(),
            'nilaiInvoiceBelumBayar' => Invoice::where('status_bayar', '!=', 'Lunas')
                ->selectRaw('SUM(total_tagihan - jumlah_dibayar) as total')
                ->value('total') ?? 0,
            'totalPengeluaranBulanIni' => PengeluaranOperasional::whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->sum('nominal'),
            'totalInvoiceBulanIni' => Invoice::whereIn('status_bayar', ['Lunas', 'Sebagian'])
                ->whereMonth('tanggal_issued', now()->month)
                ->whereYear('tanggal_issued', now()->year)
                ->sum('jumlah_dibayar'),
            'anggaranBulanIni' => Setting::where('key', 'anggaran_bulanan')->value('value') ?? 50000000,
            'recentPo' => PurchaseOrder::with('supplier')
                ->latest()
                ->take(5)
                ->get(),
            'lowStockItems' => StokGudang::with('bahanBaku')
                ->join('bahan_baku', 'stok_gudang.bahan_baku_id', '=', 'bahan_baku.id')
                ->whereColumn('stok_gudang.qty_aktual', '<=', 'bahan_baku.stok_minimum')
                ->select('stok_gudang.*')
                ->take(5)
                ->get(),
        ];

        return view('dashboard', $data);
    }
}
