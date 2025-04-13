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
        Schema::create('orders_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->text('order_number');
            $table->unsignedBigInteger('product_id');
            $table->json('category_id');
            $table->json('subcategory_id');
            $table->json('author_id');
            $table->json('seller_id')->nullable();
            $table->unsignedBigInteger('publisher_id');
            $table->integer('qty')->notNullable()->comment('order qty');
            $table->double('price', 8, 2)->notNullable();
            $table->double('total', 8, 2)->notNullable();
            $table->unsignedInteger('campaign_id')->nullable();
            $table->tinyInteger('status');
            $table->timestamps();
            $table->softDeletes();
            // Foreign key constraints
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders_items', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['product_id']);

        });
    }
};
