<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->seedRoles();
        $this->seedUsers();
        $this->call(AddDriverRoleSeeder::class);
    }

    public function seedRoles() {
        $superAdmin = Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'User']);
        Role::create(['name' => 'Kurir']);
        $permissionUsers = Permission::create(['name' => 'Users']);
        $superAdmin->givePermissionTo($permissionUsers);
    }

    public function seedUsers() {
        $superAdmin = User::create([
            'name'          => 'Super Admin',
            'email'         => 'superadmin@express.admasolusi.space',
            'phonenumber'   => '6282244101305',
            'password'      => bcrypt('12345678'),
        ])->assignRole('Super Admin');
    }
    
}
