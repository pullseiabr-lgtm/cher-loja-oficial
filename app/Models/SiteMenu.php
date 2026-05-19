<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteMenu extends Model
{
    protected $table = 'site_menus';

    protected $fillable = ['name', 'location', 'status'];

    protected $casts = [
        'id'       => 'integer',
        'name'     => 'string',
        'location' => 'string',
        'status'   => 'integer',
    ];

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SiteMenuItem::class, 'site_menu_id', 'id')
                    ->whereNull('parent_id')
                    ->orderBy('sort_order');
    }

    public function allItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SiteMenuItem::class, 'site_menu_id', 'id')->orderBy('sort_order');
    }
}
