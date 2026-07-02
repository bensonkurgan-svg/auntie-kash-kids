<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeController extends Controller
{
    public function checkout(Request $request)
    {
        $data = $request->validate([
            'course_id' => ['required','exists:courses,id'],
            'student_id' => ['required','exists:students,id'],
        ]);

        $course = Course::findOrFail($data['course_id']);
        $stripe = new StripeClient(config('services.stripe.secret'));

        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $course->title,
                        'description' => 'Enrollment in '.$course->title.' at Auntie Kash Kids Academy',
                    ],
                    'unit_amount' => (int) round($course->price * 100),
                ],
                'quantity' => 1,
            ]],
            'metadata' => [
                'user_id' => Auth::id(),
                'course_id' => $course->id,
                'student_id' => $data['student_id'],
            ],
            'customer_email' => Auth::user()->email,
            'success_url' => config('app.url').'/dashboard/parent?payment=success',
            'cancel_url' => config('app.url').'/courses',
        ]);

        return response()->json(['url' => $session->url]);
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Throwable $e) {
            Log::error('Stripe webhook signature failed: '.$e->getMessage());
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $meta = $session->metadata;

            if (isset($meta->student_id, $meta->course_id, $meta->user_id)) {
                Enrollment::updateOrCreate(
                    ['student_id' => $meta->student_id, 'course_id' => $meta->course_id],
                    [
                        'user_id' => $meta->user_id,
                        'status' => 'ACTIVE',
                        'stripe_payment_id' => $session->payment_intent ?? $session->id,
                    ]
                );
            }
        }

        return response('Webhook handled', 200);
    }
}
