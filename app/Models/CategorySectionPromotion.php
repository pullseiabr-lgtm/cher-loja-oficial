<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorySectionPromotion extends Model
{
    protected $table = 'category_section_promotions';
    protected $fillable = ['category_section_id', 'promotion_id'];

    public function promotion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Promotion::class, 'promotion_id', 'id');
    }
}
