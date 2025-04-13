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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('purchase_id');
            $table->text('purchase_number');
            $table->unsignedBigInteger('product_id');
            $table->json('category_id')->nullable();
            $table->json('subcategory_id')->nullable();
            $table->json('author_id')->nullable();
            $table->json('seller_id')->nullable();
            $table->unsignedBigInteger('publisher_id')->nullable();
            $table->integer('qty')->notNullable()->comment('purchase qty');
            $table->double('price', 8, 2)->notNullable();
            $table->double('total', 8, 2)->notNullable();
            $table->tinyInteger('status');
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
