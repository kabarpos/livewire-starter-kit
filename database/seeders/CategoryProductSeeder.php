<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data first (disable foreign key checks)
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\Product::truncate();
        \App\Models\Category::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $categories = [
            [
                'name' => 'Software & Apps',
                'slug' => 'software-apps',
                'description' => 'Digital software applications and tools',
                'is_active' => true,
                'sort_order' => 1,
                'products' => [
                    [
                        'name' => 'Photo Editor Pro',
                        'description' => 'Professional photo editing software with advanced features for photographers and designers.',
                        'short_description' => 'Advanced photo editing tool',
                        'price' => 49.99,
                        'sale_price' => 39.99,
                        'is_digital' => true,
                        'is_featured' => true,
                        'download_link' => 'https://example.com/download/photo-editor-pro',
                    ],
                    [
                        'name' => 'Task Manager Plus',
                        'description' => 'Comprehensive task management application for teams and individuals.',
                        'short_description' => 'Organize your tasks efficiently',
                        'price' => 29.99,
                        'sale_price' => 24.99,
                        'is_digital' => true,
                    ],
                ]
            ],
            [
                'name' => 'E-books',
                'slug' => 'e-books',
                'description' => 'Digital books and publications',
                'is_active' => true,
                'sort_order' => 2,
                'products' => [
                    [
                        'name' => 'Web Development Guide',
                        'description' => 'Complete guide to modern web development covering HTML, CSS, JavaScript, and popular frameworks.',
                        'short_description' => 'Learn web development from scratch',
                        'price' => 19.99,
                        'sale_price' => 15.99,
                        'is_digital' => true,
                        'is_featured' => true,
                        'download_link' => 'https://example.com/download/web-dev-guide',
                    ],
                ]
            ],
            [
                'name' => 'Online Courses',
                'slug' => 'online-courses',
                'description' => 'Educational courses and training materials',
                'is_active' => true,
                'sort_order' => 3,
                'products' => [
                    [
                        'name' => 'Laravel Mastery Course',
                        'description' => 'Complete Laravel framework course from beginner to advanced level with real-world projects.',
                        'short_description' => 'Master Laravel development',
                        'price' => 99.99,
                        'sale_price' => 79.99,
                        'is_digital' => true,
                        'is_featured' => true,
                        'download_link' => 'https://example.com/course/laravel-mastery',
                    ],
                ]
            ],
            [
                'name' => 'Digital Art & Design',
                'slug' => 'digital-art-design',
                'description' => 'Digital artwork, templates, and design assets',
                'is_active' => true,
                'sort_order' => 4,
                'products' => [
                    [
                        'name' => 'UI Design Templates Pack',
                        'description' => 'Collection of modern UI design templates for web and mobile applications.',
                        'short_description' => 'Professional design templates',
                        'price' => 39.99,
                        'sale_price' => 29.99,
                        'is_digital' => true,
                    ],
                ]
            ],
            [
                'name' => 'Music & Audio',
                'slug' => 'music-audio',
                'description' => 'Digital music, sound effects, and audio content',
                'is_active' => true,
                'sort_order' => 5,
                'products' => [
                    [
                        'name' => 'Royalty-Free Music Collection',
                        'description' => 'High-quality royalty-free music tracks for commercial and personal use.',
                        'short_description' => 'Commercial music collection',
                        'price' => 59.99,
                        'sale_price' => 49.99,
                        'is_digital' => true,
                    ],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $products = $categoryData['products'];
            unset($categoryData['products']);
            
            $category = Category::create($categoryData);

            foreach ($products as $productData) {
                $productData['category_id'] = $category->id;
                $productData['slug'] = Str::slug($productData['name']);
                $productData['sku'] = 'SKU-' . strtoupper(Str::random(8));
                $productData['stock_quantity'] = 999; // Digital products have unlimited stock
                $productData['manage_stock'] = false; // Digital products don't need stock management
                $productData['in_stock'] = true;
                $productData['is_active'] = true;
                $productData['images'] = ['placeholder.jpg']; // Placeholder image
                $productData['featured_image'] = 'placeholder.jpg';
                $productData['attributes'] = ['type' => 'digital']; // Product attributes

                Product::create($productData);
            }
        }
    }
}
