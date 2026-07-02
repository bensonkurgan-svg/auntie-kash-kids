<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\TutorProfile;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'tutor_profile_id' => ['required','exists:tutor_profiles,id'],
            'rating' => ['required','integer','min:1','max:5'],
            'comment' => ['nullable','string','max:1000'],
        ]);

        Review::create(array_merge($data, ['user_id' => Auth::id()]));

        // Recalculate tutor rating
        $tutor = TutorProfile::find($data['tutor_profile_id']);
        $avg = $tutor->reviews()->avg('rating');
        $count = $tutor->reviews()->count();
        $tutor->update(['rating' => round($avg, 1), 'review_count' => $count]);

        return back()->with('success', 'Thank you for your review!');
    }
}
