<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromotionSectionWithPromotionsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'layout_type' => $this->layout_type,
            'columns'     => $this->columns,
            'promotions'  => $this->promotions->map(fn($p) => [
                'id'        => $p->id,
                'name'      => $p->name,
                'slug'      => $p->slug,
                'cover'     => $p->cover,
                'link_type' => $p->link_type,
                'link_url'  => $p->link_url,
            ])->values(),
        ];
    }
}
