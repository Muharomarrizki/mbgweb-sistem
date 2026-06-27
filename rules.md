# SYSTEM REQUIREMENT DOCUMENT (SRD) - SPPG / MBG MANAGEMENT SYSTEM

## 1. CONTEXT & GOAL
Kamu adalah Senior Software Architect dan Backend Engineer. Tugasmu adalah merancang dan mengimplementasikan Sistem Informasi Manajemen Satuan Pelayanan Pemenuhan Gizi (SPPG) / Makan Bergizi Gratis (MBG) berbasis Web Multi-User. Sistem ini harus mengotomatisasi aliran data dari Perencanaan, Pengadaan (PO), Pergudangan, Produksi, Distribusi, hingga Akuntansi (Invoice & Laporan Keuangan) secara REAL-TIME tanpa ada redundansi data.

## 2. SYSTEM ACTORS & PERMISSIONS (RBAC)
Sistem menggunakan Role-Based Access Control (RBAC) ketat:
- **Bendahara**: Akses penuh ke Dashboard, Keuangan, PO, Invoice, Pengeluaran, Master Supplier.
- **Admin Gudang**: Akses penuh ke Stok Gudang (Barang Masuk/Keluar), Kartu Stok, Master Bahan Baku. Read-only untuk PO (hanya untuk verifikasi barang masuk).
- **Kepala Dapur**: Akses penuh ke Menu Produksi MBG dan Penggunaan Bahan Baku harian.
- **Pengawas / Auditor**: Read-only ke seluruh sistem, terutama Laporan Keuangan dan Laporan MBG.

## 3. CORE BUSINESS LOGIC & DATA LIFECYCLE (ANTI-HALUSINASI REGULATION)
Agent WAJIB mengikuti alur dependensi data berikut (Dilarang memutus chain ini):
1. **RAB & PO Chain**: PO dibuat berdasarkan Master Supplier dan Master Bahan Baku. Status PO: `Draft` -> `Dikirim` -> `Disetujui` -> `Selesai`. Nomor PO otomatis menggunakan format `PO-YYYYMM-XXXX`.
2. **PO to Stock In (Gudang)**: Ketika status PO berubah menjadi `Selesai` (Barang diterima), sistem harus OTOMATIS:
   - Menambah kuantitas di tabel `Stok_Gudang` (Barang Masuk).
   - Mencatat log di `Kartu_Stok`.
3. **PO to Invoice Chain**: Setiap PO yang berstatus `Selesai` akan otomatis meng-generate draft `Invoice` dengan format `INV-YYYYMM-XXXX`. Nilai Invoice diambil dari (Qty PO * Harga PO). Status Invoice: `Belum Dibayar` -> `Sebagian` -> `Lunas`.
4. **Produksi to Stock Out Chain**: Ketika Kepala Dapur menginput Produksi MBG (Misal: 3.500 Porsi dengan menu tertentu), sistem harus menghitung konversi kebutuhan bahan baku (BOM - Bill of Materials) dan OTOMATIS mengurangi `Stok_Gudang` (Barang Keluar) serta mencatatnya di `Kartu_Stok`.
5. **Invoice & Pengeluaran to Financial Report**: Setiap Invoice yang diubah statusnya menjadi `Lunas` atau `Sebagian` oleh Bendahara, serta setiap input di `Pengeluaran Operasional`, harus otomatis masuk ke dalam kalkulasi Jurnal Arus Kas dan Laporan Laba Rugi.

## 4. DATABASE SCHEMA SPECIFICATION (RELATIONAL)
Buatlah skema database (disarankan PostgreSQL/MySQL) dengan relasi sebagai berikut:

- `users` (id, username, password_hash, role)
- `suppliers` (id, nama, alamat, telepon, npwp, pic)
- `bahan_baku` (id, nama_bahan, kategori [Beras/Ayam/dll], satuan [Kg/Butir/Pcs])
- `purchase_orders` (id, no_po [unique], supplier_id, tanggal, status [Enum], total_harga)
- `po_items` (id, po_id, bahan_baku_id, qty, harga_satuan)
- `invoices` (id, no_invoice [unique], po_id, tanggal_issued, jatuh_tempo, status_bayar [Enum], jumlah_dibayar)
- `stok_gudang` (id, bahan_baku_id, qty_aktual, lokasi_gudang)
- `kartu_stok` (id, bahan_baku_id, jenis_transaksi [Masuk/Keluar], qty, referensi_id [po_id atau produksi_id], tanggal, saldo_akhir)
- `pengeluaran_operasional` (id, kategori [Bahan Baku/Gas/Gaji/dll], nominal, tanggal, keterangan)
- `produksi_mbg` (id, tanggal, menu_nama, jumlah_porsi)
- `distribusi_mbg` (id, produksi_id, sekolah_tujuan, jumlah_porsi, jam_kirim, petugas_nama)

## 5. REKAPITULASI MENU & DASHBOARD LOGIC
- **Dashboard Formulas**:
  - `Sisa Anggaran` = Anggaran Bulan Ini (Hardcoded/Setting) - Total (Pengeluaran Operasional + Invoice Lunas/Sebagian).
  - `Nilai Stok` = SUM (qty_aktual * harga_pembelian_terakhir_dari_po).
- **Export Feature**: Menu PO, Invoice, dan semua Laporan Keuangan WAJIB memiliki fungsi cetak PDF / Export Excel dengan layout siap audit (clean & tabular).

## 6. EXPECTED OUTPUT
Generate backend logic (API Endpoints dengan clean architecture), frontend components yang responsif menggunakan framework modern (misalnya Next.js/React dengan Tailwind atau Vue), dan query migrasi database berdasarkan spesifikasi di atas. Mulai dari perancangan database dan API Endpoint untuk modul Master Data dan PO terlebih dahulu.