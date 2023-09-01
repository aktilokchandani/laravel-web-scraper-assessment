<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Movie;
use App\Jobs\ScrapeMovies;


class MovieController extends Controller
{
    public function scrapeAndInsertMovies(Request $request)
    {
        try {
            dispatch(new ScrapeMovies());
            return response()->json(['message' => 'Movies scraped and inserted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        $movies = Movie::paginate(5); 
        return view('movies.index', ['movies' => $movies]);
    }

    
}
