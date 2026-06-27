<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = App\Models\StokGudang::where('qty_aktual', '>', 0)->count();
echo "Stok > 0 : " . $count . "\n";
