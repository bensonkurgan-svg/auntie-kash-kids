<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        // Student: emergency contact + extra profile detail
        Schema::table('students', function (Blueprint $table) {
            $table->string('grade_level')->nullable()->after('age');
            $table->string('emergency_contact_name')->nullable()->after('grade_level');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_phone');
            $table->text('medical_notes')->nullable()->after('emergency_contact_relationship');
        });
        // Tutor profile: qualifications, country, availability
        Schema::table('tutor_profiles', function (Blueprint $table) {
            $table->string('qualifications')->nullable()->after('bio');
            $table->string('country')->nullable()->after('qualifications');
            $table->string('availability')->nullable()->after('country');
        });
    }
    public function down(): void {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['grade_level','emergency_contact_name','emergency_contact_phone','emergency_contact_relationship','medical_notes']);
        });
        Schema::table('tutor_profiles', function (Blueprint $table) {
            $table->dropColumn(['qualifications','country','availability']);
        });
    }
};
