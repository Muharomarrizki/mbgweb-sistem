<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distribusi_mbg', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produksi_id')->constrained('produksi_mbg')->cascadeOnDelete();
            $table->string('sekolah_tujuan');
            $table->integer('jumlah_porsi');
            $table->time('jam_kirim')->nullable();
            $table->string('petugas_nama')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribusi_mbg');
    }
};
