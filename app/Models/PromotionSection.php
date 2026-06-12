<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromotionSection extends Model
{
    protected $fillable = ['name', 'slug', 'layout_type', 'columns', 'status'];

    protected $casts = [
        'status'  => 'integer',
        'columns' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', Status::ACTIVE);
    }

    public function promotionSectionPromotions(): HasMany
    {
        return $this->hasMany(PromotionSectionPromotion::class);
    }

    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(
            Promotion::class,
            'promotion_section_promotions',
            'promotion_section_id',
            'promotion_id'
        )->withPivot('order')->orderBy('promotion_section_promotions.order');
    }
}
