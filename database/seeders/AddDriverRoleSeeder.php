<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AddDriverRoleSeeder extends Seeder
{
    public function run()
    {
        // Pastikan role 'Driver' sudah ada
        $driverRole = Role::firstOrCreate(['name' => 'Driver']);

        // Cari user dengan email 'driverpanen@gmail.com'
        $user = User::where('email', 'driverpanen@gmail.com')->first();

        if ($user) {
            // Tambahkan role 'Driver' ke user
            $user->assignRole($driverRole);
            $this->command->info('Role Driver berhasil ditambahkan ke user ' . $user->name);
        } else {
            $this->command->error('User dengan email driverpanen@gmail.com tidak ditemukan');
        }
    }
}