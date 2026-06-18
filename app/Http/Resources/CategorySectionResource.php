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
            'item_template'  => $this->item_template ?? 'card',
            'row_layout'     => $this->row_layout ?? 'justified',
            'status'         => $this->status,
        ];
    }
}
