<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduksiItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function produksiMbg()
    {
        return $this->belongsTo(ProduksiMbg::class, 'produksi_id');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class);
    }
}
