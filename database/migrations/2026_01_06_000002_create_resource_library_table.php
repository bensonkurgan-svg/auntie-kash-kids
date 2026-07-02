<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('library_resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->enum('audience', ['INSTRUCTOR','PARENT'])->default('INSTRUCTOR');
            $table->enum('content_type', ['DOCUMENT','ARTICLE','LINK','VIDEO'])->default('DOCUMENT');
            $table->text('body')->nullable();          // for articles (rich text)
            $table->string('file_path')->nullable();   // for downloads
            $table->string('external_url')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['audience','category']);
        });
    }
    public function down(): void { Schema::dropIfExists('library_resources'); }
};
