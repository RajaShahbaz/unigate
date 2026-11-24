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
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('type', ['public', 'private', 'semi-government'])->default('private');
            $table->year('established_year')->nullable();
            $table->text('facilities')->nullable(); // JSON or comma separated
            $table->decimal('ranking', 5, 2)->nullable();
            $table->decimal('tuition_fee_range_min', 10, 2)->nullable();
            $table->decimal('tuition_fee_range_max', 10, 2)->nullable();
            $table->boolean('hostel_available')->default(false);
            $table->boolean('scholarship_available')->default(false);
            $table->text('admission_criteria')->nullable();
            $table->string('accreditation')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('universities');
    }
};

