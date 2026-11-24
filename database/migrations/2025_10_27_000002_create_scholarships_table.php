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
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->onDelete('cascade');
            $table->string('name');
            $table->string('scholarship_type'); // merit, need-based, sports, etc.
            $table->text('description')->nullable();
            $table->text('eligibility_criteria')->nullable();
            $table->decimal('amount', 10, 2)->nullable(); // Fixed amount
            $table->decimal('percentage', 5, 2)->nullable(); // Percentage discount
            $table->enum('amount_type', ['fixed', 'percentage', 'full'])->default('fixed');
            $table->integer('total_slots')->nullable();
            $table->integer('available_slots')->nullable();
            $table->date('application_start_date')->nullable();
            $table->date('application_end_date')->nullable();
            $table->text('required_documents')->nullable(); // JSON or comma-separated
            $table->text('application_process')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->boolean('renewable')->default(false);
            $table->integer('renewable_years')->nullable();
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
        Schema::dropIfExists('scholarships');
    }
};

