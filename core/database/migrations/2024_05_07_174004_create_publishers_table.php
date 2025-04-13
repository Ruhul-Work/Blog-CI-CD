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
        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false); // Making name field required
            $table->text('icon')->nullable();
            $table->text('cover_image')->nullable();
            $table->longText('description')->nullable();
            $table->string('meta_title')->nullable()->comment('for seo');
            $table->longText('meta_description')->nullable()->comment('for seo');
            $table->string('meta_image')->nullable()->comment('for seo');
            $table->boolean('status')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->comment('Auth id');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Auth id');
            $table->unsignedBigInteger('deleted_by')->nullable()->comment('Auth id');
            $table->text('slug')->nullable();
            $table->softDeletes(); // Adding soft delete column
            $table->timestamps();
            $table->index(['id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publishers');
    }
};
