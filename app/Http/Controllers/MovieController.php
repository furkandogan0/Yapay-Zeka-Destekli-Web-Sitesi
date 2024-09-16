<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MovieController extends Controller
{
    public function getRecommendations(Request $request)
    {
        $movieName = $request->input('movie_name');
    
        // Flask API'ye HTTP POST isteği gönder
        $response = Http::post('http://127.0.0.1:5000/recommend', [
            'movie_name' => $movieName,
        ]);
    
        // Flask'tan gelen JSON yanıtını kontrol et
        if ($response->successful()) {
            $recommendedMovies = $response->json();
            Log::info('Recommended Movies from Flask:', ['movies' => $recommendedMovies]);
        } else {
            return back()->withErrors(['movie_name' => 'Bu film veritabanımızda bulunamadı.']);
        }
    
        // TMDb ve OMDb API anahtarlarınızı environment dosyasından alıyoruz
        $tmdbApiKey = env('TMDB_API_KEY');
        $omdbApiKey = env('OMDB_API_KEY');
        $moviesWithPosters = [];
    
        foreach ($recommendedMovies as $movie) {
            // TMDb API'ye film başlığı ile istek gönderiyoruz
            $tmdbResponse = Http::get("https://api.themoviedb.org/3/search/movie", [
                'api_key' => $tmdbApiKey,
                'query' => $movie
            ]);
    
            $movieData = $tmdbResponse->json();
            Log::info('TMDb API Response for ' . $movie . ':', ['data' => $movieData]);
    
            // İlk sonucu alıyoruz, eğer sonuç varsa
            if (!empty($movieData['results'])) {
                $result = $movieData['results'][0];
                $posterPath = !empty($result['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $result['poster_path'] : asset('default.jpg');
                $imdbId = $result['id'];
    
                // IMDb ID'sini almak için başka bir istek yapıyoruz
                $imdbResponse = Http::get("https://api.themoviedb.org/3/movie/{$imdbId}", [
                    'api_key' => $tmdbApiKey,
                    'append_to_response' => 'external_ids'
                ]);
    
                $imdbData = $imdbResponse->json();
                $imdbId = $imdbData['external_ids']['imdb_id'] ?? '';
    
                $moviesWithPosters[] = [
                    'title' => $movie,
                    'poster' => $posterPath,
                    'imdb_id' => $imdbId
                ];
            } else {
                // Eğer TMDb'den sonuç bulamazsak, varsayılan resmi kullanıyoruz
                $moviesWithPosters[] = [
                    'title' => $movie,
                    'poster' => asset('default.jpg'), // Varsayılan resim
                    'imdb_id' => ''
                ];
            }
        }
    
        // Blade şablonuna afişlerle birlikte film önerilerini gönderiyoruz
        return view('recommendations', ['movies' => $moviesWithPosters]);
    }
    

    public function showRecommendationsForm()
    {
        return view('recommendations');
    }
}
