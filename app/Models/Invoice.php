<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_invoice',
        'po_id',
        'tanggal_issued',
        'jatuh_tempo',
        'status_bayar',
        'total_tagihan',
        'jumlah_dibayar',
        'catatan',
    ];

    protected $casts = [
        'tanggal_issued' => 'date',
        'jatuh_tempo' => 'date',
        'total_tagihan' => 'decimal:2',
        'jumlah_dibayar' => 'decimal:2',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }
}
