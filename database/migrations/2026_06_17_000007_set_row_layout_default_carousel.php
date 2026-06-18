<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Converte registros existentes para 'carousel' (comportamento original)
        DB::table('category_sections')
            ->where('row_layout', 'justified')
            ->update(['row_layout' => 'carousel']);

        Schema::table('category_sections', function (Blueprint $table) {
            $table->string('row_layout')->default('carousel')->change();
        });
    }

    public function down(): void
    {
        DB::table('category_sections')
            ->where('row_layout', 'carousel')
            ->update(['row_layout' => 'justified']);

        Schema::table('category_sections', function (Blueprint $table) {
            $table->string('row_layout')->default('justified')->change();
        });
    }
};
