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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->unsignedBigInteger('user_id');

            $table->text('customer_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->date('sale_date')->nullable();
            $table->decimal('adjust_amount', 8, 2)->default(0);
            $table->decimal('packing_charge', 8, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->decimal('coupon_discount', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping_charge', 10, 2)->default(0);
            $table->integer('quantity')->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('order_type', ['wholesale', 'retail'])->nullable();
            $table->string('payment_status');
            $table->string('source');
            $table->string('packaged_status')->nullable();
            $table->timestamp('packaged_at')->nullable();
            $table->string('packaged_by')->nullable();
            $table->unsignedBigInteger('packager_id')->nullable();
            $table->string('ordered_by')->nullable();
            $table->unsignedBigInteger('courier_id')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('order_status_id');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Add soft delete functionality
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
