<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Goutte\Client;
use App\Models\Movie;


class ScrapeMovies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://www.imdb.com/chart/top');

        $movieNodes = $crawler->filter('.ipc-metadata-list .ipc-metadata-list-summary-item')->slice(0, 10);
    
        $movieNodes->each(function ($node) {
        $title = $node->filter('.ipc-title__text')->text();
        $title = preg_replace('/^\d+\.\s*/', '', $title);
        
        $year = (int)$node->filter('.cli-title-metadata-item')->text();
        $rating = (float)$node->filter('.ipc-rating-star--imdb')->text();
        $url = $node->filter('.ipc-title-link-wrapper')->attr('href');
        $url = "https://www.imdb.com".$url;
        // Check if a movie with the same title and year already exists
        $existingMovie = Movie::where('title', $title)->where('year', $year)->first();

        try {
            // Insert the movie if it doesn't already exist
            if (!$existingMovie) {
                Movie::create([
                    'title' => $title,
                    'year' => $year,
                    'rating' => $rating,
                    'url' => $url,
                ]);
            }
        } catch (\Exception $e) {
           
            Log::error("Error inserting movie: $title ($year) - " . $e->getMessage());
            
        }

        });
    }
}
