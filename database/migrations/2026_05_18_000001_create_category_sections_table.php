<?php

use App\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('category_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->unsignedTinyInteger('status')->default(Status::ACTIVE);
            $table->string('creator_type')->nullable();
            $table->bigInteger('creator_id')->nullable();
            $table->string('editor_type')->nullable();
            $table->bigInteger('editor_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_sections');
    }
};
