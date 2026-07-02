<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('content_posts', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['BLOG_POST','LIBRARY_ITEM','PARENT_RESOURCE']);
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body');
            $table->string('cover_image')->nullable();
            $table->json('tags')->nullable();
            $table->enum('status', ['DRAFT','PENDING_REVIEW','PUBLISHED','ARCHIVED'])->default('DRAFT');
            $table->string('file_url')->nullable();
            $table->string('file_type')->nullable();
            $table->string('age_range')->nullable();
            $table->string('read_time')->nullable();
            $table->string('category')->nullable();
            $table->boolean('featured')->default(false);
            $table->foreignId('author_id')->constrained('users');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('content_posts'); }
};
