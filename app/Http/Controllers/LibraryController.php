<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\LibraryResource;
use App\Models\TutorProfile;
use App\Models\ClassSession;

class LibraryController extends Controller
{
    private const ALLOWED_EXT = ['pdf','doc','docx','ppt','pptx','xls','xlsx','jpg','jpeg','png','txt'];
    private const MAX_KB = 20480;

    /** Instructor Portal: resources + their assigned classes. */
    public function instructorPortal()
    {
        $user = Auth::user();
        if (! in_array($user->role, ['TUTOR','CEO','ADMIN'])) abort(403);

        $resources = LibraryResource::published()->forInstructors()
            ->orderByDesc('is_featured')->orderBy('category')->orderBy('title')->get()
            ->groupBy('category');

        // The instructor's assigned classes / schedule
        $profile = TutorProfile::with('courses')->where('user_id', $user->id)->first();
        $upcomingClasses = ClassSession::with(['course','student'])
            ->where('tutor_id', $user->id)
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')->take(10)->get();

        return view('dashboard.instructor-portal', compact('resources','profile','upcomingClasses'));
    }

    /** Public Parent Resource Centre (searchable article library). */
    public function parentCentre(Request $request)
    {
        $search = $request->input('q');
        $category = $request->input('category');

        $query = LibraryResource::published()->forParents();
        if ($search) {
            $query->where(fn($q) => $q->where('title','like',"%{$search}%")
                ->orWhere('description','like',"%{$search}%")
                ->orWhere('body','like',"%{$search}%"));
        }
        if ($category) $query->where('category', $category);

        $resources = $query->orderByDesc('is_featured')->latest()->get();
        $featured = LibraryResource::published()->forParents()->where('is_featured', true)->take(3)->get();
        $categories = LibraryResource::published()->forParents()
            ->whereNotNull('category')->distinct()->pluck('category');

        return view('pages.parent-resources', compact('resources','featured','categories','search','category'));
    }

    /** Single parent article. */
    public function parentArticle(LibraryResource $resource)
    {
        abort_if($resource->audience !== 'PARENT' || ! $resource->is_published, 404);
        $related = LibraryResource::published()->forParents()
            ->where('category', $resource->category)->where('id','!=',$resource->id)->take(4)->get();
        return view('pages.parent-article', compact('resource','related'));
    }

    /** CEO/Admin: manage the resource library (both audiences). */
    public function manage()
    {
        if (! Auth::user()->canManageContent()) abort(403);
        $resources = LibraryResource::latest()->get();
        return view('dashboard.library-manager', compact('resources'));
    }

    public function store(Request $request)
    {
        if (! Auth::user()->canManageContent()) abort(403);
        $data = $request->validate([
            'title'        => ['required','string','max:255'],
            'description'  => ['nullable','string','max:1000'],
            'category'     => ['nullable','string','max:100'],
            'audience'     => ['required','in:INSTRUCTOR,PARENT'],
            'content_type' => ['required','in:DOCUMENT,ARTICLE,LINK,VIDEO'],
            'body'         => ['nullable','string'],
            'external_url' => ['nullable','url'],
            'file'         => ['nullable','file','max:'.self::MAX_KB],
            'is_featured'  => ['nullable','boolean'],
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $ext = strtolower($request->file('file')->getClientOriginalExtension());
            if (! in_array($ext, self::ALLOWED_EXT)) {
                return back()->withErrors(['file' => 'That file type is not allowed.']);
            }
            $filePath = $request->file('file')->store('library');
        }

        LibraryResource::create([
            'title'        => $data['title'],
            'description'  => $data['description'] ?? null,
            'category'     => $data['category'] ?? null,
            'audience'     => $data['audience'],
            'content_type' => $data['content_type'],
            'body'         => $data['body'] ?? null,
            'file_path'    => $filePath,
            'external_url' => $data['external_url'] ?? null,
            'is_featured'  => $request->boolean('is_featured'),
            'is_published' => true,
            'created_by'   => Auth::id(),
        ]);
        return back()->with('success', 'Resource added.');
    }

    public function destroy(LibraryResource $resource)
    {
        if (! Auth::user()->canManageContent()) abort(403);
        if ($resource->file_path && Storage::exists($resource->file_path)) {
            Storage::delete($resource->file_path);
        }
        $resource->delete();
        return back()->with('success', 'Resource removed.');
    }

    public function download(LibraryResource $resource)
    {
        // Instructor resources require staff/tutor login; parent resources are public.
        if ($resource->audience === 'INSTRUCTOR') {
            $u = Auth::user();
            if (! $u || ! in_array($u->role, ['TUTOR','CEO','ADMIN'])) abort(403);
        }
        if (! $resource->file_path || ! Storage::exists($resource->file_path)) abort(404);
        return Storage::download($resource->file_path, $resource->title);
    }
}
