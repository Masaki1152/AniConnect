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
        Schema::create('anime_pilgrimage_post_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('anime_pilgrimage_post_id');
            $table->unsignedInteger('user_id');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anime_pilgrimage_post_likes');
    }
};
