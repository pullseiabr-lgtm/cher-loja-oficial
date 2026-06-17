<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategorySection extends Model
{
    protected $fillable = ['name', 'slug', 'type', 'status'];

    protected $casts = [
        'status' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', Status::ACTIVE);
    }

    public function categorySectionCategories(): HasMany
    {
        return $this->hasMany(CategorySectionCategory::class);
    }

    public function categorySectionProducts(): HasMany
    {
        return $this->hasMany(CategorySectionProduct::class);
    }

    public function categorySectionPromotions(): HasMany
    {
        return $this->hasMany(CategorySectionPromotion::class);
    }

    public function productCategories(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductCategory::class,
            'category_section_categories',
            'category_section_id',
            'product_category_id'
        );
    }
}
