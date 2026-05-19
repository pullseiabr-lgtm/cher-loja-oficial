<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategorySectionCategory extends Model
{
    protected $fillable = ['category_section_id', 'product_category_id'];

    public function categorySection(): BelongsTo
    {
        return $this->belongsTo(CategorySection::class);
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
