<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed categories
        Category::create(['name' => 'Electronics']);
        Category::create(['name' => 'Clothing']);
        Category::create(['name' => 'Books']);
        Category::create(['name' => 'Home Appliances']);
        Category::create(['name' => 'Furniture']);
        Category::create(['name' => 'Toys']);
    }
}
