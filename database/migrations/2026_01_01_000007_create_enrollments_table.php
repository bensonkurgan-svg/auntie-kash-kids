<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['ACTIVE','COMPLETED','CANCELLED','PENDING_PAYMENT'])->default('ACTIVE');
            $table->decimal('progress', 5, 2)->default(0);
            $table->string('stripe_payment_id')->nullable();
            $table->timestamps();
            $table->unique(['student_id', 'course_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('enrollments'); }
};
