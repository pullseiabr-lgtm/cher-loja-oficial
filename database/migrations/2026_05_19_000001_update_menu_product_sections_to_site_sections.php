<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('menus')
            ->where('language', 'product_sections')
            ->where('url', 'product-sections')
            ->update([
                'name'     => 'Seções do Site',
                'language' => 'site_sections',
                'url'      => 'settings/site-sections',
                'icon'     => 'lab lab-line-item-section',
            ]);
    }

    public function down(): void
    {
        DB::table('menus')
            ->where('language', 'site_sections')
            ->where('url', 'settings/site-sections')
            ->update([
                'name'     => 'Product Sections',
                'language' => 'product_sections',
                'url'      => 'product-sections',
                'icon'     => 'lab lab-line-product-section',
            ]);
    }
};
