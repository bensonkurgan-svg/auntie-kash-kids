@extends('layouts.app')
@section('title', 'Login')
@section('content')
<section class="section" style="background:var(--surface);min-height:70vh;display:flex;align-items:center;">
    <div class="container" style="max-width:440px;">
        <div class="card" style="padding:40px;">
            <h1 style="font-size:28px;color:var(--navy);margin-bottom:8px;text-align:center;">Welcome Back! 👋</h1>
            <p style="text-align:center;color:var(--muted);margin-bottom:24px;font-size:15px;">Log in to continue your learning journey</p>
            @if($errors->any())
                <div style="background:#FFF0F0;border:1px solid #FFB0B0;color:#C0392B;padding:12px;border-radius:12px;margin-bottom:16px;font-size:14px;">
                    {{ $errors->first() }}
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}">@csrf
                <div class="mb-4">
                    <label class="label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="label">Password</label>
                    <input type="password" name="password" class="input" required>
                </div>
                <label class="flex items-center gap-2 mb-6" style="font-size:14px;cursor:pointer;">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <button type="submit" class="btn-primary w-full">Log In</button>
            </form>
            <p style="text-align:center;margin-top:20px;font-size:14px;color:var(--muted);">
                Don't have an account? <a href="{{ route('signup') }}" style="color:var(--purple);font-weight:700;">Sign up</a>
            </p>
            <div style="margin-top:24px;padding:16px;background:var(--surface);border-radius:12px;font-size:12px;color:var(--muted);">
                <strong>Demo accounts:</strong><br>
                CEO: ceo@auntiekash.com<br>
                Parent: parent1@example.com<br>
                Password: DemoPass123
            </div>
        </div>
    </div>
</section>
@endsection
