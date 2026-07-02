<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\ClassSession;
use App\Models\Course;
use App\Models\Student;
use App\Models\TutorProfile;

class ClassroomController extends Controller
{
    /** Tutor classroom: their courses, assignments, scoreboard, attendance. */
    public function index()
    {
        $user = Auth::user();
        $profile = TutorProfile::with('courses')->where('user_id', $user->id)->first();
        $courseIds = $profile ? $profile->courses->pluck('id') : collect();

        $assignments = Assignment::with(['course','submissions.student'])
            ->where('tutor_id', $user->id)->latest()->get();

        // Scoreboard: graded submissions ranked by score
        $scoreboard = AssignmentSubmission::with(['student','assignment.course'])
            ->whereHas('assignment', fn($q) => $q->where('tutor_id', $user->id))
            ->where('status', 'GRADED')
            ->orderByDesc('score')->take(20)->get();

        $sessions = ClassSession::with(['student','course'])
            ->where('tutor_id', $user->id)->latest()->take(20)->get();

        $myCourses = $profile ? $profile->courses : collect();
        $myStudents = Student::whereHas('enrollments', fn($q) => $q->whereIn('course_id', $courseIds))
            ->get();

        return view('dashboard.classroom', compact(
            'assignments','scoreboard','sessions','myCourses','myStudents'
        ));
    }

    public function storeAssignment(Request $request)
    {
        if (! Auth::user()->isTutor() && ! Auth::user()->canManageContent()) abort(403);
        $data = $request->validate([
            'course_id'    => ['required','exists:courses,id'],
            'title'        => ['required','string','max:255'],
            'instructions' => ['nullable','string','max:2000'],
            'max_score'    => ['required','integer','min:1','max:1000'],
            'due_date'     => ['nullable','date'],
        ]);
        $data['tutor_id'] = Auth::id();
        Assignment::create($data);
        return back()->with('success', 'Assignment created.');
    }

    public function grade(Request $request, AssignmentSubmission $submission)
    {
        if (! Auth::user()->isTutor() && ! Auth::user()->canManageContent()) abort(403);
        $data = $request->validate([
            'score'    => ['required','integer','min:0'],
            'feedback' => ['nullable','string','max:1000'],
        ]);
        $submission->update([
            'score' => $data['score'],
            'feedback' => $data['feedback'] ?? null,
            'status' => 'GRADED',
            'graded_at' => now(),
        ]);
        return back()->with('success', 'Submission graded.');
    }

    /** Tutor/CEO/Admin sets the persistent meeting room for a program. */
    public function saveMeetingRoom(Request $request)
    {
        if (! Auth::user()->isTutor() && ! Auth::user()->canManageContent()) abort(403);
        $data = $request->validate([
            'course_id'        => ['required','exists:courses,id'],
            'meeting_platform' => ['nullable','in:ZOOM,MEET'],
            'meeting_link'     => ['nullable','url'],
            'meeting_schedule' => ['nullable','string','max:255'],
        ]);
        \App\Models\Course::where('id', $data['course_id'])->update([
            'meeting_platform' => $data['meeting_platform'] ?? null,
            'meeting_link'     => $data['meeting_link'] ?? null,
            'meeting_schedule' => $data['meeting_schedule'] ?? null,
        ]);
        return back()->with('success', 'Meeting room saved for this program.');
    }

    public function logAttendance(Request $request)
    {
        if (! Auth::user()->isTutor() && ! Auth::user()->canManageContent()) abort(403);
        $data = $request->validate([
            'course_id'  => ['required','exists:courses,id'],
            'student_id' => ['required','exists:students,id'],
            'status'     => ['required','in:PRESENT,ABSENT,COMPLETED'],
            'duration_minutes' => ['nullable','integer','min:0'],
            'notes'      => ['nullable','string','max:500'],
            'meeting_platform' => ['nullable','in:ZOOM,MEET'],
            'meeting_link' => ['nullable','url'],
        ]);
        $data['tutor_id'] = Auth::id();
        $data['scheduled_at'] = now();
        ClassSession::create($data);
        return back()->with('success', 'Session logged.');
    }
}
