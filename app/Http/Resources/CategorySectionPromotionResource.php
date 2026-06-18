<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategorySectionPromotionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                   => $this->id,
            'category_section_id'  => $this->category_section_id,
            'promotion_id'         => $this->promotion_id,
            'promotion_name'       => optional($this->promotion)->name,
            'promotion_cover'      => optional($this->promotion)->cover,
            'promotion_status'     => optional($this->promotion)->status,
            'promotion_type'       => optional($this->promotion)->type,
            'promotion_link_type'  => optional($this->promotion)->link_type,
            'promotion_link_url'   => optional($this->promotion)->link_url,
        ];
    }
}
