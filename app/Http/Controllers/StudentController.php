<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class StudentController extends Controller
{
    /** Parent adds a child (used by self-enroll flow + dashboard). */
    public function store(Request $request)
    {
        if (! Auth::user()->isParent()) abort(403);

        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'age'  => ['nullable','integer','min:3','max:18'],
            'grade_level' => ['nullable','string','max:100'],
            'emergency_contact_name' => ['nullable','string','max:255'],
            'emergency_contact_phone' => ['nullable','string','max:40'],
            'emergency_contact_relationship' => ['nullable','string','max:100'],
            'medical_notes' => ['nullable','string','max:1000'],
        ]);

        $student = Student::create(array_merge(
            ['parent_id' => Auth::id()],
            $data
        ));

        // JSON for the checkout modal; redirect for normal form posts.
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['id' => $student->id, 'name' => $student->name]);
        }
        return back()->with('success', "{$student->name} added.");
    }

    public function update(Request $request, Student $student)
    {
        if ($student->parent_id !== Auth::id()) abort(403);
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'age'  => ['nullable','integer','min:3','max:18'],
            'grade_level' => ['nullable','string','max:100'],
            'emergency_contact_name' => ['nullable','string','max:255'],
            'emergency_contact_phone' => ['nullable','string','max:40'],
            'emergency_contact_relationship' => ['nullable','string','max:100'],
            'medical_notes' => ['nullable','string','max:1000'],
        ]);
        $student->update($data);
        return back()->with('success', "{$student->name}'s details updated.");
    }

    public function destroy(Student $student)
    {
        if ($student->parent_id !== Auth::id()) abort(403);
        $name = $student->name;
        $student->delete();
        return back()->with('success', "{$name} removed.");
    }
}
