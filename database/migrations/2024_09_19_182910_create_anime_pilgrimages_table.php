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
        Schema::create('anime_pilgrimages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prefecture_id')->constrained('prefectures')->onDelete('cascade');
            $table->string('name');
            $table->string('place');
            $table->string('map_link');
            $table->string('wiki_link');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anime_pilgrimages');
    }
};
