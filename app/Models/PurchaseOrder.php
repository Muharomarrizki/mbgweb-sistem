<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_po',
        'supplier_id',
        'tanggal',
        'status',
        'total_harga',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_harga' => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PoItem::class, 'po_id');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'po_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Recalculate total_harga from items
     */
    public function recalculateTotal(): void
    {
        $this->update([
            'total_harga' => $this->items()->sum(\DB::raw('qty * harga_satuan')),
        ]);
    }
}
