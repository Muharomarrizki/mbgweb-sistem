# Sistem Manajemen SPPG Sukasejati 2 - Badan Gizi Nasional

Sistem Manajemen untuk operasional **Badan Gizi Nasional** di Satuan Pelayanan Pemenuhan Gizi (SPPG) Sukasejati 2 berbasis Web Multi-User. Sistem ini mengotomatisasi aliran data dari Perencanaan, Pengadaan (PO), Pergudangan, Produksi, Distribusi, hingga Akuntansi secara real-time.

## Akses Sistem (Development Mode)

Sistem ini saat ini telah dikonfigurasi untuk **bypass login otomatis**. 
Setiap kali Anda mengakses web, Anda akan secara otomatis login sebagai **Super Admin** yang memiliki hak akses (Role) penuh ke seluruh modul sistem (Bendahara, Admin Gudang, Kepala Dapur, dan Pengawas). 

Anda tidak perlu memasukkan email atau password lagi.

## Cara Menjalankan Project

1. Pastikan dependensi PHP dan Node.js sudah terinstal.
2. Jalankan server PHP:
   ```bash
   php artisan serve
   ```
3. (Opsional) Jalankan Vite untuk asset frontend:
   ```bash
   npm run dev
   ```
4. Buka browser dan akses URL: `http://localhost:8000`

## Tech Stack
- **Framework:** Laravel 11
- **Database:** MySQL
- **Frontend:** Blade, Tailwind CSS, Alpine.js
- **PDF Export:** DomPDF
- **RBAC:** Spatie Laravel Permission
