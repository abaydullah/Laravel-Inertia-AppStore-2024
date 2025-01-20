<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'google_name' => $this->google_name,
            'google_url' => $this->google_url,
            'slug' => $this->slug,
            'type' => $this->type,
            'status' => $this->status,
        ];
    }
}
