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
        Schema::create('category_music_post', function (Blueprint $table) {
            $table->id();
            $table->foreignId('music_post_category_id')->constrained('music_post_categories')->onDelete('cascade');
            $table->foreignId('music_post_id')->constrained('music_posts')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_music_post');
    }
};
