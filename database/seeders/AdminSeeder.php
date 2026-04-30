<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
            ['first_name' => 'Super',       'last_name' => 'Admin',       'email' => 'admin@imru.com'],
        ];

        foreach ($admins as $admin) {
            Admin::create([
                'first_name' => $admin['first_name'],
                'last_name'  => $admin['last_name'],
                'email'      => $admin['email'],
                'password'   => Hash::make('12345678'),
                'is_active'  => 1,
            ]);
        }
    }
}
