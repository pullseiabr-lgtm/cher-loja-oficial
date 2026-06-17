<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorySectionProduct extends Model
{
    protected $table = 'category_section_products';
    protected $fillable = ['category_section_id', 'product_id'];

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id')->withTrashed();
    }
}
