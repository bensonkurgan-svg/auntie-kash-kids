<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Course;
use App\Models\Module;
use App\Models\TutorProfile;

class ProgramController extends Controller
{
    /** Internal program manager (NOT the public courses page). */
    public function index()
    {
        $courses = Course::with(['tutorProfile.user','modules.lessons','materials'])
            ->withCount('enrollments')->orderBy('title')->get();
        $tutors = TutorProfile::with('user')->get();
        return view('dashboard.programs', compact('courses','tutors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required','string','max:255'],
            'description' => ['required','string'],
            'icon'        => ['nullable','string','max:8'],
            'price'       => ['required','numeric','min:0'],
            'tutor_profile_id' => ['required','exists:tutor_profiles,id'],
        ]);
        $data['slug'] = Str::slug($data['title']).'-'.Str::random(4);
        $data['icon'] = $data['icon'] ?: '📚';
        $data['is_published'] = true;
        Course::create($data);
        return back()->with('success', 'Program created.');
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'title'       => ['required','string','max:255'],
            'description' => ['required','string'],
            'icon'        => ['nullable','string','max:8'],
            'price'       => ['required','numeric','min:0'],
            'tutor_profile_id' => ['required','exists:tutor_profiles,id'],
            'is_published' => ['nullable','boolean'],
        ]);
        $data['is_published'] = $request->boolean('is_published');
        $course->update($data);
        return back()->with('success', 'Program updated.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return back()->with('success', 'Program deleted.');
    }

    public function addModule(Request $request, Course $course)
    {
        $data = $request->validate(['title' => ['required','string','max:255']]);
        $course->modules()->create([
            'title' => $data['title'],
            'order' => $course->modules()->count() + 1,
        ]);
        return back()->with('success', 'Module added.');
    }

    public function deleteModule(Module $module)
    {
        $module->delete();
        return back()->with('success', 'Module removed.');
    }
}
