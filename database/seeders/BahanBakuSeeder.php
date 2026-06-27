<?php

namespace Database\Seeders;

use App\Models\BahanBaku;
use Illuminate\Database\Seeder;

class BahanBakuSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['nama_bahan' => 'Beras Premium', 'kategori' => 'Beras', 'satuan' => 'Kg', 'harga_terakhir' => 14000, 'stok_minimum' => 100],
            ['nama_bahan' => 'Ayam Potong Segar', 'kategori' => 'Ayam', 'satuan' => 'Kg', 'harga_terakhir' => 38000, 'stok_minimum' => 50],
            ['nama_bahan' => 'Daging Sapi', 'kategori' => 'Daging', 'satuan' => 'Kg', 'harga_terakhir' => 130000, 'stok_minimum' => 20],
            ['nama_bahan' => 'Telur Ayam', 'kategori' => 'Telur', 'satuan' => 'Butir', 'harga_terakhir' => 2500, 'stok_minimum' => 500],
            ['nama_bahan' => 'Minyak Goreng', 'kategori' => 'Minyak', 'satuan' => 'Liter', 'harga_terakhir' => 18000, 'stok_minimum' => 30],
            ['nama_bahan' => 'Bawang Merah', 'kategori' => 'Bumbu', 'satuan' => 'Kg', 'harga_terakhir' => 45000, 'stok_minimum' => 10],
            ['nama_bahan' => 'Bawang Putih', 'kategori' => 'Bumbu', 'satuan' => 'Kg', 'harga_terakhir' => 35000, 'stok_minimum' => 10],
            ['nama_bahan' => 'Cabai Merah', 'kategori' => 'Sayur', 'satuan' => 'Kg', 'harga_terakhir' => 50000, 'stok_minimum' => 5],
            ['nama_bahan' => 'Wortel', 'kategori' => 'Sayur', 'satuan' => 'Kg', 'harga_terakhir' => 12000, 'stok_minimum' => 15],
            ['nama_bahan' => 'Kentang', 'kategori' => 'Sayur', 'satuan' => 'Kg', 'harga_terakhir' => 15000, 'stok_minimum' => 20],
            ['nama_bahan' => 'Susu UHT', 'kategori' => 'Susu', 'satuan' => 'Karton', 'harga_terakhir' => 55000, 'stok_minimum' => 10],
            ['nama_bahan' => 'Gula Pasir', 'kategori' => 'Lainnya', 'satuan' => 'Kg', 'harga_terakhir' => 16000, 'stok_minimum' => 25],
        ];

        foreach ($items as $item) {
            BahanBaku::firstOrCreate(['nama_bahan' => $item['nama_bahan']], $item);
        }
    }
}
