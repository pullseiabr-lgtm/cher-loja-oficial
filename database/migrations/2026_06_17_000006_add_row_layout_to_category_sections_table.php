<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('category_sections', function (Blueprint $table) {
            $table->string('row_layout')->default('justified')->after('item_template');
        });
    }

    public function down(): void
    {
        Schema::table('category_sections', function (Blueprint $table) {
            $table->dropColumn('row_layout');
        });
    }
};
