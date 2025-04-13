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
        Schema::create('package_order_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->foreignId('subscription_order_id')->constrained('subscription_orders')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('method_id');
            $table->string('method_name')->nullable();
            $table->string('transaction_id');
            $table->string('payment_id')->nullable();
            $table->string('customerMsisdn')->nullable();
            $table->double('amount')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_order_transactions');
    }
};
