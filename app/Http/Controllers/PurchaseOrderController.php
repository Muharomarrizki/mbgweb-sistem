<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Services\PoNumberGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with('supplier');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_po', 'like', "%{$search}%")
                  ->orWhereHas('supplier', fn($q) => $q->where('nama', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $purchaseOrders = $query->latest('tanggal')->paginate(10)->withQueryString();

        return view('purchase-orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('nama')->get();
        $bahanBaku = BahanBaku::where('is_active', true)->orderBy('nama_bahan')->get();
        $noPo = PoNumberGenerator::generate();

        return view('purchase-orders.create', compact('suppliers', 'bahanBaku', 'noPo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.bahan_baku_id' => 'required|exists:bahan_baku,id',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $po = PurchaseOrder::create([
                'no_po' => PoNumberGenerator::generate(),
                'supplier_id' => $request->supplier_id,
                'tanggal' => $request->tanggal,
                'status' => 'Draft',
                'catatan' => $request->catatan,
                'created_by' => auth()->id(),
                'total_harga' => 0,
            ]);

            $totalHarga = 0;
            foreach ($request->items as $item) {
                $po->items()->create([
                    'bahan_baku_id' => $item['bahan_baku_id'],
                    'qty' => $item['qty'],
                    'harga_satuan' => $item['harga_satuan'],
                ]);
                $totalHarga += $item['qty'] * $item['harga_satuan'];
            }

            $po->update(['total_harga' => $totalHarga]);
        });

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order berhasil dibuat.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'items.bahanBaku', 'invoice']);

        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'Draft') {
            return redirect()->route('purchase-orders.index')
                ->with('error', 'Hanya PO berstatus Draft yang bisa diedit.');
        }

        $purchaseOrder->load('items');
        $suppliers = Supplier::where('is_active', true)->orderBy('nama')->get();
        $bahanBaku = BahanBaku::where('is_active', true)->orderBy('nama_bahan')->get();

        return view('purchase-orders.edit', compact('purchaseOrder', 'suppliers', 'bahanBaku'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'Draft') {
            return redirect()->route('purchase-orders.index')
                ->with('error', 'Hanya PO berstatus Draft yang bisa diedit.');
        }

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.bahan_baku_id' => 'required|exists:bahan_baku,id',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $purchaseOrder) {
            $purchaseOrder->update([
                'supplier_id' => $request->supplier_id,
                'tanggal' => $request->tanggal,
                'catatan' => $request->catatan,
            ]);

            // Delete old items and recreate
            $purchaseOrder->items()->delete();

            $totalHarga = 0;
            foreach ($request->items as $item) {
                $purchaseOrder->items()->create([
                    'bahan_baku_id' => $item['bahan_baku_id'],
                    'qty' => $item['qty'],
                    'harga_satuan' => $item['harga_satuan'],
                ]);
                $totalHarga += $item['qty'] * $item['harga_satuan'];
            }

            $purchaseOrder->update(['total_harga' => $totalHarga]);
        });

        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase Order berhasil diperbarui.');
    }

    public function updateStatus(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'status' => 'required|in:Draft,Dikirim,Disetujui,Selesai',
        ]);

        $allowedTransitions = [
            'Draft' => ['Dikirim'],
            'Dikirim' => ['Disetujui', 'Draft'],
            'Disetujui' => ['Selesai', 'Dikirim'],
            'Selesai' => [],
        ];

        $currentStatus = $purchaseOrder->status;
        $newStatus = $request->status;

        if (!in_array($newStatus, $allowedTransitions[$currentStatus] ?? [])) {
            return redirect()->back()
                ->with('error', "Tidak bisa mengubah status dari {$currentStatus} ke {$newStatus}.");
        }

        $purchaseOrder->update(['status' => $newStatus]);

        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('success', "Status PO berhasil diubah menjadi {$newStatus}.");
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'Draft') {
            return redirect()->route('purchase-orders.index')
                ->with('error', 'Hanya PO berstatus Draft yang bisa dihapus.');
        }

        if ($purchaseOrder->invoice()->exists()) {
            return redirect()->route('purchase-orders.index')
                ->with('error', 'PO tidak bisa dihapus karena sudah memiliki Invoice terkait.');
        }

        try {
            $purchaseOrder->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") {
                return redirect()->route('purchase-orders.index')
                    ->with('error', 'PO tidak bisa dihapus karena masih terhubung dengan data lain.');
            }
            throw $e;
        }

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order berhasil dihapus.');
    }

    public function exportPdf(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'items.bahanBaku']);

        $pdf = Pdf::loadView('purchase-orders.pdf', compact('purchaseOrder'));

        return $pdf->download("PO-{$purchaseOrder->no_po}.pdf");
    }
}
