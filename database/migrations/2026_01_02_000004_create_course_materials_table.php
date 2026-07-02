<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('course_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('module_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['DOCUMENT','VIDEO_LINK','IMAGE','LINK'])->default('DOCUMENT');
            $table->string('file_path')->nullable();   // stored on Railway volume
            $table->string('external_url')->nullable(); // for video/links
            $table->enum('status', ['UNDER_REVIEW','APPROVED','REJECTED'])->default('UNDER_REVIEW');
            $table->text('review_note')->nullable();
            $table->foreignId('submitted_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('course_materials'); }
};
