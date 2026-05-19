<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Idempotent: only seed if empty
        if (DB::table('site_menus')->count() > 0) {
            return;
        }

        $headerId = DB::table('site_menus')->insertGetId([
            'name'       => 'Menu Header',
            'location'   => 'header',
            'status'     => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $footerId = DB::table('site_menus')->insertGetId([
            'name'       => 'Menu Footer',
            'location'   => 'footer',
            'status'     => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Header default items
        DB::table('site_menu_items')->insert([
            [
                'site_menu_id' => $headerId,
                'parent_id'    => null,
                'title'        => 'Início',
                'type'         => 'custom',
                'url'          => '/',
                'target'       => '_self',
                'sort_order'   => 1,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'site_menu_id' => $headerId,
                'parent_id'    => null,
                'title'        => 'Categorias',
                'type'         => 'categories_all',
                'url'          => null,
                'target'       => '_self',
                'sort_order'   => 2,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'site_menu_id' => $headerId,
                'parent_id'    => null,
                'title'        => 'Ofertas',
                'type'         => 'custom',
                'url'          => '/offers',
                'target'       => '_self',
                'sort_order'   => 3,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);

        // Footer: section groups
        $empresaId = DB::table('site_menu_items')->insertGetId([
            'site_menu_id' => $footerId,
            'parent_id'    => null,
            'title'        => 'EMPRESA',
            'type'         => 'custom',
            'url'          => null,
            'target'       => '_self',
            'sort_order'   => 1,
            'status'       => 1,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $ajudaId = DB::table('site_menu_items')->insertGetId([
            'site_menu_id' => $footerId,
            'parent_id'    => null,
            'title'        => 'AJUDA',
            'type'         => 'custom',
            'url'          => null,
            'target'       => '_self',
            'sort_order'   => 2,
            'status'       => 1,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }
}
