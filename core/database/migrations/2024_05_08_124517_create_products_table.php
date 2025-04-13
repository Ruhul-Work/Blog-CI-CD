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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('english_name');
            $table->string('bangla_name');
            $table->text('searchable_data');
            $table->string('slug')->unique();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('isBundle')->default(0);
            $table->string('product_type')->nullable();
            $table->unsignedBigInteger('publisher_id')->nullable();
            $table->date('published_year')->nullable();
            $table->string('edition')->nullable();
            $table->integer('pages_no')->nullable();
            $table->string('cover_type')->nullable();
            $table->float('weight')->nullable();
            $table->string('isbn')->nullable();
            $table->string('product_code')->nullable()->unique();
            $table->text('language')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->decimal('mrp_price', 10, 2)->nullable();
            $table->decimal('current_price', 10, 2)->nullable();
            $table->enum('discount_type', ['percentage', 'amount'])->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->tinyInteger('show_discount')->default(0);
            $table->integer('stock')->nullable();
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'upcoming'])->default('in_stock');
            $table->integer('sale_count')->nullable();
            $table->integer('stock_requests')->nullable();
            $table->string('thumb_image')->nullable();
            $table->string('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->string('meta_image')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            //$table->fullText(['searchable_data','bangla_name','short_description','description']);
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::create('product_subcategory', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subcategory_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });


//
//        Schema::create('product_subject', function (Blueprint $table) {
//            $table->id();
//            $table->unsignedBigInteger('subject_id');
//            $table->unsignedBigInteger('product_id');
//            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
//            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
//        });

        Schema::create('author_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->text('pages_photos')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
