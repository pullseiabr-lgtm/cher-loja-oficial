<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategorySectionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'type'           => $this->type ?? 'categories',
            'title_tag'      => $this->title_tag ?? 'h2',
            'title_position' => $this->title_position ?? 'left',
            'status'         => $this->status,
        ];
    }
}
