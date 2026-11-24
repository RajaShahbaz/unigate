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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payer_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('package_id')->nullable()->references('id')->on('packages')->onDelete('set null');
            $table->foreignId('application_package_id')->nullable()->references('id')->on('application_packages')->onDelete('set null');
            $table->unsignedBigInteger('bill_id')->nullable()->comment('Bill ID (if applicable)');
            $table->string('trx_id')->unique()->comment('Transaction Reference Number');
            $table->date('date');
            $table->decimal('paid_amount', 10, 2)->comment('Amount paid after tax deduction');
            $table->decimal('tax_paid', 10, 2)->default(0)->comment('Tax amount paid');
            $table->string('payment_method')->nullable()->comment('Payment method (e.g., MWALLET, CARD)');
            $table->string('transaction_date')->nullable()->comment('Transaction date from JazzCash');
            $table->string('transaction_expiry_date')->nullable()->comment('Transaction expiry date');
            $table->string('transaction_status')->nullable()->comment('Response code from JazzCash (999 = success)');
            $table->string('currency', 3)->default('PKR')->comment('Transaction currency');
            $table->string('bill_reference')->nullable()->comment('Bill reference number');
            $table->string('retreival_reference_no')->nullable()->comment('Retrieval reference number');
            $table->string('mobile_no')->nullable()->comment('Mobile number used for payment');
            $table->string('CNIC')->nullable()->comment('CNIC used for payment');
            $table->text('message')->nullable()->comment('Response message from JazzCash');
            $table->decimal('discounted_amount', 10, 2)->nullable()->comment('Discounted amount if any');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better query performance
            $table->index('payer_id');
            $table->index('trx_id');
            $table->index('transaction_status');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
