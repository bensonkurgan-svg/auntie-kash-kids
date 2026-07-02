@extends('layouts.dashboard')
@section('title', 'Master Calendar')
@section('content')
<div class="dash-header">
    <div><h1 class="dash-title">Master Calendar</h1><p class="dash-sub">Classes, workshops, and special events at a glance.</p></div>
    <button class="btn-primary" onclick="document.getElementById('newEvent').scrollIntoView({behavior:'smooth'})">➕ Add Event</button>
</div>

<div class="dash-tabs">
    <button class="dash-tab active" data-tab="week">🗓️ Week</button>
    <button class="dash-tab" data-tab="month">📆 Month</button>
    <button class="dash-tab" data-tab="year">📅 Year</button>
    <button class="dash-tab" data-tab="today">📍 Today</button>
    <button class="dash-tab" data-tab="upcoming">⏭️ Upcoming</button>
</div>

{{-- Week view --}}
<div class="tab-panel active" data-panel="week">
    <div class="flex items-center justify-between mb-4" style="flex-wrap:wrap;gap:10px;">
        <a href="{{ route('calendar', ['week' => $weekStart->copy()->subWeek()->toDateString()]) }}" class="btn-secondary" style="min-height:40px;">← Prev</a>
        <strong style="color:var(--navy);">{{ $weekStart->format('M j') }} – {{ $weekEnd->format('M j, Y') }}</strong>
        <a href="{{ route('calendar', ['week' => $weekStart->copy()->addWeek()->toDateString()]) }}" class="btn-secondary" style="min-height:40px;">Next →</a>
    </div>
    <div class="cal-week">
        @foreach($days as $day)
            <div class="cal-day {{ $day->date->isToday() ? 'cal-today' : '' }}">
                <div class="cal-day-head">
                    <span class="cal-dow">{{ $day->date->format('D') }}</span>
                    <span class="cal-dom">{{ $day->date->format('j') }}</span>
                </div>
                <div class="cal-day-body">
                    @foreach($day->events as $e)
                        <div class="cal-chip" style="background:{{ $e->typeColor() }}22;border-left:3px solid {{ $e->typeColor() }};">
                            <strong>{{ $e->starts_at->format('g:ia') }}</strong> {{ $e->title }}
                        </div>
                    @endforeach
                    @foreach($day->sessions as $s)
                        <div class="cal-chip" style="background:#F0E8FF;border-left:3px solid #7B2FF7;">
                            <strong>{{ $s->scheduled_at?->format('g:ia') }}</strong> {{ $s->course->title ?? 'Class' }}<br>
                            <span style="font-size:10px;color:#666;">{{ $s->student->name ?? '' }} · {{ $s->tutor->name ?? '' }}</span>
                        </div>
                    @endforeach
                    @if($day->events->isEmpty() && $day->sessions->isEmpty())
                        <span class="cal-empty">—</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Month view --}}
