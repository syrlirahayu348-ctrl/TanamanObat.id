<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['publishedPlants'])->get();
        return view('categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $plants = $category->publishedPlants()->with('category')->paginate(12);
        return view('categories.show', compact('category', 'plants'));
    }
}
