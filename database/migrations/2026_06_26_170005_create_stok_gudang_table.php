<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_gudang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_baku_id')->unique()->constrained('bahan_baku')->restrictOnDelete();
            $table->decimal('qty_aktual', 12, 2)->default(0);
            $table->string('lokasi_gudang')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_gudang');
    }
};
