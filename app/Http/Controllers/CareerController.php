<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WaitlistEntry;
use App\Models\InstructorApplication;

class CareerController extends Controller
{
    // ── Public pages ────────────────────────────────────────────────────────
    public function becomeInstructor() { return view('pages.become-instructor'); }
    public function careers()          { return view('pages.careers'); }
    public function press()            { return view('pages.press'); }
    public function waitlist()         { return view('pages.waitlist'); }

    // ── Form submissions ────────────────────────────────────────────────────
    public function submitWaitlist(Request $request)
    {
        $data = $request->validate([
            'parent_name' => ['required','string','max:255'],
            'email'       => ['required','email','max:255'],
            'phone'       => ['nullable','string','max:40'],
            'child_name'  => ['nullable','string','max:255'],
            'child_age'   => ['nullable','integer','min:3','max:18'],
            'program_interest' => ['nullable','string','max:255'],
            'type'        => ['required','in:WAITLIST,FOUNDING_FAMILY'],
            'message'     => ['nullable','string','max:1000'],
        ]);
        WaitlistEntry::create($data);
        return back()->with('success', "Thank you! You're on the list — we'll be in touch soon. 🌈");
    }

    public function submitApplication(Request $request)
    {
        $data = $request->validate([
            'name'           => ['required','string','max:255'],
            'email'          => ['required','email','max:255'],
            'phone'          => ['nullable','string','max:40'],
            'country'        => ['nullable','string','max:100'],
            'qualifications' => ['nullable','string','max:255'],
            'experience'     => ['nullable','string','max:2000'],
            'subjects'       => ['nullable','string','max:255'],
            'cover_note'     => ['nullable','string','max:2000'],
            'cv'             => ['nullable','file','mimes:pdf,doc,docx','max:10240'],
        ]);

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('applications');
        }

        InstructorApplication::create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'phone'          => $data['phone'] ?? null,
            'country'        => $data['country'] ?? null,
            'qualifications' => $data['qualifications'] ?? null,
            'experience'     => $data['experience'] ?? null,
            'subjects'       => $data['subjects'] ?? null,
            'cover_note'     => $data['cover_note'] ?? null,
            'cv_path'        => $cvPath,
        ]);
        return back()->with('success', "Thank you for applying! We'll review your application and be in touch. 🌟");
    }

    // ── CEO/Admin: view submissions ─────────────────────────────────────────
    public function submissions()
    {
        if (! Auth::user()->canManageContent()) abort(403);
        $waitlist = WaitlistEntry::latest()->get();
        $applications = InstructorApplication::latest()->get();
        return view('dashboard.recruitment', compact('waitlist','applications'));
    }
}
