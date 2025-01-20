<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use KnotsPHP\PublicIP\Finders\PublicIP;
use phpDocumentor\Reflection\Location;

class AppResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $review_percentage = $this->reviews_count > 0 ? 100 / $this->reviews_count : 0;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'app_id' => $this->app_id,
            'icon' => $this->iconUrl(),
            'rating' => $this->rating,
            $this->mergeWhen($request->routeIs(['app.view*','admin.apps*']), [
                'url' => $this->url,
                'cover_image' => $this->coverImage(),
                'downloads' => $this->downloads,
                'histograms' => $this->histograms,
                'paid' => $this->paid,
                'updated' => $this->updated,
                'updated_at' => $this->updated_at->format('d M Y'),
                'trailer' => $this->trailer,
                'type' => $this->type,
                'developer_id' => $this->developer_id,
                'category_id' => $this->category_id,
                'whats_new' => $this->whats_new,
                'meta_description' => $this->meta_description,
                'description' => $this->description,

                'status' => $this->status,
            ]),
            'screenshots' => ScreenshotResource::collection($this->whenLoaded('screenshots')),
            'developer' => new DeveloperResource($this->whenLoaded('developer')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'versions' => VersionResource::collection($this->whenLoaded('versions')),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'review_count' => $this->mergeWhen($request->routeIs('app.view*'),[
                    'total' => $this->reviews_count,
                    'rating' => round($this->rating_avg,2),
                    'five' => $this->rating_five > 0 ? $this->rating_five * $review_percentage : $this->rating_five,
                    'four' => $this->rating_four > 0 ? $this->rating_four * $review_percentage : $this->rating_four,
                    'three' => $this->rating_three > 0 ? $this->rating_three * $review_percentage : $this->rating_three,
                    'two' => $this->rating_two > 0 ? $this->rating_two * $review_percentage : $this->rating_two,
                    'one' => $this->rating_one > 0 ? $this->rating_one * $review_percentage : $this->rating_one,
                ]),


        ];
    }
}
