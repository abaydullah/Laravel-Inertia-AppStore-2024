<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class App extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function developer()
    {
        return $this->belongsTo(Developer::class);
    }

    public function screenshots()
    {
        return $this->hasMany(Screenshot::class);
    }
    public function versions()
    {
        return $this->hasMany(Version::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function iconUrl()
    {

        if (Storage::disk('public')->exists('icons/'.$this->icon)) {

            return Storage::disk('public')->url('icons/'.$this->icon);
        }
        if (isset($this->icon_url)) {

            return $this->icon_url;
        }
        return '/assets/img/lazy.png';
    }
    public function coverImage()
    {
        if (Storage::disk('public')->exists('cover/'.$this->cover_image)) {

            return Storage::disk('public')->url('cover/'.$this->cover_image);
        }

        return '/assets/img/lazy.png';
    }

    public function scopeType(Builder $query,$type)
    {
        $query->where('type', '=', $type);
    }
}
