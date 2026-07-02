<!DOCTYPE html><html><head><meta charset="utf-8"><style>
body{font-family:DejaVu Sans,sans-serif;color:#222;font-size:13px;}
h1{color:#7B2FF7;font-size:22px;margin-bottom:2px;}
h2{color:#1D1B4E;font-size:15px;border-bottom:2px solid #FF3E9E;padding-bottom:4px;margin-top:24px;}
.muted{color:#888;font-size:11px;}
table{width:100%;border-collapse:collapse;margin-top:8px;}
td,th{text-align:left;padding:6px 8px;border-bottom:1px solid #eee;font-size:12px;}
th{background:#F4F2FB;color:#1D1B4E;}
</style></head><body>
<h1>Auntie Kash Kids — Data Export</h1>
<p class="muted">Generated {{ now()->toDayDateTimeString() }}</p>

<h2>Profile</h2>
<table>
@foreach($data['profile'] as $k => $v)
<tr><th style="width:30%;">{{ ucfirst($k) }}</th><td>{{ $v ?? '—' }}</td></tr>
@endforeach
</table>

<h2>Children</h2>
@if(count($data['children']))
<table><tr><th>Name</th><th>Age</th></tr>
@foreach($data['children'] as $c)<tr><td>{{ $c['name'] }}</td><td>{{ $c['age'] ?? '—' }}</td></tr>@endforeach
</table>
@else<p class="muted">No children on record.</p>@endif

<h2>Enrolments</h2>
@if(count($data['enrollments']))
<table><tr><th>Program</th><th>Progress</th><th>Status</th></tr>
@foreach($data['enrollments'] as $e)<tr><td>{{ $e['course'] }}</td><td>{{ $e['progress'] }}%</td><td>{{ $e['status'] }}</td></tr>@endforeach
</table>
@else<p class="muted">No enrolments on record.</p>@endif
</body></html>
