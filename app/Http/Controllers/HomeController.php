<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Plant;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPlants  = Plant::published()->popular(6)->with('category')->get();
        $latestPlants    = Plant::published()->latest()->limit(8)->with('category')->get();
        $categories      = Category::withCount(['publishedPlants'])->get();
        $totalPlants     = Plant::published()->count();
        $totalCategories = Category::count();

        return view('home.index', compact(
            'featuredPlants', 'latestPlants', 'categories', 'totalPlants', 'totalCategories'
        ));
    }

    public function search()
    {
        $query = request('q');
        $plants = Plant::published()
            ->search($query)
            ->with('category')
            ->paginate(12);

        $categories = Category::all();

        return view('plants.index', compact('plants', 'query', 'categories'));
    }

    public function faq()
    {
        return view('faq');
    }

    public function contact()
    {
        return view('contact');
    }

    public function submitContact(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ]);

        return back()->with('success', 'Pesan Anda berhasil dikirim! Admin kami akan segera menghubungi Anda.');
    }
}
