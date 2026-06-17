<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('category_section_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('promotion_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['category_section_id', 'promotion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_section_promotions');
    }
};
