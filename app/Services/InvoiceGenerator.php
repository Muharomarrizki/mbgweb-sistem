<?php

namespace App\Services;

use App\Models\Invoice;

class InvoiceGenerator
{
    /**
     * Generate auto-incremented Invoice number: INV-YYYYMM-XXXX
     */
    public static function generateNumber(): string
    {
        $prefix = 'INV-' . now()->format('Ym') . '-';

        $lastInv = Invoice::where('no_invoice', 'like', $prefix . '%')
            ->orderBy('no_invoice', 'desc')
            ->first();

        if ($lastInv) {
            $lastNumber = (int) substr($lastInv->no_invoice, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
