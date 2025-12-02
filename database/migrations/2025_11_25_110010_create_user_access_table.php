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
        Schema::create('pmgi_user_access', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 20);
            $table->dateTime('login_dt');
            $table->dateTime('logout_dt')->nullable();
            $table->string('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmgi_user_access');
    }
};
