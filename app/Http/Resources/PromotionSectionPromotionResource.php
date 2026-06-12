<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromotionSectionPromotionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'promotion_id' => $this->promotion_id,
            'name'         => $this->promotion?->name,
            'cover'        => $this->promotion?->cover,
            'link_type'    => $this->promotion?->link_type,
            'link_url'     => $this->promotion?->link_url,
            'status'       => $this->promotion?->status,
            'order'        => $this->order,
        ];
    }
}
