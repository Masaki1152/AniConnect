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
        Schema::create('character_post_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_post_id')->constrained('character_posts')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('character_post_comments')->nullOnDelete(); // 自己参照の外部キー
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('body');
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->string('image4')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_post_comments');
    }
};
