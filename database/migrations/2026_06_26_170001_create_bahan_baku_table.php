<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan');
            $table->enum('kategori', ['Beras', 'Ayam', 'Daging', 'Ikan', 'Sayur', 'Bumbu', 'Minyak', 'Telur', 'Susu', 'Lainnya']);
            $table->enum('satuan', ['Kg', 'Gram', 'Liter', 'Ml', 'Butir', 'Pcs', 'Bungkus', 'Karton', 'Ikat','Keranjang']);
            $table->decimal('harga_terakhir', 15, 2)->default(0);
            $table->integer('stok_minimum')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahan_baku');
    }
};
