'ip' => PublicIP::get(),
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
'developer' => new DeveloperResource($this->whenLoaded('developer')),
'category' => new CategoryResource($this->whenLoaded('category')),
'screenshots' => ScreenshotResource::collection($this->whenLoaded('screenshots')),
'versions' => VersionResource::collection($this->whenLoaded('versions')),
'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
'status' => $this->status,
