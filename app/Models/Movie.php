<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = ['title', 'year', 'rating', 'url'];

    protected $casts = [
        'rating' => 'decimal:2', // Set the desired number of decimal places
    ];
}