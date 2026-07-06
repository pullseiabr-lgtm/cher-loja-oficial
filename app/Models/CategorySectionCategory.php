<?php

namespace App\Models;

use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CategorySectionCategory extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['category_section_id', 'product_category_id', 'name'];

    public function categorySection(): BelongsTo
    {
        return $this->belongsTo(CategorySection::class);
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function getThumbAttribute(): ?string
    {
        if (!empty($this->getFirstMediaUrl('category-section-category'))) {
            $media = $this->getMedia('category-section-category')->last();
            return $media->getUrl('thumb');
        }
        return optional($this->productCategory)->thumb;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit(Fit::Fill, 108, 108)->keepOriginalImageFormat()->sharpen(10);
    }
}
