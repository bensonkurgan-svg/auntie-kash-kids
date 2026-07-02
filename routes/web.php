<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscoveryController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\GdprController;

// ─── Public pages ───────────────────────────────────────────────────────────
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/instructors', [PageController::class, 'instructors'])->name('instructors');
Route::get('/courses', [PageController::class, 'courses'])->name('courses');
Route::get('/courses/{slug}', [PageController::class, 'courseDetail'])->name('courses.show');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/blog/library', [PageController::class, 'library'])->name('library');
Route::get('/blog/library/{id}', [PageController::class, 'libraryItem'])->name('library.show');
Route::get('/blog/{slug}', [PageController::class, 'blogPost'])->name('blog.show');
Route::get('/parents/resources', [PageController::class, 'resources'])->name('resources');
Route::get('/parents/resources/{id}', [PageController::class, 'resourceItem'])->name('resources.show');
Route::get('/events', [PageController::class, 'events'])->name('events');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/cookie-policy', [PageController::class, 'cookies'])->name('cookies');

// ─── Discovery form ─────────────────────────────────────────────────────────
Route::get('/discovery', [DiscoveryController::class, 'show'])->name('discovery');
Route::post('/discovery', [DiscoveryController::class, 'store'])->name('discovery.store');

// ─── Auth ───────────────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Stripe ─────────────────────────────────────────────────────────────────
Route::post('/stripe/checkout', [StripeController::class, 'checkout'])->middleware('role:PARENT')->name('stripe.checkout');
Route::post('/stripe/webhook', [StripeController::class, 'webhook'])->name('stripe.webhook');

