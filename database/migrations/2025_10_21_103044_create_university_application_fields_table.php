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
        Schema::create('university_application_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->onDelete('cascade');
            
            $table->string('field_name'); // e.g., "Portfolio", "Essay", "Medical Certificate"
            $table->string('field_label'); // Display label for the field
            $table->text('field_description')->nullable(); // Instructions for the field
            
            $table->enum('field_type', [
                'text',           // Single line text
                'textarea',       // Multi-line text
                'number',         // Numeric input
                'date',           // Date picker
                'file',           // File upload
                'select',         // Dropdown
                'checkbox',       // Checkbox
                'radio',          // Radio buttons
                'email',          // Email input
                'url'             // URL input
            ]);
            
            $table->json('field_options')->nullable(); // For select, checkbox, radio (store options as JSON)
            $table->string('file_types')->nullable(); // Allowed file types for file uploads (e.g., "pdf,doc,docx")
            $table->integer('max_file_size')->nullable(); // Max file size in KB
            
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0); // For ordering fields
            $table->boolean('status')->default(true); // Active/Inactive
            
            $table->timestamps();
            
            $table->index(['university_id', 'status']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('university_application_fields');
    }
};
