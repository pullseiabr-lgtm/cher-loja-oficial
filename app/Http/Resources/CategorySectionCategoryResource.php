<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategorySectionCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                  => $this->id,
            'category_section_id' => $this->category_section_id,
            'product_category_id' => $this->product_category_id,
            'name'                => $this->name,
            'category_name'       => $this->name ?: optional($this->productCategory)->name,
            'category_slug'       => optional($this->productCategory)->slug,
            'category_status'     => optional($this->productCategory)->status,
            'category_thumb'      => $this->thumb,
            'default_name'        => optional($this->productCategory)->name,
            'default_thumb'       => optional($this->productCategory)->thumb,
        ];
    }
}
