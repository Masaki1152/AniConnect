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
        Schema::create('character_post_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_post_category_id')->constrained('character_post_categories')->onDelete('cascade');
            $table->foreignId('character_post_id')->constrained('character_posts')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_post_category');
    }
};
