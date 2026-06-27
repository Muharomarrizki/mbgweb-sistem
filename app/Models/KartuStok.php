<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KartuStok extends Model
{
    use HasFactory;

    protected $table = 'kartu_stok';

    protected $fillable = [
        'bahan_baku_id',
        'jenis_transaksi',
        'qty',
        'referensi_tipe',
        'referensi_id',
        'tanggal',
        'saldo_akhir',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'qty' => 'decimal:2',
        'saldo_akhir' => 'decimal:2',
    ];

    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }
}
