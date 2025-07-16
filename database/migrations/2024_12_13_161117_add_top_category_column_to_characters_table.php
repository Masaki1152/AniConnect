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
        Schema::table('characters', function (Blueprint $table) {
            $table->unsignedBigInteger('category_top_1')->nullable();
            $table->unsignedBigInteger('category_top_2')->nullable();
            $table->unsignedBigInteger('category_top_3')->nullable();
            $table->timestamp('top_categories_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn('category_top_1');
            $table->dropColumn('category_top_2');
            $table->dropColumn('category_top_3');
            $table->dropColumn('top_categories_updated_at');
        });
    }
};
