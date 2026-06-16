<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Plant;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    public function index(Request $request)
    {
        $query      = $request->get('q');
        $categoryId = $request->get('category');
        $sort       = $request->get('sort', 'latest');

        $plants = Plant::published()->with('category');

        if ($query) {
            $plants->search($query);
        }

        if ($categoryId) {
            $plants->where('category_id', $categoryId);
        }

        $plants = match($sort) {
            'popular' => $plants->orderByDesc('views'),
            'az'      => $plants->orderBy('local_name'),
            default   => $plants->latest(),
        };

        $plants     = $plants->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('plants.index', compact('plants', 'categories', 'query', 'categoryId', 'sort'));
    }

    public function show(Plant $plant)
    {
        abort_if($plant->status !== 'published', 404);

        $plant->incrementViews();
        $plant->load(['category', 'images', 'user']);

        $relatedPlants = Plant::published()
            ->where('category_id', $plant->category_id)
            ->where('id', '!=', $plant->id)
            ->limit(4)
            ->get();

        $isFavorited = auth()->check()
            ? $plant->isFavoritedBy(auth()->user())
            : false;

        return view('plants.show', compact('plant', 'relatedPlants', 'isFavorited'));
    }
}
