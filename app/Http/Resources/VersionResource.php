<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VersionResource extends JsonResource
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
            'version'=> $this->version,
            'version_code'=> $this->version_code,
            'screen_dpi'=> $this->screen_dpi,
            'sha1'=> $this->sha1,
            'sha256'=> $this->sha256,
            'md5'=> $this->md5,
            'minimum_android'=> $this->minimum_android,
            'minimum_android_level'=> $this->minimum_android_level,
            'target_android'=> $this->target_android,
            'target_android_level'=> $this->target_android_level,
            'architecture'=> $this->architecture,
            'permissions'=> $this->permissions,
            'Languages'=> $this->Languages,
            'signature'=> $this->signature,
            'file'=> $this->file,
            'file_size'=> $this->fileSize(),
            'file_type'=> $this->file_type,
            'date'=> date('d/m/Y',strtotime($this->date)),
            'date_format'=> date('M d, Y',strtotime($this->date)),
            'whats_new'=> $this->whats_new,

        ];
    }
}
