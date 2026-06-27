<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran_operasional', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', ['Bahan Baku', 'Gas', 'Gaji', 'Transportasi', 'Peralatan', 'Listrik', 'Air', 'Lainnya']);
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_operasional');
    }
};
