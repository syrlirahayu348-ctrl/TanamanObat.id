<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Plant;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()->favoritePlants()
            ->with('category')
            ->paginate(12);

        return view('user.favorites', compact('favorites'));
    }

    public function toggle(Request $request, Plant $plant)
    {
        $user = auth()->user();

        $favorite = Favorite::where('user_id', $user->id)
            ->where('plant_id', $plant->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $isFavorited = false;
            $message     = 'Tanaman dihapus dari favorit.';
        } else {
            Favorite::create([
                'user_id'  => $user->id,
                'plant_id' => $plant->id,
            ]);
            $isFavorited = true;
            $message     = 'Tanaman ditambahkan ke favorit!';
        }

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success'    => true,
                'favorited'  => $isFavorited,
                'message'    => $message,
                'count'      => $plant->favorites()->count(),
            ]);
        }

        return back()->with('success', $message);
    }
}
