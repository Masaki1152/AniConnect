<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_stories', function (Blueprint $table) {
            $table->decimal('average_star_num', 2, 1)->default(0)->after('official_link');
        });
    }

    public function down(): void
    {
        Schema::table('work_stories', function (Blueprint $table) {
            $table->dropColumn('average_star_num');
        });
    }
};
