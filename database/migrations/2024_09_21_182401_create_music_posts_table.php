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
        Schema::create('music_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('music_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('work_id');
            $table->string('post_title');
            $table->integer('star_num');
            $table->string('body');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('music_posts');
    }
};
