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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('university_id')->constrained('universities')->onDelete('cascade');
            $table->foreignId('interest_id')->constrained('interests')->onDelete('cascade');
            
            // General application fields (common for all universities)
            $table->string('application_number')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone', 20);
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->text('current_address');
            $table->text('permanent_address')->nullable();
            $table->string('nationality', 100);
            
            // Education background
            $table->string('previous_school')->nullable();
            $table->string('previous_degree')->nullable();
            $table->decimal('previous_gpa', 4, 2)->nullable();
            $table->year('graduation_year')->nullable();
            
            // Documents (general)
            $table->string('transcript_file')->nullable();
            $table->string('id_document_file')->nullable();
            $table->string('photo_file')->nullable();
            $table->string('recommendation_letter_file')->nullable();
            
            // Application status and tracking
            $table->enum('status', [
                'draft',
                'submitted',
                'under_review',
                'shortlisted',
                'interview_scheduled',
                'accepted',
                'rejected',
                'withdrawn'
            ])->default('draft');
            
            $table->text('application_message')->nullable(); // Student's message/statement
            $table->text('admin_notes')->nullable(); // Notes from university admin
            $table->text('rejection_reason')->nullable(); // Reason if rejected
            
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better performance
            $table->index(['user_id', 'university_id']);
            $table->index('status');
            $table->index('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
