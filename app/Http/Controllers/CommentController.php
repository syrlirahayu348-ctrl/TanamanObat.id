<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Plant;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Plant $plant)
    {
        $user = auth()->user();

        // 1. Handle Reply to existing comment
        if ($request->has('parent_id')) {
            $request->validate([
                'parent_id' => ['required', 'exists:comments,id'],
                'comment' => ['required', 'string', 'max:1000'],
            ]);

            // Create the reply
            Comment::create([
                'user_id'   => $user->id,
                'plant_id'  => $plant->id,
                'parent_id' => $request->parent_id,
                'rating'    => null, // replies don't require rating
                'comment'   => $request->comment,
            ]);

            return back()->with('success', 'Balasan ulasan berhasil dikirim!');
        }

        // 2. Handle standard comment/rating submission
        $isAdminOrEditor = $user->isAdmin() || $user->isEditor();

        $rules = [
            'comment' => ['required', 'string', 'max:1000'],
        ];

        // Rating is required for regular users, but optional for Admin/Editor
        if ($isAdminOrEditor) {
            $rules['rating'] = ['nullable', 'integer', 'min:1', 'max:5'];
        } else {
            $rules['rating'] = ['required', 'integer', 'min:1', 'max:5'];
        }

        $request->validate($rules);

        if ($isAdminOrEditor) {
            // Admin & Editor: unlimited new comments/ratings
            Comment::create([
                'user_id'  => $user->id,
                'plant_id' => $plant->id,
                'rating'   => $request->rating, // can be null
                'comment'  => $request->comment,
            ]);
            $msg = 'Ulasan/Komentar berhasil ditambahkan!';
        } else {
            // Regular User: 1 ulasan per tanaman (bisa di-update)
            Comment::updateOrCreate(
                [
                    'user_id'   => $user->id,
                    'plant_id'  => $plant->id,
                    'parent_id' => null,
                ],
                [
                    'rating'  => $request->rating,
                    'comment' => $request->comment,
                ]
            );
            $msg = 'Ulasan Anda berhasil disimpan!';
        }

        return back()->with('success', $msg);
    }
}
