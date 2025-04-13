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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->enum('stock_type', ['initial', 'adjustment', 'sale', 'purchase', 'return']);
            $table->date('stock_entry_date');
            $table->integer('order_id')->nullable();
            $table->integer('return_id')->nullable();
            $table->integer('purchase_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->decimal('item_price', 8, 2)->default(0.00);
            $table->integer('item_qty');
            $table->decimal('item_discount', 8, 2)->nullable();
            $table->decimal('item_subtotal', 8, 2);
            $table->string('item_description');
            $table->tinyInteger('is_bundle_item')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
