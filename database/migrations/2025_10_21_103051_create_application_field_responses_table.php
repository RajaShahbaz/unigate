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
        Schema::create('application_field_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('university_application_field_id')->constrained('university_application_fields')->onDelete('cascade');
            
            $table->text('response_value')->nullable(); // Text response
            $table->string('file_path')->nullable(); // File path for file uploads
            
            $table->timestamps();
            
            $table->index('application_id');
            $table->unique(['application_id', 'university_application_field_id'], 'unique_application_field_response');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_field_responses');
    }
};
