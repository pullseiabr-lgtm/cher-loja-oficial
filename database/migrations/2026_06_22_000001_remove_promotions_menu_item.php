<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('menus')->where('url', 'promotions')->delete();
    }

    public function down(): void
    {
        DB::table('menus')->insert([
            'name'       => 'Promotions',
            'language'   => 'promotions',
            'url'        => 'promotions',
            'icon'       => 'lab lab-line-promotion',
            'priority'   => 100,
            'status'     => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
