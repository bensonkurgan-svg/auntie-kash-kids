<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use App\Models\Lesson;

class LessonController extends Controller
{
    /** Learning view for an enrolment — resumes at last lesson. */
    public function learn(Enrollment $enrollment, ?Lesson $lesson = null)
    {
        $this->authorizeEnrollment($enrollment);
        $enrollment->load(['course.modules.lessons','course.materials','completions','student']);

        $allLessons = $enrollment->course->modules->flatMap->lessons;
        $current = $lesson
            ?? ($enrollment->last_lesson_id ? $allLessons->firstWhere('id', $enrollment->last_lesson_id) : null)
            ?? $allLessons->first();

        if ($current) {
            $enrollment->update(['last_lesson_id' => $current->id]);
        }

        $completedIds = $enrollment->completions->pluck('lesson_id')->all();

        return view('pages.learn', compact('enrollment','current','completedIds','allLessons'));
    }

    public function complete(Request $request)
    {
        $data = $request->validate([
            'lesson_id' => ['required','exists:lessons,id'],
            'enrollment_id' => ['required','exists:enrollments,id'],
        ]);

        $enrollment = Enrollment::with('course.modules.lessons')->findOrFail($data['enrollment_id']);
        $this->authorizeEnrollment($enrollment);

        LessonCompletion::firstOrCreate([
            'lesson_id' => $data['lesson_id'],
            'enrollment_id' => $data['enrollment_id'],
        ], ['completed_at' => now()]);

        $totalLessons = $enrollment->course->modules->sum(fn($m) => $m->lessons->count());
        $completed = LessonCompletion::where('enrollment_id', $enrollment->id)->count();
        $progress = $totalLessons > 0 ? round(($completed / $totalLessons) * 100, 2) : 0;
        $enrollment->update([
            'progress' => $progress,
            'last_lesson_id' => $data['lesson_id'],
            'status' => $progress >= 100 ? 'COMPLETED' : 'ACTIVE',
        ]);

        return response()->json(['success' => true, 'progress' => $progress]);
    }

    private function authorizeEnrollment(Enrollment $enrollment): void
    {
        $user = Auth::user();
        if (! $user) abort(401);
        $owns = $enrollment->user_id === $user->id;
        $isStaff = in_array($user->role, ['CEO','ADMIN','TUTOR']);
        if (! $owns && ! $isStaff) abort(403);
    }
}
