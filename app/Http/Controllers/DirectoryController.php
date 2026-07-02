<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\User;
use App\Models\Course;
use App\Models\TutorProfile;
use App\Models\ClassSession;

class DirectoryController extends Controller
{
    /** CEO/Admin: full student directory with filters. */
    public function students(Request $request)
    {
        $this->authorizeStaff();

        $courseId = $request->integer('course');
        $tutorId  = $request->integer('tutor');

        $students = Student::with([
                'parent',
                'assignedTutor.user',
                'enrollments.course',
            ])
            ->when($courseId, fn($q) => $q->whereHas('enrollments', fn($e) => $e->where('course_id', $courseId)))
            ->when($tutorId, fn($q) => $q->where('assigned_tutor_id', $tutorId))
            ->orderBy('name')
            ->get();

        $courses = Course::orderBy('title')->get(['id','title','icon']);
        $tutors  = TutorProfile::with('user')->get();

        return view('dashboard.directory-students', compact('students','courses','tutors','courseId','tutorId'));
    }

    /** Single student detail (progress, contacts, history). */
    public function studentDetail(Student $student)
    {
        $this->authorizeStaff();
        $student->load([
            'parent',
            'assignedTutor.user',
            'enrollments.course',
            'enrollments.lastLesson',
        ]);

        // Attendance/session history
        $sessions = ClassSession::with(['course','tutor'])
            ->where('student_id', $student->id)
            ->latest()->take(30)->get();

        return view('dashboard.directory-student-detail', compact('student','sessions'));
    }

    /** CEO/Admin: instructor directory with full info. */
    public function instructors(Request $request)
    {
        $this->authorizeStaff();

        $instructors = User::where('role', 'TUTOR')
            ->with(['tutorProfile.courses'])
            ->orderBy('name')
            ->get();

        return view('dashboard.directory-instructors', compact('instructors'));
    }

    private function authorizeStaff(): void
    {
        if (! Auth::user()->canManageContent()) abort(403);
    }
}
