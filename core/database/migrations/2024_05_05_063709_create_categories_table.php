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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('icon');
            $table->text('cover_image')->nullable();
            $table->longText('description')->nullable();

            $table->string('meta_title')->nullable()->comment('for seo');
            $table->longText('meta_description')->nullable()->comment('for seo');
            $table->string('meta_image')->nullable()->comment('for seo');

            $table->boolean('status')->nullable();

            $table->integer('created_by')->nullable()->comment('Auth id');
            $table->integer('updated_by')->nullable()->comment('Auth id');
            $table->integer('deleted_by')->nullable()->comment('Auth id');
            $table->string('slug', 191)->unique()->nullable(false);


            $table->timestamps();
            $table->fullText('name');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
