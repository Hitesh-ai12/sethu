<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'hiteshpandey732195@gmail.com'], // ✅ Agar already hai toh dubara na ho
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
                'mobile_number' => '9999999999',
            ]
        );
    }
}