// ─── Authenticated routes ───────────────────────────────────────────────────
Route::middleware('role')->group(function () {
    // GDPR
    Route::get('/data-export', [GdprController::class, 'exportPage'])->name('data-export');
    Route::post('/data-export', [GdprController::class, 'export']);
    Route::get('/data-delete', [GdprController::class, 'deletePage'])->name('data-delete');
    Route::post('/data-delete', [GdprController::class, 'delete']);

    // Lessons & reviews
    Route::post('/lessons/complete', [LessonController::class, 'complete'])->name('lessons.complete');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// ─── Dashboards ─────────────────────────────────────────────────────────────
Route::get('/dashboard/ceo', [DashboardController::class, 'ceo'])->middleware('role:CEO')->name('dashboard.ceo');
Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->middleware('role:ADMIN')->name('dashboard.admin');
Route::get('/dashboard/tutor', [DashboardController::class, 'tutor'])->middleware('role:TUTOR')->name('dashboard.tutor');
Route::get('/dashboard/parent', [DashboardController::class, 'parent'])->middleware('role:PARENT')->name('dashboard.parent');
Route::get('/dashboard/student', [DashboardController::class, 'student'])->middleware('role:STUDENT')->name('dashboard.student');

// ─── CMS (Admin + CEO) ──────────────────────────────────────────────────────
Route::middleware('role:CEO,ADMIN')->group(function () {
    Route::get('/admin/cms', [CmsController::class, 'editor'])->name('cms.editor');
    Route::post('/admin/cms/submit', [CmsController::class, 'submit'])->name('cms.submit');
    Route::post('/admin/cms/approve', [CmsController::class, 'approve'])->name('cms.approve');
    Route::post('/admin/cms/reject', [CmsController::class, 'reject'])->name('cms.reject');
    Route::post('/discovery/{id}/status', [DiscoveryController::class, 'updateStatus'])->name('discovery.status');
});

// ═══════════════════════════════════════════════════════════════════════════
//  v2 FEATURES
// ═══════════════════════════════════════════════════════════════════════════
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\CourseMaterialController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProfileController;

// ── Forced password change (any authenticated user) ─────────────────────────
Route::middleware('role')->group(function () {
    Route::get('/password/change', [PasswordController::class, 'showChange'])->name('password.change');
    Route::post('/password/change', [PasswordController::class, 'change'])->name('password.change.update');

    // Profile editing (tutor + staff)
    Route::get('/dashboard/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/dashboard/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Continue learning / resume
    Route::get('/learn/{enrollment}/{lesson?}', [LessonController::class, 'learn'])->name('learn');

    // Secure material download (auth required)
    Route::get('/materials/{material}/download', [CourseMaterialController::class, 'download'])->name('materials.download');

    // GDPR exports in multiple formats
    Route::get('/data-export/{format?}', [GdprController::class, 'export'])->name('data-export.download');
});

// ── Tutor material submission ───────────────────────────────────────────────
Route::middleware('role:TUTOR,CEO,ADMIN')->group(function () {
    Route::post('/materials', [CourseMaterialController::class, 'store'])->name('materials.store');
    Route::delete('/materials/{material}', [CourseMaterialController::class, 'destroy'])->name('materials.destroy');
});

// ── Staff management (CEO + Admin, with internal guards) ─────────────────────
Route::middleware('role:CEO,ADMIN')->group(function () {
    Route::get('/dashboard/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::post('/dashboard/staff/tutor', [StaffController::class, 'storeTutor'])->name('staff.tutor.store');
    Route::post('/dashboard/staff/{user}/deactivate', [StaffController::class, 'deactivate'])->name('staff.deactivate');
    Route::post('/dashboard/staff/{user}/reactivate', [StaffController::class, 'reactivate'])->name('staff.reactivate');

    // Blog manager
    Route::get('/dashboard/blog', [BlogController::class, 'index'])->name('blog.manage');
    Route::post('/dashboard/blog', [BlogController::class, 'store'])->name('blog.store');
    Route::put('/dashboard/blog/{post}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/dashboard/blog/{post}', [BlogController::class, 'destroy'])->name('blog.destroy');
});

// ── CEO-only management ─────────────────────────────────────────────────────
Route::middleware('role:CEO')->group(function () {
    Route::post('/dashboard/staff/admin', [StaffController::class, 'storeAdmin'])->name('staff.admin.store');
    Route::delete('/dashboard/staff/{user}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::post('/dashboard/staff/assign-tutor', [StaffController::class, 'assignTutor'])->name('staff.assign');

    // Program manager
    Route::get('/dashboard/programs', [ProgramController::class, 'index'])->name('programs.index');
    Route::post('/dashboard/programs', [ProgramController::class, 'store'])->name('programs.store');
    Route::put('/dashboard/programs/{course}', [ProgramController::class, 'update'])->name('programs.update');
    Route::delete('/dashboard/programs/{course}', [ProgramController::class, 'destroy'])->name('programs.destroy');
    Route::post('/dashboard/programs/{course}/module', [ProgramController::class, 'addModule'])->name('programs.module.add');
    Route::delete('/dashboard/modules/{module}', [ProgramController::class, 'deleteModule'])->name('programs.module.delete');

    // Material review
    Route::post('/materials/{material}/review', [CourseMaterialController::class, 'review'])->name('materials.review');
});

// ── Parent: manage children (self-enroll) ───────────────────────────────────
use App\Http\Controllers\StudentController;
Route::middleware('role:PARENT')->group(function () {
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
});

// ═══════════════════════════════════════════════════════════════════════════
//  v3 FEATURES — Shop, Messaging, Classroom
// ═══════════════════════════════════════════════════════════════════════════
use App\Http\Controllers\ShopController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ClassroomController;

// ── Public shop ─────────────────────────────────────────────────────────────
Route::get('/shop', [ShopController::class, 'index'])->name('shop');

// ── Messaging (all authenticated users) ─────────────────────────────────────
Route::middleware('role')->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::post('/messages/private', [MessageController::class, 'sendPrivate'])->name('messages.private');
    Route::post('/messages/public', [MessageController::class, 'sendPublic'])->name('messages.public');
});

// ── Tutor classroom ─────────────────────────────────────────────────────────
Route::middleware('role:TUTOR,CEO,ADMIN')->group(function () {
    Route::get('/classroom', [ClassroomController::class, 'index'])->name('classroom');
    Route::post('/classroom/assignment', [ClassroomController::class, 'storeAssignment'])->name('classroom.assignment');
    Route::post('/classroom/grade/{submission}', [ClassroomController::class, 'grade'])->name('classroom.grade');
    Route::post('/classroom/attendance', [ClassroomController::class, 'logAttendance'])->name('classroom.attendance');
    Route::post('/classroom/meeting-room', [ClassroomController::class, 'saveMeetingRoom'])->name('classroom.meeting');
});

// ── Shop management (CEO + Admin) ───────────────────────────────────────────
Route::middleware('role:CEO,ADMIN')->group(function () {
    Route::get('/dashboard/shop', [ShopController::class, 'manage'])->name('shop.manage');
    Route::post('/dashboard/shop', [ShopController::class, 'store'])->name('shop.store');
    Route::put('/dashboard/shop/{product}', [ShopController::class, 'update'])->name('shop.update');
    Route::delete('/dashboard/shop/{product}', [ShopController::class, 'destroy'])->name('shop.destroy');
});

// ── Discovery call staff assignment (CEO only) ──────────────────────────────
Route::middleware('role:CEO')->group(function () {
    Route::post('/discovery/{id}/assign', [\App\Http\Controllers\DiscoveryController::class, 'assignStaff'])->name('discovery.assign');
});

// ── Student & Instructor directory (CEO + Admin) ────────────────────────────
use App\Http\Controllers\DirectoryController;
Route::middleware('role:CEO,ADMIN')->group(function () {
    Route::get('/dashboard/students', [DirectoryController::class, 'students'])->name('directory.students');
    Route::get('/dashboard/students/{student}', [DirectoryController::class, 'studentDetail'])->name('directory.student');
    Route::get('/dashboard/instructors', [DirectoryController::class, 'instructors'])->name('directory.instructors');
});

// ═══════════════════════════════════════════════════════════════════════════
//  v4 — Master Calendar, Instructor Portal, Resource Centres
// ═══════════════════════════════════════════════════════════════════════════
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\LibraryController;

// ── Public Parent Resource Centre ───────────────────────────────────────────
Route::get('/resources/parents', [LibraryController::class, 'parentCentre'])->name('parent.resources');
Route::get('/resources/parents/{resource}', [LibraryController::class, 'parentArticle'])->name('parent.article');

// ── Authenticated downloads + instructor portal ─────────────────────────────
Route::middleware('role')->group(function () {
    Route::get('/library/{resource}/download', [LibraryController::class, 'download'])->name('library.download');
});
Route::middleware('role:TUTOR,CEO,ADMIN')->group(function () {
    Route::get('/dashboard/instructor-portal', [LibraryController::class, 'instructorPortal'])->name('instructor.portal');
});

// ── CEO/Admin: calendar + library management ────────────────────────────────
Route::middleware('role:CEO,ADMIN')->group(function () {
    Route::get('/dashboard/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::post('/dashboard/calendar/event', [CalendarController::class, 'storeEvent'])->name('calendar.event.store');
    Route::delete('/dashboard/calendar/event/{event}', [CalendarController::class, 'destroyEvent'])->name('calendar.event.destroy');

    Route::get('/dashboard/library', [LibraryController::class, 'manage'])->name('library.manage');
    Route::post('/dashboard/library', [LibraryController::class, 'store'])->name('library.store');
    Route::delete('/dashboard/library/{resource}', [LibraryController::class, 'destroy'])->name('library.destroy');
});

// ═══════════════════════════════════════════════════════════════════════════
//  v5 — Become an Instructor, Careers, Press, Waitlist
// ═══════════════════════════════════════════════════════════════════════════
use App\Http\Controllers\CareerController;

Route::get('/become-an-instructor', [CareerController::class, 'becomeInstructor'])->name('become.instructor');
Route::post('/become-an-instructor', [CareerController::class, 'submitApplication'])->name('become.instructor.submit');
Route::get('/careers', [CareerController::class, 'careers'])->name('careers');
Route::get('/press', [CareerController::class, 'press'])->name('press');
Route::get('/waitlist', [CareerController::class, 'waitlist'])->name('waitlist');
Route::post('/waitlist', [CareerController::class, 'submitWaitlist'])->name('waitlist.submit');

Route::middleware('role:CEO,ADMIN')->group(function () {
    Route::get('/dashboard/recruitment', [CareerController::class, 'submissions'])->name('recruitment');
});

// ── Informational pages ─────────────────────────────────────────────────────
Route::get('/faq', [\App\Http\Controllers\PageController::class, 'faq'])->name('faq');
Route::get('/meet-auntie-kash', [\App\Http\Controllers\PageController::class, 'meetAuntieKash'])->name('meet.auntie.kash');
Route::get('/terms', [\App\Http\Controllers\PageController::class, 'terms'])->name('terms');
Route::get('/anthem', [\App\Http\Controllers\PageController::class, 'anthem'])->name('anthem');

// ═══════════════════════════════════════════════════════════════════════════
//  v6 — Activities (printable learning activities)
// ═══════════════════════════════════════════════════════════════════════════
use App\Http\Controllers\ActivityController;

// Public
Route::get('/activities', [ActivityController::class, 'index'])->name('activities');
Route::get('/activities/{activity}/download', [ActivityController::class, 'download'])->name('activities.download');
// Management
Route::middleware('role:CEO,ADMIN')->group(function () {
    Route::get('/dashboard/activities', [ActivityController::class, 'manage'])->name('activities.manage');
    Route::post('/dashboard/activities', [ActivityController::class, 'store'])->name('activities.store');
    Route::put('/dashboard/activities/{activity}', [ActivityController::class, 'update'])->name('activities.update');
    Route::delete('/dashboard/activities/{activity}', [ActivityController::class, 'destroy'])->name('activities.destroy');
});

// ═══════════════════════════════════════════════════════════════════════════
//  v11 — Reading Library (books)
// ═══════════════════════════════════════════════════════════════════════════
use App\Http\Controllers\LibraryBookController;

Route::get('/reading-library', [LibraryBookController::class, 'index'])->name('reading.library');
Route::get('/reading-library/book/{book:slug}', [LibraryBookController::class, 'show'])->name('reading.book');

Route::middleware('role:CEO,ADMIN')->group(function () {
    Route::get('/dashboard/books', [LibraryBookController::class, 'manage'])->name('books.manage');
    Route::post('/dashboard/books', [LibraryBookController::class, 'store'])->name('books.store');
    Route::put('/dashboard/books/{book}', [LibraryBookController::class, 'update'])->name('books.update');
    Route::delete('/dashboard/books/{book}', [LibraryBookController::class, 'destroy'])->name('books.destroy');
});
