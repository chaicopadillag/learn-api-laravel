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
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('name', 50);
            $table->string('description', 255)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('type', ['in_person', 'virtual']);
            $table->boolean('status')->default(true);
            $table->foreignUuid('teacher_id')->references('id')->on('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
