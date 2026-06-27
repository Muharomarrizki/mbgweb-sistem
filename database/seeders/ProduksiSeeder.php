<?php

namespace Database\Seeders;

use App\Models\BahanBaku;
use App\Models\DistribusiMbg;
use App\Models\KartuStok;
use App\Models\ProduksiItem;
use App\Models\ProduksiMbg;
use App\Models\StokGudang;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProduksiSeeder extends Seeder
{
    public function run(): void
    {
        $kepalaDapur = User::first();
        $bahan = StokGudang::where('qty_aktual', '>', 5)->first();

        if (!$bahan) {
            echo "Tidak ada stok bahan baku yang cukup untuk disimulasikan.\n";
            return;
        }

        $bahanBakuId = $bahan->bahan_baku_id;
        $qtyGuna = 5;

        DB::transaction(function () use ($kepalaDapur, $bahan, $bahanBakuId, $qtyGuna) {
            $produksi = ProduksiMbg::create([
                'tanggal' => now()->toDateString(),
                'menu_nama' => 'Nasi Ayam Teriyaki (Sample)',
                'jumlah_porsi' => 500,
                'catatan' => 'Data sample untuk tampilan (Bisa Dihapus)',
                'created_by' => $kepalaDapur->id,
            ]);

            ProduksiItem::create([
                'produksi_id' => $produksi->id,
                'bahan_baku_id' => $bahanBakuId,
                'qty_digunakan' => $qtyGuna,
            ]);

            $bahan->qty_aktual -= $qtyGuna;
            $bahan->save();

            KartuStok::create([
                'bahan_baku_id' => $bahanBakuId,
                'jenis_transaksi' => 'Keluar',
                'qty' => $qtyGuna,
                'referensi_tipe' => 'Produksi',
                'referensi_id' => $produksi->id,
                'tanggal' => now()->toDateString(),
                'saldo_akhir' => $bahan->qty_aktual,
                'keterangan' => "Pemakaian bahan baku untuk produksi Menu: " . $produksi->menu_nama,
            ]);

            // Buat Distribusi
            DistribusiMbg::create([
                'produksi_id' => $produksi->id,
                'sekolah_tujuan' => 'SDN Sukasejati 01',
                'jumlah_porsi' => 250,
                'jam_kirim' => '10:30',
                'petugas_nama' => 'Budi Pengirim',
                'catatan' => 'Pengiriman sesi 1',
            ]);
            
            DistribusiMbg::create([
                'produksi_id' => $produksi->id,
                'sekolah_tujuan' => 'SDN Sukasejati 02',
                'jumlah_porsi' => 250,
                'jam_kirim' => '11:00',
                'petugas_nama' => 'Andi Pengirim',
                'catatan' => 'Pengiriman sesi 2',
            ]);
        });
        
        echo "Data Produksi & Distribusi sample berhasil dibuat.\n";
    }
}
