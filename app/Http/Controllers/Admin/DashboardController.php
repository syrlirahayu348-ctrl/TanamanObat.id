<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Plant;
use App\Models\User;
use App\Models\Favorite;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_plants'     => Plant::count(),
            'published_plants' => Plant::published()->count(),
            'draft_plants'     => Plant::where('status', 'draft')->count(),
            'total_users'      => User::count(),
            'total_categories' => Category::count(),
            'total_favorites'  => Favorite::count(),
        ];

        $recentPlants = Plant::with('category', 'user')->latest()->limit(5)->get();
        $recentUsers  = User::latest()->limit(5)->get();
        $popularPlants = Plant::published()->popular(5)->with('category')->get();

        return view('admin.dashboard', compact('stats', 'recentPlants', 'recentUsers', 'popularPlants'));
    }
}
