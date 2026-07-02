<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class GdprController extends Controller
{
    public function exportPage() { return view('pages.data-export'); }
    public function deletePage() { return view('pages.data-delete'); }

    private function collect(): array
    {
        $user = Auth::user()->load(['students.enrollments.course','enrollments','reviews']);
        return [
            'profile' => [
                'name'   => $user->name,
                'email'  => $user->email,
                'role'   => $user->role,
                'phone'  => $user->phone,
                'joined' => $user->created_at->toDateString(),
            ],
            'children' => $user->students->map(fn($s) => [
                'name' => $s->name, 'age' => $s->age,
            ])->toArray(),
            'enrollments' => $user->enrollments->map(fn($e) => [
                'course' => $e->course->title ?? '',
                'progress' => $e->progress,
                'status' => $e->status,
            ])->toArray(),
        ];
    }

    public function export(string $format = 'json')
    {
        $data = $this->collect();
        $stamp = now()->format('Y-m-d');

        return match($format) {
            'txt' => response($this->toText($data), 200, [
                'Content-Type' => 'text/plain',
                'Content-Disposition' => "attachment; filename=\"my-data-{$stamp}.txt\"",
            ]),
            'pdf' => Pdf::loadView('exports.data-pdf', ['data' => $data])
                ->download("my-data-{$stamp}.pdf"),
            default => response()->json($data)
                ->header('Content-Disposition', "attachment; filename=\"my-data-{$stamp}.json\""),
        };
    }

    private function toText(array $data): string
    {
        $out  = "AUNTIE KASH KIDS — PERSONAL DATA EXPORT\n";
        $out .= "Generated: ".now()->toDayDateTimeString()."\n";
        $out .= str_repeat('=', 50)."\n\n";
        $out .= "PROFILE\n";
        foreach ($data['profile'] as $k => $v) $out .= "  ".ucfirst($k).": ".($v ?? '—')."\n";
        $out .= "\nCHILDREN\n";
        foreach ($data['children'] as $c) $out .= "  - {$c['name']} (age ".($c['age'] ?? 'n/a').")\n";
        if (empty($data['children'])) $out .= "  (none)\n";
        $out .= "\nENROLMENTS\n";
        foreach ($data['enrollments'] as $e) $out .= "  - {$e['course']} — {$e['progress']}% ({$e['status']})\n";
        if (empty($data['enrollments'])) $out .= "  (none)\n";
        return $out;
    }

    public function delete()
    {
        $user = Auth::user();
        Auth::logout();
        $user->delete();
        return redirect('/')->with('success', 'Your account and data have been permanently deleted.');
    }
}
