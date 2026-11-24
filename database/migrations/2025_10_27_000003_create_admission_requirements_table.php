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
        Schema::create('admission_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->onDelete('cascade');
            $table->foreignId('interest_id')->nullable()->constrained('interests')->onDelete('cascade'); // Program/Course
            $table->string('program_level'); // undergraduate, postgraduate, diploma, etc.
            $table->text('general_requirements')->nullable();
            $table->text('academic_requirements')->nullable(); // e.g., Minimum GPA, specific subjects
            $table->decimal('minimum_percentage', 5, 2)->nullable();
            $table->decimal('minimum_gpa', 3, 2)->nullable();
            $table->text('entrance_tests')->nullable(); // e.g., SAT, MDCAT, ECAT, etc.
            $table->integer('minimum_test_score')->nullable();
            $table->text('required_documents')->nullable(); // JSON or comma-separated list
            $table->text('language_requirements')->nullable(); // e.g., IELTS, TOEFL
            $table->decimal('language_test_score', 5, 2)->nullable();
            $table->text('specific_requirements')->nullable(); // Program-specific requirements
            $table->date('application_deadline')->nullable();
            $table->decimal('application_fee', 10, 2)->nullable();
            $table->text('admission_process')->nullable();
            $table->integer('estimated_processing_days')->nullable();
            $table->text('interview_required')->nullable();
            $table->text('portfolio_required')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_requirements');
    }
};

