<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\DiscountEnum;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->notNullable();
            $table->text('slug');
            $table->double('discount', 10, 2)->nullable();
            $table->enum('discount_type', array_column(DiscountEnum::cases(), 'value'));
            $table->text('icon')->nullable();
            $table->text('cover_image')->nullable();
            $table->text('notes')->nullable();
            $table->integer('status');
            $table->boolean('is_featured');
            $table->string('meta_title')->nullable()->comment('for seo');
            $table->text('meta_description')->nullable()->comment('for seo');
            $table->text('meta_image')->nullable()->comment('for seo');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
