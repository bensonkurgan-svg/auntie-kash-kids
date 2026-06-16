<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use App\Models\Lesson;

class LessonController extends Controller
{
    public function complete(Request $request)
    {
        $data = $request->validate([
            'lesson_id' => ['required','exists:lessons,id'],
            'enrollment_id' => ['required','exists:enrollments,id'],
        ]);

        LessonCompletion::firstOrCreate([
            'lesson_id' => $data['lesson_id'],
            'enrollment_id' => $data['enrollment_id'],
        ], ['completed_at' => now()]);

        // Recalculate progress
        $enrollment = Enrollment::with('course.modules.lessons')->find($data['enrollment_id']);
        $totalLessons = $enrollment->course->modules->sum(fn($m) => $m->lessons->count());
        $completed = LessonCompletion::where('enrollment_id', $enrollment->id)->count();
        $progress = $totalLessons > 0 ? round(($completed / $totalLessons) * 100, 2) : 0;
        $enrollment->update(['progress' => $progress, 'status' => $progress >= 100 ? 'COMPLETED' : 'ACTIVE']);

        return response()->json(['success' => true, 'progress' => $progress]);
    }
}
