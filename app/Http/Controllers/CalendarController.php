<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Event;
use App\Models\ClassSession;

class CalendarController extends Controller
{
    /** CEO/Admin master calendar: classes + events, day/week/upcoming. */
    public function index(Request $request)
    {
        if (! Auth::user()->canManageContent()) abort(403);

        // Week anchor (default: this week)
        $weekStart = $request->filled('week')
            ? Carbon::parse($request->input('week'))->startOfWeek()
            : now()->startOfWeek();
        $weekEnd = (clone $weekStart)->endOfWeek();

        // Class sessions in this week
        $sessions = ClassSession::with(['course','tutor','student'])
            ->whereBetween('scheduled_at', [$weekStart, $weekEnd])
            ->orderBy('scheduled_at')->get();

        // Events in this week
        $events = Event::whereBetween('starts_at', [$weekStart, $weekEnd])
            ->orderBy('starts_at')->get();

        // Build a day-by-day structure for the week grid
        $days = [];
        for ($d = 0; $d < 7; $d++) {
            $date = (clone $weekStart)->addDays($d);
            $days[] = (object) [
                'date'     => $date,
                'sessions' => $sessions->filter(fn($s) => $s->scheduled_at && $s->scheduled_at->isSameDay($date)),
                'events'   => $events->filter(fn($e) => $e->starts_at->isSameDay($date)),
            ];
        }

        // Today's classes + upcoming (next 10)
        $todaySessions = ClassSession::with(['course','tutor','student'])
            ->whereDate('scheduled_at', today())->orderBy('scheduled_at')->get();
        $upcomingEvents = Event::upcoming()->orderBy('starts_at')->take(8)->get();
        $upcomingSessions = ClassSession::with(['course','tutor','student'])
            ->where('scheduled_at', '>=', now())->orderBy('scheduled_at')->take(10)->get();

        // ── Month view ──────────────────────────────────────────────────────
        $monthAnchor = $request->filled('month')
            ? Carbon::parse($request->input('month').'-01')->startOfMonth()
            : now()->startOfMonth();
        $monthGridStart = (clone $monthAnchor)->startOfMonth()->startOfWeek();
        $monthGridEnd   = (clone $monthAnchor)->endOfMonth()->endOfWeek();

        $monthSessions = ClassSession::with(['course','student'])
            ->whereBetween('scheduled_at', [$monthGridStart, $monthGridEnd])->get();
        $monthEvents = Event::whereBetween('starts_at', [$monthGridStart, $monthGridEnd])->get();

        $monthWeeks = [];
        $cursor = clone $monthGridStart;
        while ($cursor <= $monthGridEnd) {
            $week = [];
            for ($d = 0; $d < 7; $d++) {
                $week[] = (object) [
                    'date'        => clone $cursor,
                    'inMonth'     => $cursor->month === $monthAnchor->month,
                    'sessionCount'=> $monthSessions->filter(fn($s) => $s->scheduled_at && $s->scheduled_at->isSameDay($cursor))->count(),
                    'events'      => $monthEvents->filter(fn($e) => $e->starts_at->isSameDay($cursor)),
                ];
                $cursor->addDay();
            }
            $monthWeeks[] = $week;
        }

        // ── Year view ───────────────────────────────────────────────────────
        $yearAnchor = $request->filled('year')
            ? (int) $request->input('year')
            : now()->year;
        $yearStart = Carbon::create($yearAnchor, 1, 1)->startOfDay();
        $yearEnd   = Carbon::create($yearAnchor, 12, 31)->endOfDay();

        $yearSessions = ClassSession::whereBetween('scheduled_at', [$yearStart, $yearEnd])->get();
        $yearEvents   = Event::whereBetween('starts_at', [$yearStart, $yearEnd])->get();

        $yearMonths = [];
        for ($m = 1; $m <= 12; $m++) {
            $mStart = Carbon::create($yearAnchor, $m, 1);
            $yearMonths[] = (object) [
                'month'        => $mStart,
                'sessionCount' => $yearSessions->filter(fn($s) => $s->scheduled_at && $s->scheduled_at->month === $m)->count(),
                'eventCount'   => $yearEvents->filter(fn($e) => $e->starts_at->month === $m)->count(),
                'isCurrent'    => ($yearAnchor === now()->year && $m === now()->month),
            ];
        }

        return view('dashboard.calendar', compact(
            'days','weekStart','weekEnd','todaySessions','upcomingEvents','upcomingSessions',
            'monthAnchor','monthWeeks','yearAnchor','yearMonths'
        ));
    }

    public function storeEvent(Request $request)
    {
        if (! Auth::user()->canManageContent()) abort(403);
        $data = $request->validate([
            'title'       => ['required','string','max:255'],
            'description' => ['nullable','string','max:1000'],
            'type'        => ['required','in:WORKSHOP,SPECIAL_EVENT,HOLIDAY,ANNOUNCEMENT'],
            'starts_at'   => ['required','date'],
            'ends_at'     => ['nullable','date','after_or_equal:starts_at'],
            'location'    => ['nullable','string','max:255'],
            'is_public'   => ['nullable','boolean'],
        ]);
        $data['is_public'] = $request->boolean('is_public');
        $data['created_by'] = Auth::id();
        Event::create($data);
        return back()->with('success', 'Event added to the calendar.');
    }

    public function destroyEvent(Event $event)
    {
        if (! Auth::user()->canManageContent()) abort(403);
        $event->delete();
        return back()->with('success', 'Event removed.');
    }
}
