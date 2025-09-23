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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();

            // Link to user
    // Link to users table with UUID
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            // Personal info
            $table->string('gender', 20);
            $table->string('nationality', 100);
            $table->string('phone_number', 30);
            $table->string('second_phone_number', 30)->nullable();
            $table->date('date_of_birth');

            // File storage paths
            $table->text('teacher_image_url'); // uploaded teacher image
            $table->text('id_card_image_url'); // uploaded ID card image
            $table->text('cv_url');            // uploaded CV (PDF)

            // Optional address
            $table->text('address')->nullable();

            // CCP info (encrypted fields possible)
            $table->text('ccp_number')->nullable();
            $table->text('ccp_key')->nullable();
            $table->text('ccp_account_name')->nullable();

            // Card info (encrypted fields possible)
            $table->text('card_number')->nullable();
            $table->date('card_expiry')->nullable();
            $table->text('card_cvv')->nullable();
            $table->text('card_holder_name')->nullable();

            // Teaching info
            $table->string('primary_subject', 100);
            $table->text('other_subjects')->nullable();
            $table->string('teaching_level', 100);
            $table->integer('years_of_experience');

            // Financial
            $table->decimal('credit', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
