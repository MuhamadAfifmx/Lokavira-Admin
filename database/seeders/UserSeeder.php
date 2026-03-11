<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
  // database/seeders/UserSeeder.php

public function run(): void
{
    // 1. AKUN ADMIN
    User::create([
        'username'            => 'admin',
        'email'               => 'admin@lokavira.com',
        'password'            => Hash::make('admin123'),
        'is_admin'            => true,
        'business_name'       => 'LokaVira Digital',
        'representative_name' => 'Admin Utama', // Pengganti 'name'
        'phone_number'        => '08123456789',
        'subscribed_at'       => now(),
        'expires_at'          => now()->addYears(10),
    ]);

    // 2. AKUN USER
    User::create([
        'username'            => 'client01',
        'email'               => 'budi@brandlokal.com',
        'password'            => Hash::make('user123'),
        'is_admin'            => false,
        'business_name'       => 'Kopi Kenangan Mantan',
        'representative_name' => 'Budi Santoso', // Pengganti 'name'
        'phone_number'        => '08998877665',
        'subscribed_at'       => now(),
        'expires_at'          => now()->addMonth(),
    ]);
}
}