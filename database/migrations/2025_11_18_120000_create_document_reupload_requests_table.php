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
        Schema::create('document_reupload_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->string('field_name');
            $table->string('field_label')->nullable();
            $table->string('field_source')->default('default'); // default_document, custom_field
            $table->unsignedBigInteger('custom_field_id')->nullable();
            $table->unsignedInteger('step_number')->nullable();
            $table->string('highlight_field')->nullable();
            $table->string('previous_status')->nullable();
            $table->text('reason');
            $table->string('status')->default('pending'); // pending, resolved, cancelled
            $table->foreignId('requested_by')->nullable()->constrained('university_admins')->nullOnDelete();
            $table->foreignId('resolved_by')->nullable()->constrained('university_admins')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->foreign('custom_field_id')
                ->references('id')
                ->on('custom_application_step_fields')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_reupload_requests');
    }
};

