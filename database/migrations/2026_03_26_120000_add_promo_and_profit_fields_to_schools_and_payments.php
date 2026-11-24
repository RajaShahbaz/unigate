<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', static function (Blueprint $table) {
            $table->string('promo_code')->nullable()->unique()->after('domain');
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('promo_code');
            $table->decimal('profit_percentage', 5, 2)->default(0)->after('discount_percentage');
        });

        Schema::table('payments', static function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->after('payer_id')->constrained('schools')->nullOnDelete();
            $table->string('promo_code')->nullable()->after('CNIC');
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('discounted_amount');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_percentage');
            $table->decimal('profit_percentage', 5, 2)->default(0)->after('discount_amount');
            $table->decimal('profit_amount', 10, 2)->default(0)->after('profit_percentage');
        });
    }

    public function down(): void
    {
        Schema::table('payments', static function (Blueprint $table) {
            $table->dropConstrainedForeignId('school_id');
            $table->dropColumn([
                'promo_code',
                'discount_percentage',
                'discount_amount',
                'profit_percentage',
                'profit_amount',
            ]);
        });

        Schema::table('schools', static function (Blueprint $table) {
            $table->dropUnique(['promo_code']);
            $table->dropColumn(['promo_code', 'discount_percentage', 'profit_percentage']);
        });
    }
};

