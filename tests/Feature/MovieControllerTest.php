<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Jobs\ScrapeMovies;
use App\Models\Movie;
use Illuminate\Support\Facades\Queue;

use Illuminate\Support\Facades\Log;

class MovieControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_scrapeAndInsertMovies()
    {
        // Prevent actual job execution and logging during the test
        Queue::fake();
        Log::shouldReceive('error')->andReturnNull();

        // Act: Call the API endpoint
        $response = $this->get('/scrape'); // Adjust the route as per your actual route

        // Assert that the response is successful
        $response->assertStatus(200)->assertJson(['message' => 'Movies scraped and inserted successfully']);

        // Assert that the job was dispatched
        Queue::assertPushed(ScrapeMovies::class);

    
        $pushedJob = collect(Queue::pushed(ScrapeMovies::class))->first();

        // For example, you can check if the movies were inserted into the database
        $this->assertDatabaseCount('movies', 0); // Initially, there should be no records

        // Run the job (simulating the queue worker processing)
        Queue::push(new ScrapeMovies());

        // Assert that the job processed successfully
        $this->assertDatabaseCount('movies', 10); // Assuming 10 movies are inserted

        // Optionally, you can further assert specific data in the database
        $this->assertDatabaseHas('movies', ['title' => 'Movie Title']);
        $this->assertDatabaseHas('movies', ['title' => 'Another Movie Title']);
        // ... assert other movie records as needed

        // Optionally, you can also assert that errors are logged properly if any occur during job execution
        Log::shouldReceive('error')->withArgs(function ($message) {
            return str_contains($message, 'Error inserting movie');
        })->atLeast(1); // At least one error log should be recorded
    }
}