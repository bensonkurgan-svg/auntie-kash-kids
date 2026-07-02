<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\TutorProfile;
use App\Models\Student;
use App\Services\AccountService;
use App\Mail\StaffWelcomeMail;

class StaffController extends Controller
{
    /** Listing of staff for management screens. */
    public function index()
    {
        $admins = User::where('role','ADMIN')->orderBy('name')->get();
        $tutors = User::where('role','TUTOR')->with('tutorProfile')->orderBy('name')->get();
        return view('dashboard.staff', compact('admins','tutors'));
    }

    /** CEO only: create an admin. */
    public function storeAdmin(Request $request)
    {
        $this->authorizeCeo();
        $data = $request->validate([
            'name'  => ['required','string','max:255'],
            'email' => ['required','email','unique:users,email'],
            'phone' => ['nullable','string','max:40'],
        ]);

        $result = AccountService::createStaff($data, 'ADMIN');
        $this->deliverCredentials($result['user'], $result['password']);

        return back()->with('newCredentials', [
            'name' => $result['user']->name,
            'email' => $result['user']->email,
            'password' => $result['password'],
            'role' => 'Admin',
        ]);
    }

    /** CEO or Admin: register a tutor. */
    public function storeTutor(Request $request)
    {
        if (! Auth::user()->canManageContent()) abort(403);

        $data = $request->validate([
            'name'        => ['required','string','max:255'],
            'email'       => ['required','email','unique:users,email'],
            'work_email'  => ['nullable','email'],
            'phone'       => ['required','string','max:40'],
            'bio'         => ['nullable','string','max:1000'],
            'specialties' => ['nullable','array'],
        ]);

        $result = AccountService::createStaff($data, 'TUTOR');
        $this->deliverCredentials($result['user'], $result['password']);

        return back()->with('newCredentials', [
            'name' => $result['user']->name,
            'email' => $result['user']->email,
            'password' => $result['password'],
            'role' => 'Tutor',
        ]);
    }

    /** Deactivate (soft) — preferred over deletion to preserve history. */
    public function deactivate(User $user)
    {
        $this->guardStaffTarget($user);
        $user->update(['is_active' => false]);
        return back()->with('success', "{$user->name}'s account has been deactivated.");
    }

    public function reactivate(User $user)
    {
        $this->guardStaffTarget($user);
        $user->update(['is_active' => true]);
        return back()->with('success', "{$user->name}'s account has been reactivated.");
    }

    /** Hard delete — CEO only, and never the last CEO / not self. */
    public function destroy(User $user)
    {
        $this->authorizeCeo();
        if ($user->id === Auth::id()) {
            return back()->withErrors(['delete' => 'You cannot delete your own account.']);
        }
        if ($user->role === 'CEO') {
            return back()->withErrors(['delete' => 'CEO accounts cannot be deleted here.']);
        }
        $name = $user->name;
        $user->delete();
        return back()->with('success', "{$name}'s account has been permanently deleted.");
    }

    /** CEO assigns a tutor to a child. */
    public function assignTutor(Request $request)
    {
        $this->authorizeCeo();
        $data = $request->validate([
            'student_id' => ['required','exists:students,id'],
            'tutor_profile_id' => ['required','exists:tutor_profiles,id'],
        ]);
        Student::where('id',$data['student_id'])->update(['assigned_tutor_id'=>$data['tutor_profile_id']]);
        return back()->with('success', 'Tutor assigned to student.');
    }

    // ── helpers ──────────────────────────────────────────────────────────
    private function authorizeCeo(): void
    {
        if (! Auth::user()->isCEO()) abort(403, 'Only the CEO can perform this action.');
    }

    private function guardStaffTarget(User $user): void
    {
        if (! Auth::user()->canManageContent()) abort(403);
        // Admins may only manage tutors; CEO may manage admins & tutors.
        if (Auth::user()->isAdmin() && $user->role !== 'TUTOR') abort(403);
        if ($user->role === 'CEO') abort(403);
    }

    private function deliverCredentials(User $user, string $password): void
    {
        try {
            Mail::to($user->email)->queue(new StaffWelcomeMail($user, $password));
        } catch (\Throwable $e) {
            // Email is best-effort; credentials are also shown on screen once.
        }
    }
}
