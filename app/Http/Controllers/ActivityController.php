<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Activity;

class ActivityController extends Controller
{
    private const ALLOWED_FILE = ['pdf'];
    private const MAX_KB = 20480;

    /** Public activities library, filterable by theme + age. */
    public function index(Request $request)
    {
        $theme = $request->input('theme');
        $age   = $request->input('age');

        $activities = Activity::published()
            ->when($theme, fn($q) => $q->where('theme', $theme))
            ->when($age, fn($q) => $q->where('age_bracket', $age))
            ->latest()->get();

        $themes = Activity::published()->whereNotNull('theme')->distinct()->pluck('theme');
        $ages   = Activity::published()->whereNotNull('age_bracket')->distinct()->pluck('age_bracket');

        return view('pages.activities', compact('activities','themes','ages','theme','age'));
    }

    public function download(Activity $activity)
    {
        if (! $activity->file_path || ! Storage::exists($activity->file_path)) abort(404);
        return Storage::download($activity->file_path, $activity->title.'.pdf');
    }

    /** CEO/Admin management. */
    public function manage()
    {
        if (! Auth::user()->canManageContent()) abort(403);
        $activities = Activity::latest()->get();
        $types = self::TYPES;
        return view('dashboard.activities-manager', compact('activities','types'));
    }

    // Activity type labels
    public const TYPES = [
        'WORD_SEARCH'     => 'Word Search',
        'CROSSWORD'       => 'Crossword',
        'MATCHING'        => 'Matching Game',
        'MAZE'            => 'Maze',
        'I_SPY'           => 'I Spy',
        'SPOT_DIFFERENCE' => 'Spot the Difference',
        'COLOURING'       => 'Colouring',
        'WORKSHEET'       => 'Worksheet',
        'OTHER'           => 'Other',
    ];

    public function store(Request $request)
    {
        if (! Auth::user()->canManageContent()) abort(403);
        $data = $request->validate([
            'title'         => ['required','string','max:255'],
            'theme'         => ['nullable','string','max:100'],
            'age_bracket'   => ['nullable','string','max:20'],
            'activity_type' => ['required','in:WORD_SEARCH,CROSSWORD,MATCHING,MAZE,I_SPY,SPOT_DIFFERENCE,COLOURING,WORKSHEET,OTHER'],
            'description'   => ['nullable','string','max:1000'],
            'image'         => ['nullable','image','max:5120'],
            'file'          => ['nullable','file','mimes:pdf','max:'.self::MAX_KB],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = '/storage/'.$request->file('image')->store('activities', 'public');
        }
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('activity-files');
        }

        Activity::create([
            'title'         => $data['title'],
            'theme'         => $data['theme'] ?? null,
            'age_bracket'   => $data['age_bracket'] ?? null,
            'activity_type' => $data['activity_type'],
            'description'   => $data['description'] ?? null,
            'image_path'    => $imagePath,
            'file_path'     => $filePath,
            'is_published'  => true,
            'created_by'    => Auth::id(),
        ]);
        return back()->with('success', 'Activity added.');
    }

    public function update(Request $request, Activity $activity)
    {
        if (! Auth::user()->canManageContent()) abort(403);
        $data = $request->validate([
            'title'         => ['required','string','max:255'],
            'theme'         => ['nullable','string','max:100'],
            'age_bracket'   => ['nullable','string','max:20'],
            'activity_type' => ['required','in:WORD_SEARCH,CROSSWORD,MATCHING,MAZE,I_SPY,SPOT_DIFFERENCE,COLOURING,WORKSHEET,OTHER'],
            'description'   => ['nullable','string','max:1000'],
            'image'         => ['nullable','image','max:5120'],
            'file'          => ['nullable','file','mimes:pdf','max:'.self::MAX_KB],
            'is_published'  => ['nullable','boolean'],
        ]);

        $imagePath = $activity->image_path;
        if ($request->hasFile('image')) {
            $imagePath = '/storage/'.$request->file('image')->store('activities', 'public');
        }
        $filePath = $activity->file_path;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('activity-files');
        }

        $activity->update([
            'title'         => $data['title'],
            'theme'         => $data['theme'] ?? null,
            'age_bracket'   => $data['age_bracket'] ?? null,
            'activity_type' => $data['activity_type'],
            'description'   => $data['description'] ?? null,
            'image_path'    => $imagePath,
            'file_path'     => $filePath,
            'is_published'  => $request->boolean('is_published'),
        ]);
        return back()->with('success', 'Activity updated.');
    }

    public function destroy(Activity $activity)
    {
        if (! Auth::user()->canManageContent()) abort(403);
        if ($activity->file_path && Storage::exists($activity->file_path)) Storage::delete($activity->file_path);
        $activity->delete();
        return back()->with('success', 'Activity removed.');
    }
}
