# 🍱 Sistem Manajemen SPPG Sukasejati 2
### Badan Gizi Nasional — Web-Based Management System

> Sistem Informasi Manajemen berbasis web multi-user untuk mengotomatisasi seluruh aliran operasional **Satuan Pelayanan Pemenuhan Gizi (SPPG)** Sukasejati 2 — dari Perencanaan, Pengadaan, Pergudangan, Produksi, Distribusi, hingga Laporan Keuangan secara **real-time**.

---

## 📋 Daftar Isi

- [Tentang Proyek](#tentang-proyek)
- [Fitur Utama](#fitur-utama)
- [Tech Stack](#tech-stack)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi & Menjalankan](#instalasi--menjalankan)
- [Akses & Role Pengguna](#akses--role-pengguna)
- [Alur Bisnis Sistem](#alur-bisnis-sistem)
- [Struktur Database](#struktur-database)
- [Struktur Proyek](#struktur-proyek)

---

## 🧩 Tentang Proyek

Proyek ini adalah sistem manajemen internal yang dibangun khusus untuk kebutuhan operasional program **Makan Bergizi Gratis (MBG)** yang dikelola oleh Badan Gizi Nasional. Sistem ini mengelola seluruh rantai proses dari pengadaan bahan baku hingga distribusi makanan ke sekolah-sekolah tujuan.

**Masalah yang diselesaikan:**
- ✅ Eliminasi pencatatan manual yang rentan kesalahan
- ✅ Sinkronisasi data real-time antar divisi (Keuangan, Gudang, Dapur, Pengawas)
- ✅ Otomasi pembuatan Invoice dari Purchase Order yang selesai
- ✅ Otomasi pengurangan stok gudang saat produksi berlangsung
- ✅ Laporan Keuangan dan Arus Kas yang siap audit

---

## ✨ Fitur Utama

| Modul | Deskripsi |
|---|---|
| 📊 **Dashboard** | Ringkasan sisa anggaran, nilai stok, dan statistik operasional harian |
| 🛒 **Purchase Order (PO)** | Manajemen PO dengan alur status: `Draft → Dikirim → Disetujui → Selesai`. Nomor PO otomatis format `PO-YYYYMM-XXXX` |
| 🏭 **Manajemen Stok Gudang** | Pencatatan barang masuk/keluar secara otomatis dari PO dan Produksi |
| 📦 **Kartu Stok** | Log transaksi stok yang lengkap dan dapat ditelusuri |
| 🍳 **Produksi MBG** | Input produksi harian dengan kalkulasi otomatis kebutuhan bahan baku (BOM) |
| 🚚 **Distribusi MBG** | Pencatatan distribusi makanan ke sekolah-sekolah tujuan |
| 🧾 **Invoice** | Pembuatan Invoice otomatis dari PO selesai. Format `INV-YYYYMM-XXXX`. Status: `Belum Dibayar → Sebagian → Lunas` |
| 💰 **Pengeluaran Operasional** | Pencatatan pengeluaran di luar pengadaan bahan baku |
| 📈 **Laporan Keuangan** | Laporan Arus Kas dan Laba Rugi yang terupdate secara real-time |
| 📄 **Export PDF & Excel** | Semua laporan dapat diekspor ke PDF maupun Excel siap audit |
| 👥 **Manajemen Supplier** | Data master supplier beserta NPWP, kontak, dan PIC |
| 🌾 **Master Bahan Baku** | Data master semua bahan baku beserta satuan dan kategorinya |
| ⚙️ **Pengaturan Sistem** | Konfigurasi anggaran bulanan dan parameter sistem |

---

## 🛠️ Tech Stack

| Kategori | Teknologi | Versi |
|---|---|---|
| **Framework Backend** | Laravel | 12.x |
| **Bahasa Pemrograman** | PHP | ≥ 8.2 |
| **Database** | MySQL / MariaDB | — |
| **Frontend Templating** | Blade (Laravel) | — |
| **CSS Framework** | Tailwind CSS | 3.x |
| **JavaScript** | Alpine.js | — |
| **Autentikasi** | Laravel Breeze | 2.x |
| **RBAC (Hak Akses)** | Spatie Laravel Permission | 6.x |
| **Export PDF** | barryvdh/laravel-dompdf | 3.x |
| **Export Excel** | Maatwebsite Excel | 1.x |
| **Build Tool** | Vite | — |

---

## ⚙️ Persyaratan Sistem

Sebelum memulai, pastikan perangkat lunak berikut sudah terinstal di komputer Anda:

| Perangkat Lunak | Versi Minimum | Keterangan |
|---|---|---|
| **PHP** | 8.2 | [Download PHP](https://www.php.net/downloads.php) |
| **Composer** | Terbaru | [Download Composer](https://getcomposer.org/download/) |
| **Node.js & npm** | 18.x | [Download Node.js](https://nodejs.org/) |
| **MySQL / MariaDB** | 8.0 / 10.6 | Atau gunakan XAMPP/Laragon |
| **Git** | Terbaru | [Download Git](https://git-scm.com/) |

> **💡 Tips:** Jika Anda menggunakan Windows, disarankan menggunakan **[Laragon](https://laragon.org/)** karena sudah menyertakan PHP, MySQL, dan Composer dalam satu paket yang mudah dikonfigurasi.

---

## 🚀 Instalasi & Menjalankan

Ikuti langkah-langkah berikut secara berurutan:

### Langkah 1 — Clone Repository

```bash
git clone https://github.com/Muharomarrizki/mbgweb-sistem.git
cd mbgweb-sistem
```

### Langkah 2 — Install Dependensi PHP

```bash
composer install
```

> Perintah ini mengunduh semua library PHP yang dibutuhkan (Laravel, DomPDF, Spatie, dll).

### Langkah 3 — Install Dependensi Frontend

```bash
npm install
```

> Perintah ini mengunduh library frontend (Tailwind CSS, Alpine.js, Vite).

### Langkah 4 — Konfigurasi Environment

Buat file `.env` dari template yang tersedia:

```bash
# Linux / macOS
cp .env.example .env

# Windows (Command Prompt)
copy .env.example .env
```

Kemudian buka file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mbg_system    # Nama database yang akan dibuat
DB_USERNAME=root           # Username MySQL Anda (default: root)
DB_PASSWORD=               # Password MySQL Anda (default: kosong)
```

### Langkah 5 — Generate Application Key

```bash
php artisan key:generate
```

> Perintah ini membuat kunci keamanan unik untuk enkripsi aplikasi Anda.

### Langkah 6 — Siapkan Database

1. Buka pengelola database Anda (phpMyAdmin, DBeaver, TablePlus, atau Laragon)
2. Buat database kosong baru bernama `mbg_system`
3. Jalankan migrasi dan seeder untuk membuat semua tabel beserta data awal:

```bash
php artisan migrate:fresh --seed
```

> Perintah ini akan membuat semua tabel dan mengisi data awal seperti Role (`Bendahara`, `Admin Gudang`, `Kepala Dapur`, `Pengawas`) serta akun Super Admin.

### Langkah 7 — Jalankan Aplikasi

Buka **dua terminal terpisah** dan jalankan masing-masing perintah:

**Terminal 1 — Server PHP (Backend):**
```bash
php artisan serve
```

**Terminal 2 — Vite (Frontend Asset):**
```bash
npm run dev
```

> **Shortcut:** Anda bisa juga menjalankan keduanya sekaligus dengan satu perintah:
> ```bash
> composer run dev
> ```

### Langkah 8 — Buka di Browser

Akses aplikasi di browser favorit Anda:

```
http://localhost:8000
```

---

## 👥 Akses & Role Pengguna

Sistem menggunakan **Role-Based Access Control (RBAC)** yang ketat. Setiap pengguna hanya dapat mengakses modul yang sesuai dengan perannya.

| Role | Akses Modul |
|---|---|
| 🔐 **Super Admin** | Akses penuh ke seluruh modul dan pengaturan sistem |
| 💼 **Bendahara** | Dashboard, Keuangan, Purchase Order, Invoice, Pengeluaran, Master Supplier |
| 📦 **Admin Gudang** | Stok Gudang (Masuk/Keluar), Kartu Stok, Master Bahan Baku, PO (read-only untuk verifikasi) |
| 🍳 **Kepala Dapur** | Produksi MBG dan pencatatan penggunaan bahan baku harian |
| 👁️ **Pengawas / Auditor** | Read-only ke seluruh sistem, terutama Laporan Keuangan dan Laporan MBG |

> **📌 Mode Development:** Saat ini sistem dikonfigurasi untuk **bypass login otomatis**. Setiap kali mengakses, Anda akan otomatis masuk sebagai **Super Admin**. Fitur ini dibuat untuk kemudahan pengembangan dan testing. Sebelum deploy ke production, pastikan fitur bypass ini dinonaktifkan.

---

## 🔄 Alur Bisnis Sistem

Sistem mengikuti alur data yang saling terhubung dan otomatis:

```
[Master Data: Supplier + Bahan Baku]
          │
          ▼
   [Purchase Order (PO)]
   Status: Draft → Dikirim → Disetujui → Selesai
          │
     ┌────┴────┐
     ▼         ▼
[Stok Gudang]  [Invoice Otomatis]
[Kartu Stok]   Status: Belum Dibayar → Sebagian → Lunas
     │                   │
     ▼                   ▼
[Produksi MBG]    [Laporan Keuangan]
(BOM Auto-calc)   (Arus Kas + Laba Rugi)
     │
     ▼
[Distribusi MBG ke Sekolah]
```

**Penjelasan Otomasi:**
1. **PO Selesai** → Stok gudang otomatis bertambah + log di Kartu Stok
2. **PO Selesai** → Invoice draft otomatis dibuat
3. **Produksi diinput** → Stok gudang otomatis berkurang (BOM calculation) + log di Kartu Stok
4. **Invoice Lunas/Sebagian** → Otomatis masuk ke perhitungan Arus Kas dan Laporan Keuangan

---

## 🗄️ Struktur Database

| Tabel | Deskripsi |
|---|---|
| `users` | Data pengguna dan role akses |
| `suppliers` | Master data supplier (nama, alamat, NPWP, PIC) |
| `bahan_baku` | Master bahan baku (nama, kategori, satuan) |
| `purchase_orders` | Transaksi PO dengan nomor unik `PO-YYYYMM-XXXX` |
| `po_items` | Detail item pada setiap PO |
| `invoices` | Invoice dengan nomor unik `INV-YYYYMM-XXXX` |
| `stok_gudang` | Saldo stok aktual setiap bahan baku |
| `kartu_stok` | Log setiap mutasi stok (masuk/keluar) |
| `pengeluaran_operasional` | Pengeluaran di luar pengadaan (gas, gaji, dll) |
| `produksi_mbg` | Rekap produksi harian (menu dan jumlah porsi) |
| `distribusi_mbg` | Data distribusi ke sekolah tujuan |

---

## 📁 Struktur Proyek

```
mbgweb/
├── app/
│   ├── Http/
│   │   └── Controllers/       # Semua controller aplikasi
│   │       ├── DashboardController.php
│   │       ├── PurchaseOrderController.php
│   │       ├── InvoiceController.php
│   │       ├── StokGudangController.php
│   │       ├── ProduksiController.php
│   │       ├── DistribusiController.php
│   │       ├── LaporanKeuanganController.php
│   │       └── ...
│   ├── Models/                # Eloquent Models
│   └── Services/              # Business logic & kalkulasi
├── database/
│   ├── migrations/            # Skema tabel database
│   └── seeders/               # Data awal (roles, admin)
├── resources/
│   └── views/                 # Template Blade (frontend)
├── routes/
│   └── web.php                # Definisi semua route
├── .env.example               # Template konfigurasi environment
├── composer.json              # Dependensi PHP
└── package.json               # Dependensi frontend
```

---

## 📄 Lisensi

Proyek ini dikembangkan sebagai sistem internal untuk keperluan program **Makan Bergizi Gratis (MBG)** — Badan Gizi Nasional.

---

<div align="center">
  <sub>Dibuat dengan ❤️ untuk Badan Gizi Nasional — SPPG Sukasejati 2</sub>
</div>
