<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Storage;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;
    protected $guarded = false;

    public function getIconAttribute()
    {
        return '/assets/img/lazy.png';
    }

    public function scopeRatingFive($query)
    {
       return $query->where('rating', 5);
    }
    public function scopeRatingFour($query)
    {
       return $query->where('rating', 4);
    }
    public function scopeRatingThree($query)
    {
       return $query->where('rating', 3);
    }
    public function scopeRatingTwo($query)
    {
       return $query->where('rating', 2);
    }
    public function scopeRatingOne($query)
    {
       return $query->where('rating', 1);
    }

}
