<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

class PoNumberGenerator
{
    /**
     * Generate auto-incremented PO number: PO-YYYYMM-XXXX
     */
    public static function generate(): string
    {
        $prefix = 'PO-' . now()->format('Ym') . '-';

        $lastPo = PurchaseOrder::where('no_po', 'like', $prefix . '%')
            ->orderBy('no_po', 'desc')
            ->first();

        if ($lastPo) {
            $lastNumber = (int) substr($lastPo->no_po, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
