<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('category_section_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_section_id')->constrained('category_sections')->onDelete('cascade');
            $table->foreignId('product_category_id')->constrained('product_categories')->onDelete('cascade');
            $table->string('creator_type')->nullable();
            $table->bigInteger('creator_id')->nullable();
            $table->string('editor_type')->nullable();
            $table->bigInteger('editor_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_section_categories');
    }
};
