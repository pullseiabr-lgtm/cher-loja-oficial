<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('category_sections', function (Blueprint $table) {
            $table->string('item_image_size', 20)->nullable()->after('item_template');
        });
    }

    public function down(): void
    {
        Schema::table('category_sections', function (Blueprint $table) {
            $table->dropColumn('item_image_size');
        });
    }
};
