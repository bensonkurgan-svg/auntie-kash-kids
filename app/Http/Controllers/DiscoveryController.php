<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\DiscoveryForm;
use App\Mail\DiscoveryConfirmationMail;
use App\Mail\DiscoveryTeamMail;

class DiscoveryController extends Controller
{
    public function show() { return view('pages.discovery'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'parent_name' => ['required','string','max:255'],
            'parent_email' => ['required','email'],
            'child_name' => ['required','string','max:255'],
            'child_age' => ['required','integer','min:3','max:18'],
            'parent_phone' => ['nullable','string'],
            'parent_country' => ['nullable','string'],
            'parent_city' => ['nullable','string'],
            'preferred_contact' => ['nullable','string'],
            'child_grade' => ['nullable','string'],
            'child_country' => ['nullable','string'],
            'primary_language' => ['nullable','string'],
            'interests' => ['nullable','array'],
            'strengths' => ['nullable','array'],
            'skills_to_develop' => ['nullable','array'],
            'learning_preferences' => ['nullable','array'],
            'parent_goals' => ['nullable','string'],
            'preferred_days' => ['nullable','array'],
            'preferred_time' => ['nullable','string'],
            'time_zone' => ['nullable','string'],
            'hear_about_us' => ['nullable','string'],
            'honeypot' => ['nullable','size:0'], // bot trap
        ]);

        // Honeypot — silently reject bots
        if (!empty($request->input('honeypot'))) {
            return response()->json(['success' => true]);
        }

        $submission = DiscoveryForm::create(array_merge($data, ['status' => 'NEW']));

        try {
            Mail::to($submission->parent_email)->queue(new DiscoveryConfirmationMail($submission));
            Mail::to(config('services.team.notification_email'))->queue(new DiscoveryTeamMail($submission));
        } catch (\Throwable $e) {}

        return response()->json(['success' => true, 'id' => $submission->id]);
    }

    public function updateStatus(Request $request, int $id)
    {
        $form = DiscoveryForm::findOrFail($id);
        $form->update(['status' => $request->input('status')]);
        return back();
    }
}
