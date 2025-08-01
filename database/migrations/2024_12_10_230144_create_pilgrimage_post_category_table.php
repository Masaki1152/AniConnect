<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pilgrimage_post_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pilgrimage_post_category_id')->constrained('pilgrimage_post_categories')->onDelete('cascade');
            $table->foreignId('anime_pilgrimage_post_id')->constrained('anime_pilgrimage_posts')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pilgrimage_post_category');
    }
};
