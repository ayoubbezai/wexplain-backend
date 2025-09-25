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
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // Link to users table with UUID
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            // Student details (kept as text for encryption)
            $table->text("phone_number");
            $table->text("second_number")->nullable();
            $table->text("parent_number")->nullable();
            $table->date("date_of_birth");
            $table->text("address")->nullable();
            $table->text("year_of_study");
            $table->string('gender', 20);

            $table->string('student_image_url')->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
