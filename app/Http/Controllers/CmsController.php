<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CmsPage;
use App\Models\CmsChangeRequest;
use App\Services\CmsService;

class CmsController extends Controller
{
    public function editor()
    {
        $homepage = CmsService::homepage();
        $about = CmsService::about();
        $pending = CmsChangeRequest::with('requester')->where('status','PENDING')->latest()->get();
        return view('cms.editor', compact('homepage','about','pending'));
    }

    // Admin submits a change for approval
    public function submit(Request $request)
    {
        $data = $request->validate([
            'page_key' => ['required','string'],
            'changes' => ['required','array'],
        ]);

        // CEO can publish directly; Admin submits for review
        if (Auth::user()->isCEO()) {
            CmsPage::updateOrCreate(
                ['page_key' => $data['page_key']],
                [
                    'content' => $data['changes'],
                    'status' => 'APPROVED',
                    'created_by' => Auth::id(),
                    'reviewed_by' => Auth::id(),
                    'approved_at' => now(),
                ]
            );
            return back()->with('success', 'Published successfully.');
        }

        CmsChangeRequest::create([
            'page_key' => $data['page_key'],
            'changes' => $data['changes'],
            'status' => 'PENDING',
            'requested_by' => Auth::id(),
        ]);
        return back()->with('success', 'Submitted for CEO approval.');
    }

    public function approve(Request $request)
    {
        $req = CmsChangeRequest::findOrFail($request->input('id'));
        $req->update(['status'=>'APPROVED','reviewed_by'=>Auth::id(),'reviewed_at'=>now()]);

        CmsPage::updateOrCreate(
            ['page_key' => $req->page_key],
            [
                'content' => $req->changes,
                'status' => 'APPROVED',
                'created_by' => $req->requested_by,
                'reviewed_by' => Auth::id(),
                'approved_at' => now(),
            ]
        );
        return back()->with('success', 'Change approved and published.');
    }

    public function reject(Request $request)
    {
        $req = CmsChangeRequest::findOrFail($request->input('id'));
        $req->update([
            'status'=>'REJECTED',
            'reviewed_by'=>Auth::id(),
            'reviewed_at'=>now(),
            'review_notes'=>$request->input('notes'),
        ]);
        return back()->with('success', 'Change rejected.');
    }
}
