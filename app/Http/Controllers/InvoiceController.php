<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['purchaseOrder.supplier']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('no_invoice', 'like', "%{$search}%")
                  ->orWhereHas('purchaseOrder', function($q) use ($search) {
                      $q->where('no_po', 'like', "%{$search}%")
                        ->orWhereHas('supplier', function($s) use ($search) {
                            $s->where('nama', 'like', "%{$search}%");
                        });
                  });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status_bayar', $request->status);
        }

        $invoices = $query->orderBy('tanggal_issued', 'desc')->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['purchaseOrder.supplier', 'purchaseOrder.items.bahanBaku']);
        
        return view('invoices.show', compact('invoice'));
    }

    public function updatePayment(Request $request, Invoice $invoice)
    {
        if (!auth()->user()->hasRole('bendahara')) {
            return redirect()->back()->with('error', 'Hanya Bendahara yang dapat melakukan pembayaran.');
        }

        $request->validate([
            'jumlah_dibayar' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $bayarBaru = $request->jumlah_dibayar;
        $totalTagihan = $invoice->total_tagihan;

        $status = 'Belum Dibayar';
        if ($bayarBaru >= $totalTagihan) {
            $status = 'Lunas';
            $bayarBaru = $totalTagihan; // Prevent overpayment
        } elseif ($bayarBaru > 0) {
            $status = 'Sebagian';
        }

        $invoice->update([
            'jumlah_dibayar' => $bayarBaru,
            'status_bayar' => $status,
            'catatan' => $request->catatan ?? $invoice->catatan,
        ]);

        return redirect()->route('invoices.show', $invoice->id)
            ->with('success', 'Pembayaran berhasil diperbarui dengan status: ' . $status);
    }

    public function exportPdf(Invoice $invoice)
    {
        $invoice->load(['purchaseOrder.supplier', 'purchaseOrder.items.bahanBaku']);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

        return $pdf->download("INV-{$invoice->no_invoice}.pdf");
    }
}
