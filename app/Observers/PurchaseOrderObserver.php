<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Models\KartuStok;
use App\Models\PurchaseOrder;
use App\Models\StokGudang;
use App\Services\InvoiceGenerator;
use Illuminate\Support\Facades\DB;

class PurchaseOrderObserver
{
    /**
     * Handle the PurchaseOrder "updated" event.
     * When status changes to 'Selesai':
     * 1. Add stock to stok_gudang
     * 2. Log to kartu_stok
     * 3. Generate invoice
     * 4. Update bahan_baku harga_terakhir
     */
    public function updated(PurchaseOrder $purchaseOrder): void
    {
        // Only trigger when status changes to 'Selesai'
        if ($purchaseOrder->wasChanged('status') && $purchaseOrder->status === 'Selesai') {
            DB::transaction(function () use ($purchaseOrder) {
                $this->addStockFromPO($purchaseOrder);
                $this->generateInvoice($purchaseOrder);
                $this->updateHargaTerakhir($purchaseOrder);
            });
        }
    }

    /**
     * Add stock and create kartu_stok entries for each PO item
     */
    private function addStockFromPO(PurchaseOrder $po): void
    {
        foreach ($po->items as $item) {
            // Update or create stok_gudang
            $stok = StokGudang::firstOrCreate(
                ['bahan_baku_id' => $item->bahan_baku_id],
                ['qty_aktual' => 0]
            );

            $stok->qty_aktual += $item->qty;
            $stok->save();

            // Log to kartu_stok
            KartuStok::create([
                'bahan_baku_id' => $item->bahan_baku_id,
                'jenis_transaksi' => 'Masuk',
                'qty' => $item->qty,
                'referensi_tipe' => 'PO',
                'referensi_id' => $po->id,
                'tanggal' => now()->toDateString(),
                'saldo_akhir' => $stok->qty_aktual,
                'keterangan' => "Barang masuk dari PO #{$po->no_po}",
            ]);
        }
    }

    /**
     * Auto-generate draft invoice from completed PO
     */
    private function generateInvoice(PurchaseOrder $po): void
    {
        // Don't create duplicate invoice
        if ($po->invoice()->exists()) {
            return;
        }

        Invoice::create([
            'no_invoice' => InvoiceGenerator::generateNumber(),
            'po_id' => $po->id,
            'tanggal_issued' => now()->toDateString(),
            'jatuh_tempo' => now()->addDays(30)->toDateString(),
            'status_bayar' => 'Belum Dibayar',
            'total_tagihan' => $po->total_harga,
            'jumlah_dibayar' => 0,
        ]);
    }

    /**
     * Update harga_terakhir on bahan_baku from latest PO prices
     */
    private function updateHargaTerakhir(PurchaseOrder $po): void
    {
        foreach ($po->items as $item) {
            $item->bahanBaku->update([
                'harga_terakhir' => $item->harga_satuan,
            ]);
        }
    }
}
