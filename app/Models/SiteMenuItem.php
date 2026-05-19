<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteMenuItem extends Model
{
    protected $table = 'site_menu_items';

    protected $fillable = [
        'site_menu_id',
        'parent_id',
        'title',
        'type',
        'reference_id',
        'url',
        'target',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'id'           => 'integer',
        'site_menu_id' => 'integer',
        'parent_id'    => 'integer',
        'reference_id' => 'integer',
        'sort_order'   => 'integer',
        'status'       => 'integer',
    ];

    public function menu(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SiteMenu::class, 'site_menu_id');
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SiteMenuItem::class, 'parent_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SiteMenuItem::class, 'parent_id', 'id')->orderBy('sort_order');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'reference_id');
    }

    public function page(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Page::class, 'reference_id');
    }
}
