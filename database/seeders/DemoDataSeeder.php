<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding demo data...');

        // Create Tables
        $tables = [
            ['table_number' => '1'],
            ['table_number' => '2'],
            ['table_number' => '3'],
            ['table_number' => '4'],
            ['table_number' => '5'],
        ];

        foreach ($tables as $tableData) {
            Table::create($tableData);
        }
        $this->command->info('✓ Created ' . count($tables) . ' tables with QR codes');

        // Create Categories
        $categories = [
            'Main Course',
            'Noodles & Rice',
            'Snacks',
            'Beverages',
            'Coffee & Tea',
            'Desserts'
        ];

        foreach ($categories as $categoryName) {
            Category::create(['name' => $categoryName]);
        }
        $this->command->info('✓ Created ' . count($categories) . ' categories');

        // Create Menus
        $menus = [
            // Main Course
            ['category_id' => 1, 'name' => 'Nasi Goreng Spesial', 'description' => 'Fried rice with chicken, egg, and vegetables', 'price' => 25000, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Ayam Geprek', 'description' => 'Crispy chicken with sambal', 'price' => 22000, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Ayam Bakar', 'description' => 'Grilled chicken with special sauce', 'price' => 28000, 'is_available' => true],
            
            // Noodles & Rice
            ['category_id' => 2, 'name' => 'Mie Ayam Bakso', 'description' => 'Chicken noodles with meatballs', 'price' => 20000, 'is_available' => true],
            ['category_id' => 2, 'name' => 'Mie Goreng', 'description' => 'Fried noodles with vegetables', 'price' => 18000, 'is_available' => true],
            ['category_id' => 2, 'name' => 'Nasi Putih', 'description' => 'Steamed white rice', 'price' => 5000, 'is_available' => true],
            
            // Snacks
            ['category_id' => 3, 'name' => 'French Fries', 'description' => 'Crispy french fries', 'price' => 15000, 'is_available' => true],
            ['category_id' => 3, 'name' => 'Pisang Goreng', 'description' => 'Fried banana fritters', 'price' => 12000, 'is_available' => true],
            ['category_id' => 3, 'name' => 'Tahu Isi', 'description' => 'Stuffed tofu with vegetables', 'price' => 10000, 'is_available' => true],
            
            // Beverages
            ['category_id' => 4, 'name' => 'Es Teh Manis', 'description' => 'Sweet iced tea', 'price' => 5000, 'is_available' => true],
            ['category_id' => 4, 'name' => 'Es Jeruk', 'description' => 'Fresh orange juice', 'price' => 8000, 'is_available' => true],
            ['category_id' => 4, 'name' => 'Air Mineral', 'description' => 'Mineral water', 'price' => 3000, 'is_available' => true],
            
            // Coffee & Tea
            ['category_id' => 5, 'name' => 'Kopi Susu', 'description' => 'Coffee with milk', 'price' => 12000, 'is_available' => true],
            ['category_id' => 5, 'name' => 'Kopi Hitam', 'description' => 'Black coffee', 'price' => 8000, 'is_available' => true],
            ['category_id' => 5, 'name' => 'Cappuccino', 'description' => 'Espresso with steamed milk', 'price' => 15000, 'is_available' => true],
            ['category_id' => 5, 'name' => 'Teh Tarik', 'description' => 'Pulled tea with milk', 'price' => 10000, 'is_available' => true],
            
            // Desserts
            ['category_id' => 6, 'name' => 'Es Krim Vanilla', 'description' => 'Vanilla ice cream', 'price' => 12000, 'is_available' => true],
            ['category_id' => 6, 'name' => 'Pudding', 'description' => 'Homemade pudding', 'price' => 10000, 'is_available' => true],
        ];

        foreach ($menus as $menuData) {
            Menu::create($menuData);
        }
        $this->command->info('✓ Created ' . count($menus) . ' menu items');

        $this->command->info('');
        $this->command->info('Demo data seeded successfully! 🎉');
        $this->command->info('');
        $this->command->info('You can now:');
        $this->command->info('1. Login to admin: http://127.0.0.1:8000/login');
        $this->command->info('   Email: admin@warkop.com | Password: password');
        $this->command->info('');
        $this->command->info('2. View tables and QR codes in admin panel');
        $this->command->info('3. Scan QR to test customer flow');
    }
}
