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
        Schema::create('firewall', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ip_address', 100)->unique();
            $table->enum("type",['Black_listed', 'White_listed'])->default('Black_listed');
            $table->string('comments')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Adding soft delete functionality
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firewall');
    }
};
