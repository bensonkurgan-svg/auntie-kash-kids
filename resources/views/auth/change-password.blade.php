@extends('layouts.app')
@section('title', 'Change Your Password')
@section('content')
<section class="section" style="background:var(--surface);min-height:70vh;display:flex;align-items:center;">
    <div class="container" style="max-width:460px;">
        <div class="card" style="padding:40px;">
            <div class="text-center mb-6">
                <div style="font-size:44px;margin-bottom:8px;">🔐</div>
                <h1 style="font-size:26px;color:var(--navy);">Set Your New Password</h1>
                <p style="color:var(--muted);font-size:14px;margin-top:6px;">For your security, please change the temporary password you were given before continuing.</p>
            </div>
            @if($errors->any())
                <div style="background:#FFF0F0;border:1px solid #FFB0B0;color:#C0392B;padding:12px;border-radius:12px;margin-bottom:16px;font-size:14px;">
                    {{ $errors->first() }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.change') }}">@csrf
                <div class="mb-4">
                    <label class="label">Current (temporary) password</label>
                    <input type="password" name="current_password" class="input" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="label">New password</label>
                    <input type="password" name="password" class="input" required minlength="8">
                    <p style="font-size:12px;color:var(--muted);margin-top:6px;">At least 8 characters, with letters and numbers.</p>
                </div>
                <div class="mb-6">
                    <label class="label">Confirm new password</label>
                    <input type="password" name="password_confirmation" class="input" required>
                </div>
                <button type="submit" class="btn-primary w-full">Update Password & Continue</button>
            </form>
        </div>
    </div>
</section>
@endsection
