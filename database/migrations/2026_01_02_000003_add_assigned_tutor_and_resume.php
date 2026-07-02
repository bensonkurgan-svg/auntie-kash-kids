<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('assigned_tutor_id')->nullable()->after('age')
                  ->constrained('tutor_profiles')->nullOnDelete();
        });
        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreignId('last_lesson_id')->nullable()->after('progress')
                  ->constrained('lessons')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('students', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_tutor_id');
        });
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('last_lesson_id');
        });
    }
};
