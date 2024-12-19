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
        Schema::create('work_review_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_review_id')->constrained('work_reviews')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('work_review_comments')->nullOnDelete(); // 自己参照の外部キー
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('body');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_review_comments');
    }
};
