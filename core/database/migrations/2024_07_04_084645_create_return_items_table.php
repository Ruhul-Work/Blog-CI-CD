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
        Schema::create('return_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('return_id');
            $table->text('return_number');
            $table->bigInteger('product_id');
            $table->json('category_id')->nullable();
            $table->json('subcategory_id')->nullable();
            $table->json('author_id')->nullable();
            $table->json('seller_id')->nullable();
            $table->bigInteger('publisher_id')->nullable();
            $table->integer('qty')->notNullable()->comment('return qty');
            $table->double('price', 8, 2)->notNullable();
            $table->double('total', 8, 2)->notNullable();
            $table->tinyInteger('status');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_items');
    }
};
