<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan RolePermissionSeeder terlebih dahulu
        $this->call(RolePermissionSeeder::class);
        
        // Jalankan CategoryProductSeeder untuk data sample
        $this->call(CategoryProductSeeder::class);
        
        // User::factory(10)->create();

        // Test user dengan role customer
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'whatsapp' => '+6281234567893',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        
        $testUser->assignRole('customer');
    }
}
