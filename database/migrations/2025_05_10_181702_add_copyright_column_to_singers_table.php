<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('singers', function (Blueprint $table) {
            $table->string('copyright')->after('image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('singers', function (Blueprint $table) {
            $table->dropColumn('copyright');
        });
    }
};
