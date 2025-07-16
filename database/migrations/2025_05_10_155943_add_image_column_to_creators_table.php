<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('creators', function (Blueprint $table) {
            $table->string('image')->after('name')->nullable();
            $table->string('official_site_link')->after('image')->nullable();
            $table->string('twitter_link')->after('wiki_link')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('creators', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('official_site_link');
            $table->dropColumn('twitter_link');
        });
    }
};
