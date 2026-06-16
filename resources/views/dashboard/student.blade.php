@extends('layouts.dashboard')
@section('title', 'Student Dashboard')
@section('content')
<div class="mb-8">
    <h1 style="font-size:30px;color:var(--navy);">Hi, {{ Auth::user()->name }}! 👋</h1>
    <p style="color:var(--muted);margin-top:4px;">Welcome to your learning space.</p>
</div>
<div class="card text-center" style="padding:48px;">
    <div style="font-size:48px;margin-bottom:16px;">🎓</div>
    <h3 style="color:var(--navy);margin-bottom:8px;">Your courses will appear here</h3>
    <p style="color:var(--muted);margin-bottom:24px;">Ask your parent to enroll you in a program to get started!</p>
    <a href="{{ route('courses') }}" class="btn-purple">Explore Programs</a>
</div>
@endsection
