<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$user = App\Models\User::first();
echo "Roles: " . implode(',', $user->roles->pluck('name')->toArray()) . "\n";
