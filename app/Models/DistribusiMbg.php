<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistribusiMbg extends Model
{
    use HasFactory;

    protected $table = 'distribusi_mbg';

    protected $fillable = [
        'produksi_id',
        'sekolah_tujuan',
        'jumlah_porsi',
        'jam_kirim',
        'petugas_nama',
        'catatan',
    ];

    public function produksi(): BelongsTo
    {
        return $this->belongsTo(ProduksiMbg::class, 'produksi_id');
    }
}
