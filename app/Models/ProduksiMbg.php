<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProduksiMbg extends Model
{
    use HasFactory;

    protected $table = 'produksi_mbg';

    protected $fillable = [
        'tanggal',
        'menu_nama',
        'jumlah_porsi',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function distribusi(): HasMany
    {
        return $this->hasMany(DistribusiMbg::class, 'produksi_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProduksiItem::class, 'produksi_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
