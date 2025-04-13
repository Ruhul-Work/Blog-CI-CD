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
        Schema::create('subscription_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_package_id')->constrained('subscription_packages')->onDelete('cascade');
            $table->double('package_price')->nullable();
            $table->double('discount')->nullable();
            $table->bigInteger('coupon_id')->nullable();
            $table->decimal('coupon_discount', 10, 2)->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->integer('quantity');
            $table->decimal('total', 10, 2);
            $table->string('pay_method', 191)->nullable();
            $table->double('pay_amount')->nullable();
            $table->enum('payment_status', ['Pending', 'Partial', 'Paid'])->default('Pending')->change();
            $table->datetime('subscription_start_date');
            $table->datetime('end_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_orders');
    }
};
