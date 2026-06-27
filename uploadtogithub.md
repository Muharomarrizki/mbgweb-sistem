# Panduan Upload Project ke GitHub

Panduan ini akan menjelaskan cara mengunggah (upload) project `mbgweb` Anda ke dalam repository GitHub. 

## Persiapan Awal
1. Pastikan Anda sudah memiliki akun [GitHub](https://github.com/).
2. Pastikan program **Git** sudah terinstal di komputer Anda. Anda bisa mengeceknya dengan menjalankan `git --version` di terminal. Jika belum ada, download dan instal dari [git-scm.com](https://git-scm.com/).

## Langkah-Langkah Upload

### 1. Buat Repository Baru di GitHub
- Login ke akun GitHub Anda.
- Klik ikon **+** di pojok kanan atas dan pilih **New repository**.
- Isi **Repository name** (contoh: `mbgweb-sistem`).
- Biarkan pengaturan lain secara default (Pilih *Public* atau *Private* sesuai keinginan Anda).
- **PENTING:** Jangan centang opsi "Add a README file" karena project ini sudah memilikinya.
- Klik tombol **Create repository**.

### 2. Inisialisasi Git di Komputer Lokal
Buka Terminal / Command Prompt / PowerShell, lalu arahkan ke folder project Anda:
```bash
cd path/to/mbgweb
```

Jalankan perintah inisialisasi Git agar folder ini dikenali sebagai repository lokal:
```bash
git init
```

### 3. Tambahkan File ke dalam Staging Area
Aplikasi Laravel sudah dilengkapi dengan file `.gitignore` bawaan. File ini secara otomatis akan mencegah file-file berukuran besar (`vendor`, `node_modules`) dan file rahasia (`.env`) untuk ikut ter-upload.

Tambahkan seluruh file kode ke dalam staging area menggunakan perintah:
```bash
git add .
```

### 4. Buat Commit Pertama
Beri pesan commit untuk menandai titik simpan project saat ini:
```bash
git commit -m "Initial commit - Sistem Manajemen SPPG"
```

### 5. Hubungkan ke Repository GitHub
Salin URL dari repository yang baru saja Anda buat di GitHub (biasanya berformat `https://github.com/username-anda/mbgweb-sistem.git`).

Jalankan perintah berikut untuk menghubungkan folder lokal ke GitHub (ganti URL di bawah dengan URL repository Anda):
```bash
git remote add origin https://github.com/username-anda/mbgweb-sistem.git
```

### 6. Upload (Push) Kode ke GitHub
Ubah branch utama menjadi `main` (standar modern) dan unggah kode ke GitHub:
```bash
git branch -M main
git push -u origin main
```

*(Jika ini adalah pertama kalinya Anda menggunakan Git/GitHub di komputer tersebut, akan muncul pop-up atau prompt di terminal yang meminta Anda untuk login ke akun GitHub Anda).*

---

### Tips Penting Untuk Kedepannya!
- File **`.env` tidak akan dan tidak boleh di-upload** karena memuat informasi sensitif (password database, dll). GitHub hanya akan menyimpan salinannya yaitu `.env.example`.
- Setiap kali Anda selesai membuat perubahan (ngoding) pada file di masa mendatang dan ingin menyimpannya ke GitHub, Anda hanya perlu mengulang 3 langkah dasar ini di terminal:
  1. `git add .`
  2. `git commit -m "Deskripsi perubahan, misal: Fix error pada Purchase Order"`
  3. `git push`
