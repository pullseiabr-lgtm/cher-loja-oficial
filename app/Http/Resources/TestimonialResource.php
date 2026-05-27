<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'content'    => $this->content,
            'rating'     => (int) $this->rating,
            'status'     => (int) $this->status,
            'sort_order' => (int) ($this->sort_order ?? 0),
            'image'      => $this->image,
        ];
    }
}
