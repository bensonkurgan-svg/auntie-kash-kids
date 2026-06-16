<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class GdprController extends Controller
{
    public function exportPage() { return view('pages.data-export'); }
    public function deletePage() { return view('pages.data-delete'); }

    public function export()
    {
        $user = Auth::user()->load(['students.enrollments.course','enrollments','reviews']);
        $data = [
            'profile' => ['name'=>$user->name,'email'=>$user->email,'role'=>$user->role,'joined'=>$user->created_at->toDateString()],
            'children' => $user->students->map(fn($s) => ['name'=>$s->name,'age'=>$s->age])->toArray(),
            'enrollments' => $user->enrollments->map(fn($e) => ['course'=>$e->course->title ?? '','progress'=>$e->progress,'status'=>$e->status])->toArray(),
        ];
        $filename = 'my-data-'.now()->format('Y-m-d').'.json';
        return response()->json($data)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    public function delete()
    {
        $user = Auth::user();
        Auth::logout();
        $user->delete();
        return redirect('/')->with('success', 'Your account and data have been permanently deleted.');
    }
}
