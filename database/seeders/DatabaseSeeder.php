<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => env('ADMIN_EMAIL', 'admin@giveaway.com'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'admin123')),
            'is_admin' => true,
        ]);

        echo "Admin user created!\n";
        echo "Email: " . env('ADMIN_EMAIL', 'admin@giveaway.com') . "\n";
        echo "Password: " . env('ADMIN_PASSWORD', 'admin123') . "\n";

        // Seed QR codes
        $this->call(QRCodeSeeder::class);
    }
}
