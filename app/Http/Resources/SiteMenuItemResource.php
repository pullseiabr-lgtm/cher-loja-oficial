<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SiteMenuItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'site_menu_id'   => $this->site_menu_id,
            'parent_id'      => $this->parent_id,
            'title'          => $this->title,
            'type'           => $this->type,
            'reference_id'   => $this->reference_id,
            'url'            => $this->url,
            'target'         => $this->target,
            'sort_order'     => $this->sort_order,
            'status'         => $this->status,
            'reference_name' => $this->getReferenceNameAttribute(),
            'children'       => SiteMenuItemResource::collection($this->whenLoaded('children')),
        ];
    }

    private function getReferenceNameAttribute(): ?string
    {
        if ($this->type === 'category' && $this->category) {
            return $this->category->name;
        }
        if ($this->type === 'page' && $this->page) {
            return $this->page->title;
        }
        return null;
    }
}
