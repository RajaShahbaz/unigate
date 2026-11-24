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
        Schema::table('university_interests', function (Blueprint $table) {
            // Add fee range columns to support filtering by fee range
            $table->decimal('fee_min', 10, 2)->nullable()->after('fee');
            $table->decimal('fee_max', 10, 2)->nullable()->after('fee_min');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('university_interests', function (Blueprint $table) {
            $table->dropColumn(['fee_min', 'fee_max']);
        });
    }
};
