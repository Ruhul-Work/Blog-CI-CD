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
        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('title', 191); 
            $table->string('slug', 191)->unique(); 
            $table->longText('content'); 
            $table->integer('author_id')->unsigned(); 
            $table->bigInteger('user_id')->unsigned(); 
            $table->tinyInteger('blog_type')->default(0); 
            $table->decimal('price', 10, 2)->default(0.00); 
            $table->integer('total_views')->default(0); 
            $table->integer('likes_count')->default(0); 
            $table->integer('comments_count')->default(0); 
            $table->integer('share_counts')->nullable(); 
            $table->enum('publish_status', ['draft', 'published'])->default('draft'); 
            $table->timestamp('published_at')->nullable(); 
            $table->integer('read_count')->nullable(); 
            $table->tinyInteger('allow_comments')->default(1)->nullable(); 
            $table->string('thumbnail', 191)->nullable(); 
            $table->text('meta_title')->nullable(); 
            $table->text('meta_description')->nullable(); 
            $table->longText('meta_keywords')->nullable();
            $table->longText('liked_by')->nullable();
            $table->string('meta_image', 191)->nullable(); 
            $table->tinyInteger('status')->default(1); 
            $table->tinyInteger('is_featured')->default(0); 
            $table->tinyInteger('printable')->default(1); 
            $table->bigInteger('created_by')->unsigned(); 
            $table->bigInteger('updated_by')->unsigned()->nullable(); 
            $table->bigInteger('deleted_by')->unsigned()->nullable(); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
