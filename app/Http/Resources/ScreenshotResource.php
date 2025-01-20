<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScreenshotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'photo'=>$this->screenshotUrl(),
            'photo_url'=>$this->photo_url,
            'type'=>$this->type,
            'size'=>$this->size,
        ];
    }
}
