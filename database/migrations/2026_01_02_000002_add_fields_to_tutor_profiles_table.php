<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('tutor_profiles', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('bio');
            $table->string('work_email')->nullable()->after('phone');
            $table->string('photo_url')->nullable()->after('work_email');
        });
    }
    public function down(): void {
        Schema::table('tutor_profiles', function (Blueprint $table) {
            $table->dropColumn(['phone','work_email','photo_url']);
        });
    }
};
