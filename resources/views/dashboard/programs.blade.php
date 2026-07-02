@extends('layouts.dashboard')
@section('title', 'Programs')
@section('content')
<div class="dash-header">
    <div>
        <h1 class="dash-title">Program Manager</h1>
        <p class="dash-sub">Add, edit, and structure your learning programs.</p>
    </div>
    <button class="btn-primary" onclick="document.getElementById('newProgram').scrollIntoView({behavior:'smooth'})">➕ New Program</button>
</div>

@foreach($courses as $course)
<div class="panel">
    <div class="panel-head">
        <div class="panel-title">{{ $course->icon }} {{ $course->title }}</div>
        <div class="flex gap-2 items-center">
            <span class="status-pill {{ $course->is_published ? 'st-PUBLISHED' : 'st-DRAFT' }}">{{ $course->is_published ? 'Published' : 'Draft' }}</span>
            <span class="dash-sub">{{ $course->enrollments_count }} students</span>
        </div>
    </div>
    <div class="panel-body padded">
        <div class="grid md:grid-2 gap-6">
            {{-- Edit form --}}
            <form method="POST" action="{{ route('programs.update', $course) }}">@csrf @method('PUT')
                <div class="mb-2"><label class="label">Title</label><input type="text" name="title" value="{{ $course->title }}" class="input" required></div>
                <div class="mb-2"><label class="label">Description</label><textarea name="description" class="input" rows="2" required>{{ $course->description }}</textarea></div>
                <div class="flex gap-3 mb-2">
                    <div style="flex:0 0 80px;"><label class="label">Icon</label><input type="text" name="icon" value="{{ $course->icon }}" class="input"></div>
                    <div style="flex:1;"><label class="label">Price ($)</label><input type="number" step="0.01" name="price" value="{{ $course->price }}" class="input"></div>
                </div>
                <div class="mb-3"><label class="label">Tutor</label>
                    <select name="tutor_profile_id" class="input">
                        @foreach($tutors as $tp)<option value="{{ $tp->id }}" {{ $course->tutor_profile_id==$tp->id?'selected':'' }}>{{ $tp->user->name ?? 'Tutor' }}</option>@endforeach
                    </select>
                </div>
                <label class="flex items-center gap-2 mb-3" style="font-size:14px;"><input type="checkbox" name="is_published" value="1" {{ $course->is_published?'checked':'' }}> Published</label>
                <div class="flex gap-2">
                    <button class="btn-primary" style="min-height:42px;">Save</button>
                    <button type="button" class="btn-secondary" style="min-height:42px;" onclick="if(confirm('Delete this program?')){document.getElementById('del{{$course->id}}').submit();}">Delete</button>
                </div>
            </form>
            {{-- Modules --}}
            <div>
                <h4 style="color:var(--navy);margin-bottom:10px;">Modules ({{ $course->modules->count() }})</h4>
                @foreach($course->modules as $m)
                    <div class="flex items-center justify-between" style="padding:8px 12px;background:var(--surface);border-radius:10px;margin-bottom:6px;">
                        <span style="font-size:14px;">{{ $m->title }} <span class="dash-sub">({{ $m->lessons->count() }} lessons, {{ $m->materials->count() }} materials)</span></span>
                        <form method="POST" action="{{ route('programs.module.delete',$m) }}" onsubmit="return confirm('Remove module?');">@csrf @method('DELETE')<button style="background:none;border:none;color:#c0392b;cursor:pointer;font-size:16px;">×</button></form>
                    </div>
                @endforeach
                <form method="POST" action="{{ route('programs.module.add',$course) }}" class="flex gap-2 mt-2">@csrf
                    <input type="text" name="title" class="input" placeholder="New module title" required style="flex:1;">
                    <button class="btn-secondary" style="min-height:44px;">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>
<form id="del{{$course->id}}" method="POST" action="{{ route('programs.destroy',$course) }}" style="display:none;">@csrf @method('DELETE')</form>
@endforeach

{{-- New program --}}
<div class="panel" id="newProgram" style="border:2px dashed #C9B8F0;">
    <div class="panel-head"><div class="panel-title">➕ Create New Program</div></div>
    <div class="panel-body padded">
        <form method="POST" action="{{ route('programs.store') }}">@csrf
            <div class="grid md:grid-2 gap-4">
                <div><label class="label">Title</label><input type="text" name="title" class="input" required></div>
                <div><label class="label">Tutor</label><select name="tutor_profile_id" class="input" required>@foreach($tutors as $tp)<option value="{{ $tp->id }}">{{ $tp->user->name ?? 'Tutor' }}</option>@endforeach</select></div>
            </div>
            <div class="mb-3 mt-3"><label class="label">Description</label><textarea name="description" class="input" rows="2" required></textarea></div>
            <div class="flex gap-3 mb-3">
                <div style="flex:0 0 90px;"><label class="label">Icon</label><input type="text" name="icon" class="input" placeholder="📚"></div>
                <div style="flex:1;"><label class="label">Price ($)</label><input type="number" step="0.01" name="price" class="input" value="0" required></div>
            </div>
            <button class="btn-primary">Create Program</button>
        </form>
    </div>
</div>
@endsection
