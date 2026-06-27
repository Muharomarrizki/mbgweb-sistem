# Panduan Menjalankan Sistem Manajemen SPPG Sukasejati 2

Sistem ini dibangun menggunakan framework **Laravel 12** dengan **PHP 8.2+**. Sistem Manajemen ini mengotomatisasi aliran data dari Perencanaan, Pengadaan (PO), Pergudangan, Produksi, Distribusi, hingga Akuntansi.

Untuk menjalankan program ini di komputer lokal (localhost), ikuti langkah-langkah berikut:

## Persyaratan Sistem
Sebelum memulai, pastikan perangkat Anda sudah terinstal perangkat lunak berikut:
- **PHP** (Minimal versi 8.2)
- **Composer** (Untuk mengelola package/library PHP)
- **Node.js & npm** (Untuk kompilasi asset frontend seperti Tailwind dan Alpine.js)
- **MySQL / MariaDB** (Untuk pengelola database)
- **Git** (Opsional, disarankan)

## Langkah-Langkah Menjalankan

### 1. Buka Terminal pada Folder Project
Buka terminal/Command Prompt/PowerShell dan arahkan ke direktori project:
```bash
cd path/to/mbgweb
```

### 2. Install Dependensi PHP (Composer)
Jalankan perintah berikut untuk mengunduh semua library PHP (termasuk Laravel, DOMPDF, Excel, dan Spatie Permission):
```bash
composer install
```

### 3. Install Dependensi Frontend (NPM)
Untuk mengunduh library frontend (Tailwind CSS, Alpine.js), jalankan:
```bash
npm install
```

### 4. Konfigurasi Environment (.env)
Project Laravel membutuhkan file `.env` sebagai konfigurasi lokal. Copy file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
*(Di Windows, Anda bisa menggunakan perintah `copy .env.example .env` atau lakukan copy-paste secara manual lewat File Explorer).*

Buka file `.env` yang baru dibuat di text editor Anda, lalu sesuaikan bagian koneksi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mbg_system  # Sesuaikan dengan nama database Anda
DB_USERNAME=root        # Sesuaikan dengan username (umumnya root)
DB_PASSWORD=            # Sesuaikan dengan password (umumnya kosong)
```

### 5. Generate Application Key
Jalankan perintah ini untuk membuat APP_KEY (kunci keamanan aplikasi):
```bash
php artisan key:generate
```

### 6. Siapkan Database & Jalankan Migrasi
1. Buka pengelola database Anda (seperti phpMyAdmin, DBeaver, atau TablePlus).
2. Buat database kosong baru dengan nama `mbg_system`.
3. Setelah database dibuat, jalankan perintah migrasi beserta seeder untuk membuat tabel-tabel dan mengisi data awal (seperti Role dan akun admin):
```bash
php artisan migrate:fresh --seed
```

### 7. Jalankan Server Lokal
Anda butuh menjalankan dua perintah secara bersamaan. Silakan buka **dua terminal terpisah**:

**Terminal 1 (Menjalankan server PHP Laravel):**
```bash
php artisan serve
```

**Terminal 2 (Menjalankan Vite build untuk frontend secara real-time):**
```bash
npm run dev
```

### 8. Akses Aplikasi
Buka web browser favorit Anda (Chrome, Edge, dll) dan kunjungi URL berikut:
**http://localhost:8000**

*Catatan: Sesuai dengan konfigurasi saat ini (development mode), aplikasi akan otomatis melakukan bypass login sehingga Anda akan langsung masuk sebagai Super Admin.*
