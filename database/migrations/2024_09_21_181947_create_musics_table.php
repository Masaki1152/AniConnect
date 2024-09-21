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
        Schema::create('musics', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('singer_id');
            $table->unsignedInteger('lyric_writer_id');
            $table->unsignedInteger('composer_id');
            $table->string('name');
            $table->string('release_date');
            $table->string('wiki_link');
            $table->string('youtube_link');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('musics');
    }
};
