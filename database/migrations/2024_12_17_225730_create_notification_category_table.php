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
        Schema::create('notification_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_category_id')->constrained('notification_categories')->onDelete('cascade');
            $table->foreignId('notification_id')->constrained('notifications')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_category');
    }
};
