<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('meeting_platform')->nullable()->after('is_published'); // ZOOM / MEET
            $table->string('meeting_link')->nullable()->after('meeting_platform');
            $table->string('meeting_schedule')->nullable()->after('meeting_link'); // e.g. "Tue & Thu 5pm"
        });
    }
    public function down(): void {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['meeting_platform','meeting_link','meeting_schedule']);
        });
    }
};
