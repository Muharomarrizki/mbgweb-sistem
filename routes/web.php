<?php

use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master Data - Supplier (Bendahara only)
    Route::middleware('role:bendahara')->group(function () {
        Route::resource('suppliers', SupplierController::class)->except(['show']);
    });

    // Master Data - Bahan Baku (Admin Gudang only)
    Route::middleware('role:admin_gudang,bendahara')->group(function () {
        Route::resource('bahan-baku', BahanBakuController::class)->except(['show']);
    });

    // Purchase Orders (Bendahara full, Admin Gudang read-only)
    Route::middleware('role:bendahara')->group(function () {
        Route::get('purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
        Route::post('purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
        Route::get('purchase-orders/{purchaseOrder}/edit', [PurchaseOrderController::class, 'edit'])->name('purchase-orders.edit');
        Route::put('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('purchase-orders.update');
        Route::patch('purchase-orders/{purchaseOrder}/status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase-orders.update-status');
        Route::delete('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy');
    });

    Route::middleware('role:bendahara,admin_gudang,pengawas')->group(function () {
        Route::get('purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
        Route::get('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
        Route::get('purchase-orders/{purchaseOrder}/pdf', [PurchaseOrderController::class, 'exportPdf'])->name('purchase-orders.pdf');
    });

    // Gudang - Stok & Kartu Stok (Admin Gudang, Bendahara, Pengawas, Kepala Dapur)
    Route::middleware('role:admin_gudang,bendahara,pengawas,kepala_dapur')->group(function () {
        Route::get('stok-gudang', [\App\Http\Controllers\StokGudangController::class, 'index'])->name('stok-gudang.index');
        Route::get('kartu-stok', [\App\Http\Controllers\KartuStokController::class, 'index'])->name('kartu-stok.index');
        Route::get('kartu-stok/{kartu_stok}', [\App\Http\Controllers\KartuStokController::class, 'show'])->name('kartu-stok.show');
    });

    // Gudang - Penyesuaian Stok (Admin Gudang only)
    Route::middleware('role:admin_gudang')->group(function () {
        Route::post('stok-gudang/penyesuaian', [\App\Http\Controllers\StokGudangController::class, 'penyesuaian'])->name('stok-gudang.penyesuaian');
    });

    // Keuangan - Invoices (Bendahara & Pengawas)
    Route::middleware('role:bendahara,pengawas')->group(function () {
        Route::get('invoices', [\App\Http\Controllers\InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('invoices/{invoice}/pdf', [\App\Http\Controllers\InvoiceController::class, 'exportPdf'])->name('invoices.pdf');
    });
    
    // Keuangan - Bayar Invoice (Bendahara only)
    Route::middleware('role:bendahara')->group(function () {
        Route::patch('invoices/{invoice}/payment', [\App\Http\Controllers\InvoiceController::class, 'updatePayment'])->name('invoices.update-payment');
    });

    // Produksi MBG (Kepala Dapur full, others read)
    Route::middleware('role:kepala_dapur,bendahara,pengawas')->group(function () {
        Route::get('produksi', [\App\Http\Controllers\ProduksiController::class, 'index'])->name('produksi.index');
        Route::get('produksi/{produksi}', [\App\Http\Controllers\ProduksiController::class, 'show'])->name('produksi.show');
    });

    Route::middleware('role:kepala_dapur')->group(function () {
        Route::get('produksi-create', [\App\Http\Controllers\ProduksiController::class, 'create'])->name('produksi.create');
        Route::post('produksi', [\App\Http\Controllers\ProduksiController::class, 'store'])->name('produksi.store');
    });

    // Distribusi MBG (Kepala Dapur full, others read)
    Route::middleware('role:kepala_dapur,bendahara,pengawas')->group(function () {
        Route::get('distribusi', [\App\Http\Controllers\DistribusiController::class, 'index'])->name('distribusi.index');
    });

    Route::middleware('role:kepala_dapur')->group(function () {
        Route::post('distribusi', [\App\Http\Controllers\DistribusiController::class, 'store'])->name('distribusi.store');
        Route::delete('distribusi/{distribusi}', [\App\Http\Controllers\DistribusiController::class, 'destroy'])->name('distribusi.destroy');
    });
    // Keuangan - Pengeluaran Operasional (Bendahara only)
    Route::middleware('role:bendahara')->group(function () {
        Route::get('pengeluaran', [\App\Http\Controllers\PengeluaranController::class, 'index'])->name('pengeluaran.index');
        Route::post('pengeluaran', [\App\Http\Controllers\PengeluaranController::class, 'store'])->name('pengeluaran.store');
        Route::delete('pengeluaran/{pengeluaran}', [\App\Http\Controllers\PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
    });

    // Keuangan - Laporan Keuangan (Bendahara & Pengawas)
    Route::middleware('role:bendahara,pengawas')->group(function () {
        Route::get('laporan-keuangan', [\App\Http\Controllers\LaporanKeuanganController::class, 'index'])->name('laporan-keuangan.index');
        Route::get('laporan-keuangan/pdf', [\App\Http\Controllers\LaporanKeuanganController::class, 'exportPdf'])->name('laporan-keuangan.pdf');
    });

    // Pengaturan Sistem (Bendahara only)
    Route::middleware('role:bendahara')->group(function () {
        Route::get('settings', [\App\Http\Controllers\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    });
});

require __DIR__.'/auth.php';
