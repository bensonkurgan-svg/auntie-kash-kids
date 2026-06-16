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
        $stats = [
            'users' => User::count(),
            'courses' => Course::where('is_published',true)->count(),
            'enrollments' => Enrollment::where('status','ACTIVE')->count(),
            'discoveryTotal' => DiscoveryForm::count(),
            'discoveryNew' => DiscoveryForm::where('status','NEW')->count(),
            'pendingApprovals' => CmsChangeRequest::where('status','PENDING')->count(),
            'publishedContent' => ContentPost::where('status','PUBLISHED')->count(),
        ];
        $recentEnrollments = Enrollment::with(['student','course','user'])->latest()->take(5)->get();
        $pendingRequests = CmsChangeRequest::with('requester')->where('status','PENDING')->latest()->get();
        return view('dashboard.ceo', compact('stats','recentEnrollments','pendingRequests'));
    }

    public function admin()
    {
        $stats = [
            'users' => User::count(),
            'courses' => Course::count(),
            'pendingReviews' => CmsChangeRequest::where('status','PENDING')->count(),
            'newDiscovery' => DiscoveryForm::where('status','NEW')->count(),
        ];
        $discoveryForms = DiscoveryForm::latest()->take(20)->get();
        $recentRequests = CmsChangeRequest::with('requester')->latest()->take(5)->get();
        return view('dashboard.admin', compact('stats','discoveryForms','recentRequests'));
    }

    public function tutor()
    {
        $profile = TutorProfile::with(['courses.enrollments','reviews.user'])
            ->where('user_id', Auth::id())->first();
        $totalStudents = $profile ? $profile->courses->sum(fn($c) => $c->enrollments->count()) : 0;
        return view('dashboard.tutor', compact('profile','totalStudents'));
    }

    public function parent()
    {
        $students = Student::with(['enrollments' => fn($q) => $q->where('status','ACTIVE')->with('course')])
            ->where('parent_id', Auth::id())->get();
        $activeCourses = $students->sum(fn($s) => $s->enrollments->count());
        return view('dashboard.parent', compact('students','activeCourses'));
    }

    public function student()
    {
        return view('dashboard.student');
    }
}
