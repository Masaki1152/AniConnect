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
        Schema::create('music', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_id')->constrained('works')->onDelete('cascade');
            $table->foreignId('singer_id')->constrained('singers')->onDelete('cascade');
            $table->foreignId('lyric_writer_id')->constrained('lyric_writers')->onDelete('cascade');
            $table->foreignId('composer_id')->constrained('composers')->onDelete('cascade');
            $table->string('name');
            $table->date('release_date');
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
        Schema::dropIfExists('music');
    }
};
