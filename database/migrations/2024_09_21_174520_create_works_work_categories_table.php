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
        Schema::create('works_work_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_category_id')->constrained('work_categories')->onDelete('cascade');
            $table->foreignId('work_id')->constrained('works')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works_work_categories');
    }
};
