<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Bendahara Demo',
                'email' => 'bendahara@mbg.test',
                'password' => Hash::make('password'),
                'role' => 'bendahara',
            ],
            [
                'name' => 'Admin Gudang Demo',
                'email' => 'gudang@mbg.test',
                'password' => Hash::make('password'),
                'role' => 'admin_gudang',
            ],
            [
                'name' => 'Kepala Dapur Demo',
                'email' => 'dapur@mbg.test',
                'password' => Hash::make('password'),
                'role' => 'kepala_dapur',
            ],
            [
                'name' => 'Pengawas Demo',
                'email' => 'pengawas@mbg.test',
                'password' => Hash::make('password'),
                'role' => 'pengawas',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            $user->assignRole($role);
        }
    }
}
