<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kartu_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku')->restrictOnDelete();
            $table->enum('jenis_transaksi', ['Masuk', 'Keluar']);
            $table->decimal('qty', 12, 2);
            $table->string('referensi_tipe')->nullable(); // 'PO' or 'Produksi'
            $table->unsignedBigInteger('referensi_id')->nullable();
            $table->date('tanggal');
            $table->decimal('saldo_akhir', 12, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['bahan_baku_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kartu_stok');
    }
};
