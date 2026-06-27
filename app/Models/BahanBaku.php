<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_baku';

    protected $fillable = [
        'nama_bahan',
        'kategori',
        'satuan',
        'harga_terakhir',
        'stok_minimum',
        'is_active',
    ];

    protected $casts = [
        'harga_terakhir' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function poItems(): HasMany
    {
        return $this->hasMany(PoItem::class, 'bahan_baku_id');
    }

    public function stokGudang(): HasOne
    {
        return $this->hasOne(StokGudang::class, 'bahan_baku_id');
    }

    public function kartuStok(): HasMany
    {
        return $this->hasMany(KartuStok::class, 'bahan_baku_id');
    }
}
