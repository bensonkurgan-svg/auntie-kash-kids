<?php
namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\ContentPost;
use App\Models\TutorProfile;
use App\Models\CmsPage;
use App\Services\CmsService;

class PageController extends Controller
{
    public function home()
    {
        $content = CmsService::homepage();
        $featuredCourses = Course::with('tutorProfile.user')->where('is_published', true)->take(8)->get();
        return view('pages.home', compact('content', 'featuredCourses'));
    }

    public function about()
    {
        $about = CmsService::about();
        $tutors = TutorProfile::with('user')->take(6)->get();
        return view('pages.about', compact('about', 'tutors'));
    }

    public function courses()
    {
        $courses = Course::with('tutorProfile.user')->where('is_published', true)->get();
        return view('pages.courses', compact('courses'));
    }

    public function courseDetail(string $slug)
    {
        $course = Course::with(['tutorProfile.user','modules.lessons'])
            ->where('slug', $slug)->where('is_published', true)->firstOrFail();
        $enrollmentCount = $course->enrollments()->count();
        return view('pages.course-detail', compact('course', 'enrollmentCount'));
    }

    public function blog()
    {
        $posts = ContentPost::where('type','BLOG_POST')->where('status','PUBLISHED')
            ->orderByDesc('published_at')->get();
        $featured = $posts->where('featured', true)->first();
        return view('pages.blog', compact('posts', 'featured'));
    }

    public function blogPost(string $slug)
    {
        $post = ContentPost::where('type','BLOG_POST')->where('slug',$slug)
            ->where('status','PUBLISHED')->firstOrFail();
        $related = ContentPost::where('type','BLOG_POST')->where('status','PUBLISHED')
            ->where('id','!=',$post->id)->take(3)->get();
        return view('pages.blog-post', compact('post', 'related'));
    }

    public function library()
    {
        $items = ContentPost::where('type','LIBRARY_ITEM')->where('status','PUBLISHED')
            ->orderByDesc('published_at')->get();
        return view('pages.library', compact('items'));
    }

    public function libraryItem(int $id)
    {
        $item = ContentPost::where('type','LIBRARY_ITEM')->where('id',$id)
            ->where('status','PUBLISHED')->firstOrFail();
        return view('pages.library-item', compact('item'));
    }

    public function resources()
    {
        $resources = ContentPost::where('type','PARENT_RESOURCE')->where('status','PUBLISHED')
            ->orderByDesc('published_at')->get();
        return view('pages.resources', compact('resources'));
    }

    public function resourceItem(int $id)
    {
        $resource = ContentPost::where('type','PARENT_RESOURCE')->where('id',$id)
            ->where('status','PUBLISHED')->firstOrFail();
        return view('pages.resource-item', compact('resource'));
    }

    public function events()   { return view('pages.events'); }
    public function contact()  { return view('pages.contact'); }
    public function privacy()  { return view('pages.privacy'); }
    public function cookies()  { return view('pages.cookie-policy'); }
}
