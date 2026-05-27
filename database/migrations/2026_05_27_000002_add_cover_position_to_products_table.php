<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'cover_position')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('cover_position', 30)->default('center')->after('status')
                    ->comment('CSS object-position for square crop preview');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'cover_position')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('cover_position');
            });
        }
    }
};
