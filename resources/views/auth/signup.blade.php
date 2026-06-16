@extends('layouts.app')
@section('title', 'Sign Up')
@section('content')
<section class="section" style="background:var(--surface);min-height:70vh;display:flex;align-items:center;">
    <div class="container" style="max-width:440px;">
        <div class="card" style="padding:40px;">
            <h1 style="font-size:28px;color:var(--navy);margin-bottom:8px;text-align:center;">Join the Family! 🌈</h1>
            <p style="text-align:center;color:var(--muted);margin-bottom:24px;font-size:15px;">Create your free parent account</p>
            @if($errors->any())
                <div style="background:#FFF0F0;border:1px solid #FFB0B0;color:#C0392B;padding:12px;border-radius:12px;margin-bottom:16px;font-size:14px;">
                    {{ $errors->first() }}
                </div>
            @endif
            <form method="POST" action="{{ route('signup') }}">@csrf
                <div class="mb-4">
                    <label class="label">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="input" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input" required>
                </div>
                <div class="mb-4">
                    <label class="label">Password</label>
                    <input type="password" name="password" class="input" required minlength="8">
                </div>
                <div class="mb-6">
                    <label class="label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="input" required>
                </div>
                <button type="submit" class="btn-primary w-full">Create Account</button>
            </form>
            <p style="text-align:center;margin-top:20px;font-size:14px;color:var(--muted);">
                Already have an account? <a href="{{ route('login') }}" style="color:var(--purple);font-weight:700;">Log in</a>
            </p>
        </div>
    </div>
</section>
@endsection
