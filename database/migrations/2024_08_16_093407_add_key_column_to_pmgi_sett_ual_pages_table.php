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
        Schema::table('pmgi_sett_ual_page', function (Blueprint $table) {
            $table->string('key')->unique()->after('id')->nullable(); // Add the 'key' column after the 'id' column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pmgi_sett_ual_page', function (Blueprint $table) {
            $table->dropColumn('key');
        });
    }
};
