<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Only seed if no menus exist yet
        if (DB::table('site_menus')->count() === 0) {
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

            // Header items
            DB::table('site_menu_items')->insert([
                ['site_menu_id' => $headerId, 'parent_id' => null, 'title' => 'Início',      'type' => 'custom',         'url' => '/',        'sort_order' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['site_menu_id' => $headerId, 'parent_id' => null, 'title' => 'Categorias',  'type' => 'categories_all', 'url' => null,       'sort_order' => 2, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['site_menu_id' => $headerId, 'parent_id' => null, 'title' => 'Ofertas',     'type' => 'custom',         'url' => '/offers',  'sort_order' => 3, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ]);

            // Footer: parent groups
            $empresaId = DB::table('site_menu_items')->insertGetId([
                'site_menu_id' => $footerId, 'parent_id' => null, 'title' => 'EMPRESA',  'type' => 'custom', 'url' => null, 'sort_order' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now(),
            ]);
            $ajudaId = DB::table('site_menu_items')->insertGetId([
                'site_menu_id' => $footerId, 'parent_id' => null, 'title' => 'AJUDA',    'type' => 'custom', 'url' => null, 'sort_order' => 2, 'status' => 1, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('site_menus')->whereIn('location', ['header', 'footer'])->delete();
    }
};
