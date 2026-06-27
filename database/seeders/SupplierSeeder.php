<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'nama' => 'PT Beras Nusantara',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta Pusat',
                'telepon' => '021-55501234',
                'npwp' => '01.234.567.8-901.000',
                'pic' => 'Budi Santoso',
            ],
            [
                'nama' => 'CV Ayam Segar Jaya',
                'alamat' => 'Jl. Industri No. 25, Tangerang',
                'telepon' => '021-55505678',
                'npwp' => '02.345.678.9-012.000',
                'pic' => 'Siti Aminah',
            ],
            [
                'nama' => 'UD Sayur Makmur',
                'alamat' => 'Jl. Pasar Baru No. 5, Bogor',
                'telepon' => '0251-8801234',
                'npwp' => '03.456.789.0-123.000',
                'pic' => 'Hendra Wijaya',
            ],
            [
                'nama' => 'PT Bumbu Rempah Indo',
                'alamat' => 'Jl. Raya Cibubur No. 88, Depok',
                'telepon' => '021-87654321',
                'npwp' => '04.567.890.1-234.000',
                'pic' => 'Dewi Lestari',
            ],
            [
                'nama' => 'CV Minyak Goreng Prima',
                'alamat' => 'Jl. Sudirman No. 100, Bandung',
                'telepon' => '022-12345678',
                'npwp' => '05.678.901.2-345.000',
                'pic' => 'Ahmad Fauzi',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(['nama' => $supplier['nama']], $supplier);
        }
    }
}
