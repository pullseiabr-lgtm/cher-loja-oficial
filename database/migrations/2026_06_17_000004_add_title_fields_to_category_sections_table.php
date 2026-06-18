<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('category_sections', function (Blueprint $table) {
            $table->string('title_tag')->default('h2')->after('type');
            $table->string('title_position')->default('left')->after('title_tag');
        });
    }

    public function down(): void
    {
        Schema::table('category_sections', function (Blueprint $table) {
            $table->dropColumn(['title_tag', 'title_position']);
        });
    }
};
