<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Course;
use App\Models\CourseMaterial;

class CourseMaterialController extends Controller
{
    // Allowed upload types — strict allowlist for security.
    private const ALLOWED_EXT = ['pdf','doc','docx','ppt','pptx','xls','xlsx','jpg','jpeg','png','gif','mp3','txt'];
    private const MAX_KB = 20480; // 20 MB

    /** Tutor: submit material (goes to UNDER_REVIEW). */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (! in_array($user->role, ['TUTOR','CEO','ADMIN'])) abort(403);

        $data = $request->validate([
            'course_id'    => ['required','exists:courses,id'],
            'module_id'    => ['nullable','exists:modules,id'],
            'title'        => ['required','string','max:255'],
            'description'  => ['nullable','string','max:1000'],
            'type'         => ['required','in:DOCUMENT,VIDEO_LINK,IMAGE,LINK'],
            'external_url' => ['nullable','url','required_if:type,VIDEO_LINK,LINK'],
            'file'         => ['nullable','file','max:'.self::MAX_KB,'required_if:type,DOCUMENT,IMAGE'],
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $ext = strtolower($request->file('file')->getClientOriginalExtension());
            if (! in_array($ext, self::ALLOWED_EXT)) {
                return back()->withErrors(['file' => 'That file type is not allowed.']);
            }
            // Stored on the Railway volume (storage/app/materials), served via controller.
            $filePath = $request->file('file')->store('materials');
        }

        // CEO/Admin uploads are auto-approved; tutor uploads go under review.
        $autoApprove = in_array($user->role, ['CEO','ADMIN']);

        CourseMaterial::create([
            'course_id'    => $data['course_id'],
            'module_id'    => $data['module_id'] ?? null,
            'title'        => $data['title'],
            'description'  => $data['description'] ?? null,
            'type'         => $data['type'],
            'file_path'    => $filePath,
            'external_url' => $data['external_url'] ?? null,
            'status'       => $autoApprove ? 'APPROVED' : 'UNDER_REVIEW',
            'submitted_by' => $user->id,
            'reviewed_by'  => $autoApprove ? $user->id : null,
            'reviewed_at'  => $autoApprove ? now() : null,
        ]);

        return back()->with('success', $autoApprove
            ? 'Material added and published.'
            : 'Material submitted for review. You will see the status update here.');
    }

    /** CEO: review decision. */
    public function review(Request $request, CourseMaterial $material)
    {
        if (! Auth::user()->isCEO()) abort(403);
        $data = $request->validate([
            'decision' => ['required','in:APPROVED,REJECTED,UNDER_REVIEW'],
            'review_note' => ['nullable','string','max:500'],
        ]);
        $material->update([
            'status'      => $data['decision'],
            'review_note' => $data['review_note'] ?? null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);
        return back()->with('success', 'Material marked as '.$material->statusLabel().'.');
    }

    /** Secure download — only approved materials, or the submitter/staff. */
    public function download(CourseMaterial $material)
    {
        $user = Auth::user();
        $canSeeUnapproved = $user && (in_array($user->role,['CEO','ADMIN']) || $material->submitted_by === $user->id);

        if ($material->status !== 'APPROVED' && ! $canSeeUnapproved) abort(403);
        if (! $material->file_path || ! Storage::exists($material->file_path)) abort(404);

        return Storage::download($material->file_path, $material->title);
    }

    public function destroy(CourseMaterial $material)
    {
        $user = Auth::user();
        if (! (in_array($user->role,['CEO','ADMIN']) || $material->submitted_by === $user->id)) abort(403);
        if ($material->file_path && Storage::exists($material->file_path)) {
            Storage::delete($material->file_path);
        }
        $material->delete();
        return back()->with('success', 'Material removed.');
    }
}
