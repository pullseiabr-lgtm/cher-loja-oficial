<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromotionSectionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'layout_type' => $this->layout_type,
            'columns'     => $this->columns,
            'status'      => $this->status,
        ];
    }
}
