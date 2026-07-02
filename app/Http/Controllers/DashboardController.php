<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\CmsChangeRequest;
use App\Models\DiscoveryForm;
use App\Models\ContentPost;
use App\Models\Student;
use App\Models\TutorProfile;

class DashboardController extends Controller
{
    public function ceo()
    {
        // ── Headline stats ────────────────────────────────────────────────
        $stats = [
            'users'           => User::count(),
            'parents'         => User::where('role','PARENT')->count(),
            'students'        => Student::count(),
            'tutors'          => User::where('role','TUTOR')->count(),
            'courses'         => Course::where('is_published',true)->count(),
            'enrollments'     => Enrollment::where('status','ACTIVE')->count(),
            'completed'       => Enrollment::where('status','COMPLETED')->count(),
            'discoveryTotal'  => DiscoveryForm::count(),
            'discoveryNew'    => DiscoveryForm::where('status','NEW')->count(),
            'pendingApprovals'=> CmsChangeRequest::where('status','PENDING')->count(),
            'publishedContent'=> ContentPost::where('status','PUBLISHED')->count(),
        ];

        // ── Estimated monthly revenue (active enrollments × course price) ──
        $stats['revenue'] = Enrollment::where('enrollments.status','ACTIVE')
            ->join('courses','courses.id','=','enrollments.course_id')
            ->sum('courses.price');

        // ── Avg progress across all active enrollments ────────────────────
        $stats['avgProgress'] = round((float) Enrollment::where('status','ACTIVE')->avg('progress'), 1);

        // ── Users table: everyone + what they're doing ────────────────────
        $users = User::withCount(['students','enrollments'])
            ->orderByRaw("FIELD(role,'CEO','ADMIN','TUTOR','PARENT','STUDENT')")
            ->orderBy('name')
            ->get()
            ->map(function ($u) {
                $u->activity = match($u->role) {
                    'PARENT'  => $u->enrollments_count.' enrolment(s), '.$u->students_count.' child(ren)',
                    'TUTOR'   => optional($u->tutorProfile)->review_count.' reviews · '.optional($u->tutorProfile)->rating.'★',
                    'CEO','ADMIN' => 'Platform management',
                    default   => 'Student account',
                };
                return $u;
            });

        // ── Tutor → student progress overview ─────────────────────────────
        $tutorProgress = TutorProfile::with(['user','courses.enrollments.student'])
            ->get()
            ->map(function ($tp) {
                $enrollments = $tp->courses->flatMap->enrollments;
                return (object) [
                    'name'        => $tp->user->name ?? 'Tutor',
                    'specialties' => $tp->specialties ?? [],
                    'rating'      => $tp->rating,
                    'reviewCount' => $tp->review_count,
                    'courseCount' => $tp->courses->count(),
                    'studentCount'=> $enrollments->count(),
                    'avgProgress' => $enrollments->count() ? round($enrollments->avg('progress'), 0) : 0,
                ];
            });

        // ── Per-course enrollment analytics ───────────────────────────────
        $courseStats = Course::withCount(['enrollments'])
            ->with('tutorProfile.user')
            ->orderByDesc('enrollments_count')
            ->get()
            ->map(fn($c) => (object)[
                'title'       => $c->title,
                'icon'        => $c->icon,
                'tutor'       => optional(optional($c->tutorProfile)->user)->name ?? '—',
                'students'    => $c->enrollments_count,
                'avgProgress' => round((float) $c->enrollments()->avg('progress'), 0),
                'revenue'     => $c->enrollments()->where('status','ACTIVE')->count() * $c->price,
            ]);

        $recentEnrollments = Enrollment::with(['student','course','user'])->latest()->take(8)->get();
        $pendingRequests   = CmsChangeRequest::with('requester')->where('status','PENDING')->latest()->get();

        // Course materials awaiting CEO review
        $pendingMaterials = \App\Models\CourseMaterial::with(['course','submitter'])
            ->where('status','UNDER_REVIEW')->latest()->get();

        // Discovery pipeline with staff assignment + assignable staff list
        $discoveryForms = DiscoveryForm::with('assignedStaff')->latest()->take(20)->get();
        $assignableStaff = User::whereIn('role', ['CEO','ADMIN','TUTOR'])
            ->where('is_active', true)->orderBy('name')->get(['id','name','role']);

        return view('dashboard.ceo', compact(
            'stats','users','tutorProgress','courseStats','recentEnrollments','pendingRequests','pendingMaterials','discoveryForms','assignableStaff'
        ));
    }

