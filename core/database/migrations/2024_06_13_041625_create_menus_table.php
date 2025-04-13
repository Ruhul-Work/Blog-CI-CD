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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('sub_menu_id')->nullable();
            $table->integer('child_id')->nullable();
            $table->enum('menu_type', ['Mega', 'General', 'Sub_Menu'])->nullable();
            $table->string('link')->nullable();
            $table->integer('sort_order')->nullable();
            $table->integer('status')->nullable();
            $table->text('icon')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
