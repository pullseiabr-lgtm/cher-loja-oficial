<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'cover_zoom')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('cover_zoom', 3, 1)->default(1.0)->after('cover_position')
                    ->comment('CSS scale factor for square crop preview (1.0 to 3.0)');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'cover_zoom')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('cover_zoom');
            });
        }
    }
};
