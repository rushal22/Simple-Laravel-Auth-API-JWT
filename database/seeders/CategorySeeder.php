<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating top-level category Creating subcategories under 'Clothing'
        $clothing = Category::create(['name' => 'Clothing']);
        Category::create(['name' => 'Men', 'parent_id' => $clothing->id]);
        Category::create(['name' => 'Women', 'parent_id' => $clothing->id]);
        Category::create(['name' => 'Kids', 'parent_id' => $clothing->id]);

        // Electronics Category and its subcategories
        $electronics = Category::create(['name' => 'Electronics']);
        Category::create(['name' => 'Mobile Accessories', 'parent_id' => $electronics->id]);
        Category::create(['name' => 'Computer Accessories', 'parent_id' => $electronics->id]);
        Category::create(['name' => 'Cameras', 'parent_id' => $electronics->id]);
        

        // Home Appliances Category and its subcategories
        $homeAppliances = Category::create(['name' => 'Home Appliances']);
        Category::create(['name' => 'Kitchen Appliances', 'parent_id' => $homeAppliances->id]);
        Category::create(['name' => 'Laundry Appliances', 'parent_id' => $homeAppliances->id]);
        Category::create(['name' => 'Air Conditioners', 'parent_id' => $homeAppliances->id]);

        // Books Category without subcategories
        Category::create(['name' => 'Books']);

        // Sports Category and its subcategories
        $sports = Category::create(['name' => 'Sports']);
        Category::create(['name' => 'Outdoor', 'parent_id' => $sports->id]);
        Category::create(['name' => 'Indoor', 'parent_id' => $sports->id]);
        Category::create(['name' => 'Fitness', 'parent_id' => $sports->id]);

        // Adding a category without subcategories
        Category::create(['name' => 'Toys']);

        Category::create(['name' => 'Cosmetics']);
    }
}
