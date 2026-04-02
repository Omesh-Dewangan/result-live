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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->string('roll_number')->unique()->index();
            $table->string('name')->index();
            $table->string('father_name');
            $table->string('course');
            $table->integer('subject1')->default(0);
            $table->integer('subject2')->default(0);
            $table->integer('subject3')->default(0);
            $table->integer('subject4')->default(0);
            $table->integer('subject5')->default(0);
            $table->integer('total')->default(0);
            $table->string('result_status'); // Pass/Fail
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
