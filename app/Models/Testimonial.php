<?php

namespace App\Models;

use App\Enums\Status;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Testimonial extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'testimonials';

    protected $fillable = [
        'name',
        'content',
        'rating',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'id'         => 'integer',
        'name'       => 'string',
        'content'    => 'string',
        'rating'     => 'integer',
        'status'     => 'integer',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', Status::ACTIVE);
    }

    public function getImageAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('testimonial'))) {
            $media = $this->getMedia('testimonial')->last();
            return $media->getUrl('thumb');
        }
        return asset('images/default/user.png');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Fill, 200, 200)
            ->keepOriginalImageFormat()
            ->sharpen(10);
    }
}
