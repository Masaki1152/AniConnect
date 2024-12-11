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
        Schema::create('work_story_post_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_story_post_category_id')->constrained('work_story_post_categories')->onDelete('cascade');
            $table->foreignId('work_story_post_id')->constrained('work_story_posts')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_story_post_category');
    }
};
