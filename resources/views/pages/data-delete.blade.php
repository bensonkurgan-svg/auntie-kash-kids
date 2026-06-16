@extends('layouts.app')
@section('title', 'Delete My Data')
@section('content')
<section class="section">
    <div class="container" style="max-width:560px;">
        <div class="card text-center" style="padding:40px;">
            <div style="font-size:48px;margin-bottom:16px;">🗑️</div>
            <h1 style="font-size:28px;color:var(--navy);margin-bottom:12px;">Delete Your Account</h1>
            <p style="color:#666;margin-bottom:24px;line-height:1.7;">This will permanently delete your account and all associated data. This action cannot be undone.</p>
            <form method="POST" action="{{ route('data-delete') }}" onsubmit="return confirm('Are you absolutely sure? This permanently deletes your account and cannot be undone.');">@csrf
                <button type="submit" class="btn-primary w-full" style="background:#C0392B;">Permanently Delete My Account</button>
            </form>
        </div>
    </div>
</section>
@endsection
