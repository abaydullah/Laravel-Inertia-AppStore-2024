<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'icon'=> $this->icon,
            'user_id'=> $this->user_id,
            'rating'=> $this->rating,
            'review'=> $this->review,
            'date'=> $this->date,
            'ip'=> $this->ip,
        ];
    }
}
