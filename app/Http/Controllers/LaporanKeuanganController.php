<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PengeluaranOperasional;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Ambil data invoice yang sudah dibayar (Lunas atau Sebagian)
        $invoices = Invoice::with('purchaseOrder.supplier')
            ->whereIn('status_bayar', ['Lunas', 'Sebagian'])
            ->whereMonth('tanggal_issued', $bulan)
            ->whereYear('tanggal_issued', $tahun)
            ->get();

        // Ambil data pengeluaran operasional
        $pengeluarans = PengeluaranOperasional::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $totalInvoice = $invoices->sum('jumlah_dibayar');
        $totalOpex = $pengeluarans->sum('nominal');
        $totalPengeluaran = $totalInvoice + $totalOpex;

        // Gabungkan untuk history detail jika diperlukan
        $transaksis = collect();

        foreach ($invoices as $inv) {
            $transaksis->push([
                'tanggal' => $inv->tanggal_issued->format('Y-m-d'),
                'keterangan' => 'Pembayaran Bahan Baku - ' . $inv->no_invoice . ' (' . ($inv->purchaseOrder->supplier->nama ?? 'Unknown') . ')',
                'kategori' => 'Bahan Baku',
                'nominal' => $inv->jumlah_dibayar
            ]);
        }

        foreach ($pengeluarans as $peng) {
            $transaksis->push([
                'tanggal' => $peng->tanggal->format('Y-m-d'),
                'keterangan' => $peng->keterangan ?? $peng->kategori,
                'kategori' => $peng->kategori,
                'nominal' => $peng->nominal
            ]);
        }

        $transaksis = $transaksis->sortByDesc('tanggal')->values();

        // Anggaran bulanan statis (Bisa diganti dinamis nanti)
        $anggaran = Setting::where('key', 'anggaran_bulanan')->value('value') ?? 50000000;
        $sisaAnggaran = $anggaran - $totalPengeluaran;

        return view('laporan.keuangan', compact('bulan', 'tahun', 'invoices', 'pengeluarans', 'totalInvoice', 'totalOpex', 'totalPengeluaran', 'transaksis', 'anggaran', 'sisaAnggaran'));
    }

    public function exportPdf(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Ambil data invoice yang sudah dibayar
        $invoices = Invoice::with('purchaseOrder.supplier')
            ->whereIn('status_bayar', ['Lunas', 'Sebagian'])
            ->whereMonth('tanggal_issued', $bulan)
            ->whereYear('tanggal_issued', $tahun)
            ->get();

        // Ambil data pengeluaran operasional
        $pengeluarans = PengeluaranOperasional::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $totalInvoice = $invoices->sum('jumlah_dibayar');
        $totalOpex = $pengeluarans->sum('nominal');
        $totalPengeluaran = $totalInvoice + $totalOpex;

        $transaksis = collect();
        foreach ($invoices as $inv) {
            $transaksis->push([
                'tanggal' => $inv->tanggal_issued->format('Y-m-d'),
                'keterangan' => 'Pembayaran Bahan Baku - ' . $inv->no_invoice,
                'kategori' => 'Bahan Baku',
                'nominal' => $inv->jumlah_dibayar
            ]);
        }
        foreach ($pengeluarans as $peng) {
            $transaksis->push([
                'tanggal' => $peng->tanggal->format('Y-m-d'),
                'keterangan' => $peng->keterangan ?? $peng->kategori,
                'kategori' => $peng->kategori,
                'nominal' => $peng->nominal
            ]);
        }
        $transaksis = $transaksis->sortByDesc('tanggal')->values();

        $anggaran = Setting::where('key', 'anggaran_bulanan')->value('value') ?? 50000000;
        $sisaAnggaran = $anggaran - $totalPengeluaran;

        $pdf = Pdf::loadView('laporan.keuangan-pdf', compact('bulan', 'tahun', 'totalInvoice', 'totalOpex', 'totalPengeluaran', 'transaksis', 'anggaran', 'sisaAnggaran'));
        
        return $pdf->stream("Laporan_Keuangan_SPPG_2_{$tahun}_{$bulan}.pdf");
    }
}
