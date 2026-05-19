<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('site_menu_items')) {
            Schema::create('site_menu_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('site_menu_id')->constrained('site_menus')->onDelete('cascade');
                $table->foreignId('parent_id')->nullable()->constrained('site_menu_items')->onDelete('cascade');
                $table->string('title');
                // type: custom | category | page | categories_all
                $table->string('type', 30)->default('custom');
                $table->unsignedBigInteger('reference_id')->nullable(); // category_id or page_id
                $table->string('url', 500)->nullable();
                $table->string('target', 10)->default('_self');
                $table->integer('sort_order')->default(0);
                $table->tinyInteger('status')->default(1);
                $table->nullableMorphs('creator');
                $table->nullableMorphs('editor');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_menu_items');
    }
};
