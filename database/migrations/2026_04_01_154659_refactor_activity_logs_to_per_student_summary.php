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
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('view_count')->default(0)->after('roll_number');
            $table->unsignedBigInteger('print_count')->default(0)->after('view_count');
            $table->dropColumn('action');
            // Ensure one record per student
            $table->unique('roll_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('action')->after('roll_number')->nullable();
            $table->dropUnique(['roll_number']);
            $table->dropColumn(['view_count', 'print_count']);
        });
    }
};
