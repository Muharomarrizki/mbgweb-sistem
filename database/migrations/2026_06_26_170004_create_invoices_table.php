<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice', 20)->unique();
            $table->foreignId('po_id')->constrained('purchase_orders')->restrictOnDelete();
            $table->date('tanggal_issued');
            $table->date('jatuh_tempo');
            $table->enum('status_bayar', ['Belum Dibayar', 'Sebagian', 'Lunas'])->default('Belum Dibayar');
            $table->decimal('total_tagihan', 15, 2)->default(0);
            $table->decimal('jumlah_dibayar', 15, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
