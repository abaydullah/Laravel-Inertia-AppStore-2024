<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Screenshot extends Model
{
    /** @use HasFactory<\Database\Factories\ScreenshotFactory> */
    use HasFactory;
    protected $guarded = false;

    public function screenshotUrl()
    {
        if (Storage::disk('public')->exists('screenshots/'.$this->photo)) {

            return Storage::disk('local')->url('screenshots/'.$this->photo);
        }

        return $this->photo_url;
    }
}
