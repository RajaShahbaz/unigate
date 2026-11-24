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
        Schema::create('custom_application_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->onDelete('cascade');
            $table->integer('step_number');
            $table->string('step_title');
            $table->text('step_description')->nullable();
            $table->string('step_icon')->default('file-alt');
            $table->integer('display_order')->default(0);
            $table->boolean('is_required')->default(true);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['university_id', 'step_number']);
        });

        Schema::create('custom_application_step_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_application_step_id');
            $table->string('field_name');
            $table->string('field_type'); // text, email, tel, date, number, select, textarea, file, radio, checkbox
            $table->string('field_label');
            $table->text('field_placeholder')->nullable();
            $table->text('field_options')->nullable(); // JSON for select/radio/checkbox options
            $table->boolean('is_required')->default(false);
            $table->string('validation_rules')->nullable();
            $table->string('field_icon')->nullable();
            $table->text('helper_text')->nullable();
            $table->integer('min_value')->nullable();
            $table->integer('max_value')->nullable();
            $table->string('accept_types')->nullable(); // For file uploads
            $table->integer('max_file_size')->nullable(); // In KB
            $table->integer('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            // Add foreign key with shorter name
            $table->foreign('custom_application_step_id', 'custom_step_fields_step_fk')
                  ->references('id')
                  ->on('custom_application_steps')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_application_step_fields');
        Schema::dropIfExists('custom_application_steps');
    }
};

