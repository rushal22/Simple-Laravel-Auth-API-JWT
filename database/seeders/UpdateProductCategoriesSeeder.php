<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class UpdateProductCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            // Assuming $product->category contains the category name
            $category = Category::where('name', $product->category)->first();

            if ($category) {
                // Update the product's category_id
                $product->update(['category_id' => $category->id]);
            } else {
                // Log or handle the case where the category was not found
                // \Log::warning("Category not found for product ID: {$product->id}");
                Log::warning("Category not found for product ID: {$product->id}");
            }
        }
    }
}
