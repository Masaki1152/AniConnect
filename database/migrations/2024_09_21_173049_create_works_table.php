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
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('creator_id');
            $table->unsignedInteger('music_id');
            $table->unsignedInteger('character_id');
            $table->unsignedInteger('anime_pilrrimage_id');
            $table->string('name');
            $table->string('image');
            $table->string('term');
            $table->string('official_site_link');
            $table->string('wiki_link');
            $table->string('twitter_link');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};
