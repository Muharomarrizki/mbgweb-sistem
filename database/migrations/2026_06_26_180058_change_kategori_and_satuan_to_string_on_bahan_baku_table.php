<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bahan_baku', function (Blueprint $table) {
            $table->string('kategori', 100)->change();
            $table->string('satuan', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Altering back to original ENUM is risky if there are strings outside the ENUM scope.
        // We will just leave them as strings or cast them back (which might cause errors if custom text exists).
        Schema::table('bahan_baku', function (Blueprint $table) {
            // $table->enum('kategori', ['Beras', 'Ayam', 'Daging', 'Ikan', 'Sayur', 'Bumbu', 'Minyak', 'Telur', 'Susu', 'Lainnya'])->change();
            // $table->enum('satuan', ['Kg', 'Gram', 'Liter', 'Ml', 'Butir', 'Pcs', 'Bungkus', 'Karton', 'Ikat', 'Keranjang'])->change();
        });
    }
};
