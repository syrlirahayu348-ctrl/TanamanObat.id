<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlantController extends Controller
{
    public function index()
    {
        $plants = Plant::where('user_id', auth()->id())
            ->orWhere(fn($q) => auth()->user()->isAdmin() ? $q : $q->where('id', -1))
            ->with('category')
            ->latest()
            ->paginate(15);

        if (auth()->user()->isAdmin()) {
            $plants = Plant::with('category', 'user')->latest()->paginate(15);
        }

        return view('editor.plants.index', compact('plants'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('editor.plants.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'local_name'     => ['required', 'string', 'max:255'],
            'latin_name'     => ['required', 'string', 'max:255'],
            'category_id'    => ['required', 'exists:categories,id'],
            'description'    => ['required', 'string'],
            'benefits'       => ['required', 'string'],
            'usage'          => ['required', 'string'],
            'side_effects'   => ['nullable', 'string'],
            'origin'         => ['nullable', 'string', 'max:255'],
            'harvest_season' => ['nullable', 'string', 'max:255'],
            'status'         => ['required', 'in:published,draft'],
            'image'          => ['nullable', 'image', 'max:4096'],
        ]);

        $data['user_id'] = auth()->id();
        $data['slug']    = Str::slug($request->local_name) . '-' . rand(1000, 9999);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('plants', 'public');
        }

        Plant::create($data);

        return redirect()->route('editor.plants.index')->with('success', 'Tanaman berhasil ditambahkan!');
    }

    public function edit(Plant $plant)
    {
        $categories = Category::all();
        return view('editor.plants.edit', compact('plant', 'categories'));
    }

    public function update(Request $request, Plant $plant)
    {
        $data = $request->validate([
            'local_name'     => ['required', 'string', 'max:255'],
            'latin_name'     => ['required', 'string', 'max:255'],
            'category_id'    => ['required', 'exists:categories,id'],
            'description'    => ['required', 'string'],
            'benefits'       => ['required', 'string'],
            'usage'          => ['required', 'string'],
            'side_effects'   => ['nullable', 'string'],
            'origin'         => ['nullable', 'string', 'max:255'],
            'harvest_season' => ['nullable', 'string', 'max:255'],
            'status'         => ['required', 'in:published,draft'],
            'image'          => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('plants', 'public');
        }

        $plant->update($data);

        return redirect()->route('editor.plants.index')->with('success', 'Tanaman berhasil diperbarui!');
    }
}
