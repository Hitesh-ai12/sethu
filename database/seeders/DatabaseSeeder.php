<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        User::factory()->create([
            'business_name' => 'Test Business',
            'email' => 'hiteshpandey732195@gmail.com',
            'phone' => '1234567890',
            'password' => Hash::make('Password@123'),
        ]);
    }
}
