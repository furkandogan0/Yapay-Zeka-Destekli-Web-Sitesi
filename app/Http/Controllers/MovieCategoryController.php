<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserMovieCategory;

class MovieCategoryController extends Controller
{
    public function addToCategory(Request $request)
    {
        $user = $request->user();
        $movieId = $request->input('movie_id');
        $category = $request->input('category');

        // Kaydı oluştur veya güncelle
        UserMovieCategory::updateOrCreate(
            ['user_id' => $user->id, 'movie_id' => $movieId],
            ['category' => $category]
        );

        return response()->json(['success' => true]);
    }
}
