<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategorySectionProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                  => $this->id,
            'category_section_id' => $this->category_section_id,
            'product_id'          => $this->product_id,
            'product_name'        => optional($this->product)->name,
            'product_price'       => optional($this->product)->price,
            'product_status'      => optional($this->product)->status,
        ];
    }
}
