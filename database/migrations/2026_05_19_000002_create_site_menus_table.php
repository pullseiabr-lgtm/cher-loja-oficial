<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('site_menus')) {
            Schema::create('site_menus', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('location', 20); // header, footer
                $table->tinyInteger('status')->default(1);
                $table->nullableMorphs('creator');
                $table->nullableMorphs('editor');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_menus');
    }
};
