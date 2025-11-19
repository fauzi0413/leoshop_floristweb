<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder produk
        $this->call(ProductSeeder::class);

        // 👑 Buat akun admin
        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('admin123'), // 🔒 password: admin123
        ]);

        // 🙋‍♀️ Buat akun user biasa
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'role' => 'user',
            'password' => bcrypt('user123'), // 🔒 password: user123
        ]);

    }
}
