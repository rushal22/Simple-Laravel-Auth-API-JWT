<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private function formatCategories($categories)
    {
        $formatted = [];
        foreach ($categories as $category) {
            $formatted[] = [
                'id' => $category->id,
                'name' => $category->name,
                'children' => $this->formatCategories($category->children)
            ];
        }
        return $formatted;
    }
    
    public function index()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return response()->json($this->formatCategories($categories));
    }

    public function show(Category $category)
    {
        $category->load('products', 'children.children');
        return response()->json($category);
    }

    public function getCategories()
    {
        try {
            $categories = Category::select(['id', 'name'])->orderBy('id', 'ASC')->get();
            return response()->json(['categories' => $categories], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error occurred', 'errors' => $e->getMessage()], 400);
        }
    }
}
