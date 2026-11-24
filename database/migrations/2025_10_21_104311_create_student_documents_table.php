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
        Schema::create('student_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('document_name'); // User-defined name for the document
            $table->text('notes')->nullable(); // User notes about the document
            $table->string('file_path'); // Path to the uploaded file
            $table->string('original_filename'); // Original file name
            $table->string('file_type')->nullable(); // MIME type
            $table->integer('file_size')->nullable(); // File size in bytes
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_documents');
    }
};
