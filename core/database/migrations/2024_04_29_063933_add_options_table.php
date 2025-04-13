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
        Schema::create('options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->unique();
            $table->string('value')->nullable();
            $table->enum("type",['Basic', 'Core','Website','Genarel','Pos','Others'])->default('Basic');
            $table->integer('is_locked')->default(0);
            $table->timestamps();
            $table->softDeletes(); // Adding soft delete functionality
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};
