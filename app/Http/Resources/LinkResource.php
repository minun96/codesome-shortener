<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LinkResource extends JsonResource
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
            'original_url' => $this->long_url,
            'short_code' => $this->short_code,
            'full_short_url' => $this->short_url,
            'created_at' => $this->created_at->toIso8601String(), // evita problemi di parsing
        ];
    }
}
