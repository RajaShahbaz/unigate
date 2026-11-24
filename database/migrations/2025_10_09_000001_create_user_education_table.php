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
        Schema::create('user_education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('education_level', ['matric', 'intermediate', 'graduation', 'post_graduation', 'diploma', 'other']);
            $table->string('institution_name');
            $table->string('board_university')->nullable();
            $table->string('field_of_study')->nullable();
            $table->year('start_year');
            $table->year('end_year')->nullable();
            $table->decimal('percentage_cgpa', 5, 2)->nullable();
            $table->string('grade_type')->default('percentage'); // percentage or cgpa
            $table->string('certificate_document')->nullable(); // file path
            $table->text('additional_info')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_education');
    }
};

