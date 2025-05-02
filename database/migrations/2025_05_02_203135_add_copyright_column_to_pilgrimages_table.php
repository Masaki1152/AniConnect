<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anime_pilgrimages', function (Blueprint $table) {
            $table->string('image')->after('place');
            $table->string('copyright')->after('image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('anime_pilgrimages', function (Blueprint $table) {
            $table->dropColumn('copyright');
        });
    }
};
