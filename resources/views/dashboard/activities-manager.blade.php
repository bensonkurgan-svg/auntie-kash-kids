@extends('layouts.dashboard')
@section('title', 'Activities')
@section('content')
@php
    $types = ['WORD_SEARCH'=>'Word Search','CROSSWORD'=>'Crossword','MATCHING'=>'Matching Game','MAZE'=>'Maze','I_SPY'=>'I Spy','SPOT_DIFFERENCE'=>'Spot the Difference','COLOURING'=>'Colouring','WORKSHEET'=>'Worksheet','OTHER'=>'Other'];
    $ageBrackets = ['3-7','8-12','9-16'];
@endphp
<div class="dash-header">
    <div><h1 class="dash-title">Activities</h1><p class="dash-sub">Printable learning activities — themes, age brackets, photos & PDFs.</p></div>
    <a href="{{ route('activities') }}" target="_blank" class="btn-secondary" style="min-height:42px;">View Public Page ↗</a>
</div>

<div class="panel">
    <div class="panel-head"><div class="panel-title">➕ Add Activity</div></div>
    <div class="panel-body padded">
        <form method="POST" action="{{ route('activities.store') }}" enctype="multipart/form-data">@csrf
            <div class="grid md:grid-2 gap-4 mb-3">
                <div><label class="label">Title *</label><input type="text" name="title" class="input" required placeholder="e.g. Jungle Animals Word Search"></div>
                <div><label class="label">Theme</label><input type="text" name="theme" class="input" placeholder="e.g. Animals, Pirates, Grammar"></div>
            </div>
            <div class="grid md:grid-2 gap-4 mb-3">
                <div><label class="label">Age Bracket</label>
                    <select name="age_bracket" class="input">
                        <option value="">Select…</option>
                        @foreach($ageBrackets as $b)<option value="{{ $b }}">{{ $b }} years</option>@endforeach
                    </select>
                </div>
                <div><label class="label">Activity Type</label>
                    <select name="activity_type" class="input" required>
                        @foreach($types as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3"><label class="label">Description / Instructions</label><textarea name="description" class="input" rows="2"></textarea></div>
            <div class="grid md:grid-2 gap-4 mb-4">
                <div><label class="label">Preview Photo</label><input type="file" name="image" accept="image/*" class="input"></div>
                <div><label class="label">Downloadable PDF</label><input type="file" name="file" accept=".pdf" class="input"></div>
            </div>
            <button class="btn-primary">Add Activity</button>
            <p style="font-size:12px;color:var(--muted);margin-top:8px;">Upload a photo, a PDF, or both. The photo shows as a preview; the PDF is what families download.</p>
        </form>
    </div>
</div>

<div class="panel">
    <div class="panel-head"><div class="panel-title">All Activities</div><span class="dash-sub">{{ $activities->count() }} total</span></div>
    <div class="panel-body" style="overflow-x:auto;">
        <table class="dtable">
            <thead><tr><th>Activity</th><th>Theme</th><th>Age</th><th>Type</th><th>Files</th><th style="text-align:right;">Action</th></tr></thead>
            <tbody>
            @forelse($activities as $a)
                <tr>
                    <td data-label="Activity">
                        <div class="user-cell">
                            @if($a->image_path)<img src="{{ $a->image_path }}" style="width:44px;height:44px;border-radius:8px;object-fit:cover;">@else<span style="font-size:26px;">🧩</span>@endif
                            <span class="nm">{{ $a->title }}</span>
                        </div>
                    </td>
                    <td data-label="Theme">{{ $a->theme ?: '—' }}</td>
                    <td data-label="Age">{{ $a->age_bracket ? $a->age_bracket.' yrs' : '—' }}</td>
                    <td data-label="Type">{{ $a->typeLabel() }}</td>
                    <td data-label="Files" style="font-size:13px;">{{ $a->image_path ? '🖼️' : '' }} {{ $a->file_path ? '📄' : '' }}{{ !$a->image_path && !$a->file_path ? '—' : '' }}</td>
                    <td data-label="Action" style="text-align:right;">
                        <div class="flex gap-2" style="justify-content:flex-end;">
                            <button type="button" onclick="var r=document.getElementById('edit-act-{{ $a->id }}');r.style.display=(r.style.display==='table-row'?'none':'table-row')" class="status-pill st-CONTACTED" style="border:none;cursor:pointer;">✏️ Edit</button>
                            <form method="POST" action="{{ route('activities.destroy',$a) }}" onsubmit="return confirm('Remove this activity?');" style="display:inline;">@csrf @method('DELETE')<button class="status-pill" style="border:none;cursor:pointer;background:#FFECEC;color:#c0392b;">Delete</button></form>
                        </div>
                    </td>
                </tr>
                <tr id="edit-act-{{ $a->id }}" style="display:none;background:#FAFAFE;">
                    <td colspan="6" style="padding:20px 22px;">
                        <form method="POST" action="{{ route('activities.update',$a) }}" enctype="multipart/form-data">@csrf @method('PUT')
                            <div class="grid md:grid-2 gap-4 mb-3">
                                <div><label class="label">Title</label><input type="text" name="title" value="{{ $a->title }}" class="input" required></div>
                                <div><label class="label">Theme</label><input type="text" name="theme" value="{{ $a->theme }}" class="input"></div>
                            </div>
                            <div class="grid md:grid-2 gap-4 mb-3">
                                <div><label class="label">Age Bracket</label><select name="age_bracket" class="input"><option value="">Select…</option>@foreach($ageBrackets as $b)<option value="{{ $b }}" {{ $a->age_bracket==$b?'selected':'' }}>{{ $b }} years</option>@endforeach</select></div>
                                <div><label class="label">Type</label><select name="activity_type" class="input">@foreach($types as $k=>$v)<option value="{{ $k }}" {{ $a->activity_type==$k?'selected':'' }}>{{ $v }}</option>@endforeach</select></div>
                            </div>
                            <div class="mb-3"><label class="label">Description</label><textarea name="description" class="input" rows="2">{{ $a->description }}</textarea></div>
                            <div class="grid md:grid-2 gap-4 mb-3">
                                <div><label class="label">Replace Photo</label><input type="file" name="image" accept="image/*" class="input"></div>
                                <div><label class="label">Replace PDF</label><input type="file" name="file" accept=".pdf" class="input"></div>
                            </div>
                            <label class="flex items-center gap-2 mb-3" style="font-size:14px;"><input type="checkbox" name="is_published" value="1" {{ $a->is_published?'checked':'' }}> Published (visible to public)</label>
                            <button class="btn-primary" style="min-height:42px;">Save Changes</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:30px;">No activities yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
