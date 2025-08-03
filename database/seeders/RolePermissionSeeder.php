<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions untuk toko online digital
        $permissions = [
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Product Management
            'view products',
            'create products',
            'edit products',
            'delete products',
            'manage product categories',
            
            // Order Management
            'view orders',
            'create orders',
            'edit orders',
            'delete orders',
            'process orders',
            
            // Payment Management
            'view payments',
            'process payments',
            'refund payments',
            
            // Inventory Management
            'view inventory',
            'manage inventory',
            'stock alerts',
            
            // Customer Management
            'view customers',
            'edit customers',
            'customer support',
            
            // Reports & Analytics
            'view reports',
            'sales reports',
            'financial reports',
            
            // Settings
            'manage settings',
            'system settings',
            'store settings',
            
            // Content Management
            'manage content',
            'manage banners',
            'manage pages',
            
            // Discount & Promotions
            'manage discounts',
            'manage coupons',
            'manage promotions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Assign permissions to roles
        // Admin mendapat semua permissions
        $adminRole->givePermissionTo(Permission::all());

        // Manager mendapat permissions terbatas
        $managerRole->givePermissionTo([
            'view users', 'edit users',
            'view products', 'create products', 'edit products', 'manage product categories',
            'view orders', 'edit orders', 'process orders',
            'view payments', 'process payments',
            'view inventory', 'manage inventory', 'stock alerts',
            'view customers', 'edit customers', 'customer support',
            'view reports', 'sales reports',
            'manage content', 'manage banners',
            'manage discounts', 'manage coupons', 'manage promotions',
        ]);

        // Staff mendapat permissions dasar
        $staffRole->givePermissionTo([
            'view products', 'edit products',
            'view orders', 'process orders',
            'view inventory',
            'view customers', 'customer support',
            'manage content',
        ]);

        // Customer hanya bisa melihat produk dan mengelola order mereka sendiri
        $customerRole->givePermissionTo([
            'view products',
            'create orders',
        ]);

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'whatsapp' => '+6281234567890',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('admin');

        // Create manager user
        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Store Manager',
                'whatsapp' => '+6281234567891',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $manager->assignRole('manager');

        // Create staff user
        $staff = User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Store Staff',
                'whatsapp' => '+6281234567892',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $staff->assignRole('staff');
    }
}
