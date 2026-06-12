<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionSectionPromotion extends Model
{
    protected $fillable = ['promotion_section_id', 'promotion_id', 'order'];

    public function promotionSection(): BelongsTo
    {
        return $this->belongsTo(PromotionSection::class);
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }
}