    public function admin()
    {
        // Admin = operations: content moderation, discovery pipeline, enrolments.
        // (No revenue / strategic financials — that's CEO-only.)
        $stats = [
            'users'         => User::count(),
            'students'      => Student::count(),
            'courses'       => Course::count(),
            'pendingReviews'=> CmsChangeRequest::where('status','PENDING')->count(),
            'newDiscovery'  => DiscoveryForm::where('status','NEW')->count(),
            'contactedDisc' => DiscoveryForm::where('status','CONTACTED')->count(),
            'enrolledDisc'  => DiscoveryForm::where('status','ENROLLED')->count(),
            'draftContent'  => ContentPost::where('status','DRAFT')->count(),
            'publishedContent' => ContentPost::where('status','PUBLISHED')->count(),
        ];

        // Discovery pipeline — full submissions with their details
        $discoveryForms = DiscoveryForm::with('assignedStaff')->latest()->take(30)->get();

        // Staff assignable to discovery calls (Admin, CEO, Tutor)
        $assignableStaff = User::whereIn('role', ['CEO','ADMIN','TUTOR'])
            ->where('is_active', true)->orderBy('name')->get(['id','name','role']);

        // Content library overview (what's published / drafts)
        $contentPosts = ContentPost::with('author')
            ->latest()->take(15)->get();

        // Pending content/CMS change requests to action
        $recentRequests = CmsChangeRequest::with('requester')
            ->where('status','PENDING')->latest()->get();

        // Recent parent signups to welcome/follow up
        $recentParents = User::where('role','PARENT')
            ->withCount('students')->latest()->take(8)->get();

        return view('dashboard.admin', compact(
            'stats','discoveryForms','contentPosts','recentRequests','recentParents','assignableStaff'
        ));
    }

    public function tutor()
    {
        $profile = TutorProfile::with(['courses.enrollments.student','reviews.user'])
            ->where('user_id', Auth::id())->first();
        $totalStudents = $profile ? $profile->courses->sum(fn($c) => $c->enrollments->count()) : 0;

        // Per-student progress across this tutor's courses
        $studentProgress = collect();
        if ($profile) {
            foreach ($profile->courses as $course) {
                foreach ($course->enrollments as $e) {
                    $studentProgress->push((object)[
                        'student'  => $e->student->name ?? '—',
                        'course'   => $course->title,
                        'progress' => $e->progress,
                        'status'   => $e->status,
                    ]);
                }
            }
        }

        // This tutor's material submissions + their statuses
        $mySubmissions = \App\Models\CourseMaterial::with('course')
            ->where('submitted_by', Auth::id())->latest()->get();

        return view('dashboard.tutor', compact('profile','totalStudents','studentProgress','mySubmissions'));
    }

    public function parent()
    {
        $students = Student::with(['enrollments' => fn($q) => $q->where('status','ACTIVE')->with('course')])
            ->where('parent_id', Auth::id())->get();
        $activeCourses = $students->sum(fn($s) => $s->enrollments->count());

        // Upcoming class sessions (with join links) for this parent's children
        $studentIds = $students->pluck('id');
        $upcomingSessions = \App\Models\ClassSession::with(['course','tutor','student'])
            ->whereIn('student_id', $studentIds)
            ->where('scheduled_at', '>=', now()->subHours(1))
            ->orderBy('scheduled_at')->take(10)->get();

        return view('dashboard.parent', compact('students','activeCourses','upcomingSessions'));
    }

    public function student()
    {
        return view('dashboard.student');
    }
}
