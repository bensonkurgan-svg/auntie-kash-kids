<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TutorProfile;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->isTutor()
            ? TutorProfile::firstOrCreate(['user_id' => $user->id])
            : null;
        return view('dashboard.profile', compact('user','profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'       => ['required','string','max:255'],
            'phone'      => ['nullable','string','max:40'],
            'work_email' => ['nullable','email'],
            'bio'        => ['nullable','string','max:1500'],
            'specialties'=> ['nullable','array'],
            'qualifications' => ['nullable','string','max:255'],
            'country'    => ['nullable','string','max:100'],
            'availability' => ['nullable','string','max:255'],
            'photo'      => ['nullable','image','max:5120'],
            'founder_photo' => ['nullable','image','max:5120'],
        ]);

        $photoUrl = $user->photo_url;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('avatars', 'public');
            $photoUrl = '/storage/'.$path;
        }

        $user->update([
            'name'       => $data['name'],
            'phone'      => $data['phone'] ?? $user->phone,
            'work_email' => $data['work_email'] ?? $user->work_email,
            'photo_url'  => $photoUrl,
        ]);

        // CEO can set the founder photo shown on the public About page.
        if ($user->isCEO() && $request->hasFile('founder_photo')) {
            $fp = '/storage/'.$request->file('founder_photo')->store('founder', 'public');
            \App\Models\SiteSetting::put('founder_photo', $fp);
        }

        // Sync the tutor's public profile so the photo shows wherever referenced.
        if ($user->isTutor()) {
            $profile = TutorProfile::firstOrCreate(['user_id' => $user->id]);
            $profile->update([
                'bio'        => $data['bio'] ?? $profile->bio,
                'qualifications' => $data['qualifications'] ?? $profile->qualifications,
                'country'    => $data['country'] ?? $profile->country,
                'availability' => $data['availability'] ?? $profile->availability,
                'phone'      => $data['phone'] ?? $profile->phone,
                'work_email' => $data['work_email'] ?? $profile->work_email,
                'photo_url'  => $photoUrl,
                'specialties'=> $data['specialties'] ?? $profile->specialties,
            ]);
        }

        return back()->with('success', 'Your profile has been updated.');
    }
}