<div class="tab-panel" data-panel="month">
    <div class="flex items-center justify-between mb-4" style="flex-wrap:wrap;gap:10px;">
        <a href="{{ route('calendar', ['month' => $monthAnchor->copy()->subMonth()->format('Y-m')]) }}#month" class="btn-secondary" style="min-height:40px;">← Prev</a>
        <strong style="color:var(--navy);font-size:18px;">{{ $monthAnchor->format('F Y') }}</strong>
        <a href="{{ route('calendar', ['month' => $monthAnchor->copy()->addMonth()->format('Y-m')]) }}#month" class="btn-secondary" style="min-height:40px;">Next →</a>
    </div>
    <div class="cal-month">
        <div class="cal-month-head">
            @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $dow)<div class="cal-dow-label">{{ $dow }}</div>@endforeach
        </div>
        @foreach($monthWeeks as $week)
            <div class="cal-month-row">
                @foreach($week as $day)
                    <div class="cal-mcell {{ $day->inMonth ? '' : 'cal-muted' }} {{ $day->date->isToday() ? 'cal-mtoday' : '' }}">
                        <a href="{{ route('calendar', ['week' => $day->date->toDateString()]) }}#week" class="cal-mdate" style="text-decoration:none;color:inherit;" title="Open this week">{{ $day->date->format('j') }}</a>
                        @foreach($day->events as $e)
                            <div class="cal-mchip" style="background:{{ $e->typeColor() }}22;color:{{ $e->typeColor() }};" title="{{ $e->title }}">{{ \Illuminate\Support\Str::limit($e->title, 14) }}</div>
                        @endforeach
                        @if($day->sessionCount > 0)
                            <div class="cal-mchip" style="background:#F0E8FF;color:#7B2FF7;">📚 {{ $day->sessionCount }} class{{ $day->sessionCount > 1 ? 'es' : '' }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
    <p class="dash-sub" style="margin-top:12px;font-size:13px;">Tip: switch to Week view for full class details on any day.</p>
</div>

{{-- Year view --}}
<div class="tab-panel" data-panel="year">
    <div class="flex items-center justify-between mb-4" style="flex-wrap:wrap;gap:10px;">
        <a href="{{ route('calendar', ['year' => $yearAnchor - 1]) }}#year" class="btn-secondary" style="min-height:40px;">← {{ $yearAnchor - 1 }}</a>
        <strong style="color:var(--navy);font-size:20px;">{{ $yearAnchor }}</strong>
        <a href="{{ route('calendar', ['year' => $yearAnchor + 1]) }}#year" class="btn-secondary" style="min-height:40px;">{{ $yearAnchor + 1 }} →</a>
    </div>
    <div class="cal-year">
        @foreach($yearMonths as $ym)
            <a href="{{ route('calendar', ['month' => $ym->month->format('Y-m')]) }}#month" class="cal-ymonth {{ $ym->isCurrent ? 'cal-ycurrent' : '' }}">
                <div class="cal-ymname">{{ $ym->month->format('F') }}</div>
                <div class="cal-ystats">
                    <span class="cal-ystat" style="color:#7B2FF7;">📚 {{ $ym->sessionCount }}</span>
                    <span class="cal-ystat" style="color:#E67E22;">🎉 {{ $ym->eventCount }}</span>
                </div>
            </a>
        @endforeach
    </div>
    <p class="dash-sub" style="margin-top:14px;font-size:13px;">📚 classes · 🎉 events. Click any month to open it.</p>
</div>

{{-- Today --}}
<div class="tab-panel" data-panel="today">
    <div class="panel">
        <div class="panel-head"><div class="panel-title">📍 Today's Classes — {{ now()->format('l, j F') }}</div></div>
        <div class="panel-body" style="overflow-x:auto;">
            <table class="dtable">
                <thead><tr><th>Time</th><th>Program</th><th>Student</th><th>Instructor</th><th>Status</th></tr></thead>
                <tbody>
                @forelse($todaySessions as $s)
                    <tr>
                        <td data-label="Time" style="font-weight:700;">{{ $s->scheduled_at?->format('g:i a') ?? '—' }}</td>
                        <td data-label="Program">{{ $s->course->icon ?? '' }} {{ $s->course->title ?? '—' }}</td>
                        <td data-label="Student">{{ $s->student->name ?? '—' }}</td>
                        <td data-label="Instructor">{{ $s->tutor->name ?? '—' }}</td>
                        <td data-label="Status"><span class="status-pill {{ $s->status=='ABSENT'?'st-DRAFT':'st-ACTIVE' }}">{{ $s->status }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:30px;">No classes scheduled today.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Upcoming --}}
<div class="tab-panel" data-panel="upcoming">
    <div class="grid md:grid-2 gap-6">
        <div class="panel">
            <div class="panel-head"><div class="panel-title">⏭️ Upcoming Classes</div></div>
            <div class="panel-body" style="overflow-x:auto;">
                <table class="dtable">
                    <thead><tr><th>When</th><th>Program</th><th>Student</th></tr></thead>
                    <tbody>
                    @forelse($upcomingSessions as $s)
                        <tr>
                            <td data-label="When" style="font-size:13px;">{{ $s->scheduled_at?->format('M j, g:i a') }}</td>
                            <td data-label="Program">{{ $s->course->title ?? '—' }}</td>
                            <td data-label="Student">{{ $s->student->name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="text-align:center;color:var(--muted);padding:24px;">No upcoming classes.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel">
            <div class="panel-head"><div class="panel-title">🎉 Upcoming Events</div></div>
            <div class="panel-body padded">
                @forelse($upcomingEvents as $e)
                    <div class="flex items-center justify-between" style="padding:12px;border-radius:10px;background:var(--surface);margin-bottom:8px;">
                        <div>
                            <div style="font-weight:700;color:var(--navy);">{{ $e->title }}</div>
                            <div class="dash-sub" style="font-size:12px;">{{ $e->starts_at->format('M j, Y · g:i a') }}{{ $e->location ? " · {$e->location}" : '' }}</div>
                        </div>
                        <span class="status-pill" style="background:{{ $e->typeColor() }}22;color:{{ $e->typeColor() }};">{{ $e->typeLabel() }}</span>
                    </div>
                @empty
                    <p class="dash-sub text-center" style="padding:20px;">No upcoming events.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Add event --}}
<div class="panel" id="newEvent" style="border:2px dashed #C9B8F0;">
    <div class="panel-head"><div class="panel-title">➕ Add Event / Workshop</div></div>
    <div class="panel-body padded">
        <form method="POST" action="{{ route('calendar.event.store') }}">@csrf
            <div class="grid md:grid-2 gap-4 mb-3">
                <div><label class="label">Title</label><input type="text" name="title" class="input" required></div>
                <div><label class="label">Type</label><select name="type" class="input"><option value="SPECIAL_EVENT">Special Event</option><option value="WORKSHOP">Workshop</option><option value="HOLIDAY">Holiday</option><option value="ANNOUNCEMENT">Announcement</option></select></div>
            </div>
            <div class="mb-3"><label class="label">Description</label><textarea name="description" class="input" rows="2"></textarea></div>
            <div class="grid md:grid-3 gap-4 mb-3">
                <div><label class="label">Starts</label><input type="datetime-local" name="starts_at" class="input" required></div>
                <div><label class="label">Ends (optional)</label><input type="datetime-local" name="ends_at" class="input"></div>
                <div><label class="label">Location</label><input type="text" name="location" class="input" placeholder="e.g. Zoom / Online"></div>
            </div>
            <label class="flex items-center gap-2 mb-4" style="font-size:14px;"><input type="checkbox" name="is_public" value="1" checked> Show on public Events page</label>
            <button class="btn-primary">Add to Calendar</button>
        </form>
    </div>
</div>

<style>
.cal-week { display:grid; grid-template-columns:repeat(7,1fr); gap:8px; }
.cal-day { background:#fff; border:1px solid var(--border); border-radius:12px; min-height:160px; overflow:hidden; display:flex; flex-direction:column; }
.cal-today { border-color:var(--purple); box-shadow:0 0 0 2px rgba(123,47,247,0.15); }
.cal-day-head { padding:8px; text-align:center; border-bottom:1px solid var(--border); background:var(--surface); }
.cal-today .cal-day-head { background:#F0E8FF; }
.cal-dow { display:block; font-size:11px; color:var(--muted); text-transform:uppercase; font-weight:700; }
.cal-dom { font-size:18px; font-weight:700; color:var(--navy); }
.cal-day-body { padding:6px; flex:1; display:flex; flex-direction:column; gap:4px; }
.cal-chip { font-size:11px; padding:5px 7px; border-radius:6px; line-height:1.3; color:#333; }
.cal-empty { color:#ccc; text-align:center; margin:auto; }

/* Month view */
.cal-month { border:1px solid var(--border); border-radius:12px; overflow:hidden; }
.cal-month-head { display:grid; grid-template-columns:repeat(7,1fr); background:var(--surface); }
.cal-dow-label { padding:8px; text-align:center; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; }
.cal-month-row { display:grid; grid-template-columns:repeat(7,1fr); }
.cal-mcell { min-height:92px; border-top:1px solid var(--border); border-left:1px solid var(--border); padding:5px; display:flex; flex-direction:column; gap:3px; }
.cal-mcell:nth-child(7n+1) { border-left:none; }
.cal-muted { background:#FAFAFB; }
.cal-muted .cal-mdate { color:#ccc; }
.cal-mtoday { background:#F0E8FF; }
.cal-mdate { font-size:13px; font-weight:700; color:var(--navy); }
.cal-mchip { font-size:10px; padding:2px 5px; border-radius:5px; line-height:1.3; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; }

/* Year view */
.cal-year { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; }
.cal-ymonth { display:block; padding:18px; border:1px solid var(--border); border-radius:14px; background:#fff; text-align:center; transition:all 160ms; }
.cal-ymonth:hover { border-color:var(--purple); box-shadow:0 6px 16px rgba(123,47,247,0.12); transform:translateY(-2px); }
.cal-ycurrent { border-color:var(--purple); background:#F0E8FF; }
.cal-ymname { font-weight:700; color:var(--navy); font-size:16px; margin-bottom:8px; }
.cal-ystats { display:flex; justify-content:center; gap:12px; }
.cal-ystat { font-size:13px; font-weight:600; }

@media (max-width:768px){
    .cal-mcell { min-height:64px; }
    .cal-year { grid-template-columns:repeat(2,1fr); }
}
@media (max-width:768px){ .cal-week { grid-template-columns:1fr; } .cal-day { min-height:auto; } }
</style>
<script>
function activateTab(name){
    var btn = document.querySelector('.dash-tab[data-tab="'+name+'"]');
    var panel = document.querySelector('.tab-panel[data-panel="'+name+'"]');
    if(!btn || !panel) return;
    document.querySelectorAll('.dash-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    panel.classList.add('active');
}
document.querySelectorAll('.dash-tab').forEach(tab => {
    tab.addEventListener('click', () => activateTab(tab.dataset.tab));
});
// On load, honor #month / #year / #week hash (used by prev/next navigation links)
(function(){
    var h = (window.location.hash || '').replace('#','');
    if(['week','month','year','today','upcoming'].includes(h)) activateTab(h);
})();
</script>
@endsection
