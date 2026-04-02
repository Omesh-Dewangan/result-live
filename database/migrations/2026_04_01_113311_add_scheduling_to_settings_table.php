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
        Schema::table('settings', function (Blueprint $table) {
            $table->dateTime('result_from')->nullable();
            $table->dateTime('result_to')->nullable();
            $table->dateTime('login_from')->nullable();
            $table->dateTime('login_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['result_from', 'result_to', 'login_from', 'login_to']);
        });
    }
};
