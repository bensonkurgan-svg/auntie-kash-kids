@extends('layouts.dashboard')
@section('title', 'My Profile')
@section('content')
<div class="dash-header">
    <div><h1 class="dash-title">My Profile</h1><p class="dash-sub">Update your information and photo.</p></div>
</div>

<div class="panel" style="max-width:680px;">
    <div class="panel-body padded">
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">@csrf
            <div class="flex items-center gap-4 mb-6" style="flex-wrap:wrap;">
                @if($user->photo())
                    <img src="{{ $user->photo() }}" style="width:84px;height:84px;border-radius:50%;object-fit:cover;border:3px solid var(--purple);">
                @else
                    <span style="width:84px;height:84px;border-radius:50%;background:linear-gradient(135deg,#7B2FF7,#FF3E9E);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:30px;">{{ strtoupper(substr($user->name,0,2)) }}</span>
                @endif
                <div>
                    <label class="label">Profile Photo</label>
                    <input type="file" name="photo" accept="image/*" class="input">
                    <p style="font-size:12px;color:var(--muted);margin-top:4px;">Shows wherever you're referenced on the site.</p>
                </div>
            </div>

            <div class="grid md:grid-2 gap-4 mb-4">
                <div><label class="label">Full Name</label><input type="text" name="name" value="{{ $user->name }}" class="input" required></div>
                <div><label class="label">Phone</label><input type="text" name="phone" value="{{ $user->phone }}" class="input"></div>
            </div>
            <div class="mb-4"><label class="label">Work Email</label><input type="email" name="work_email" value="{{ $user->work_email }}" class="input"></div>

            @if($profile)
                <div class="mb-4"><label class="label">Bio</label><textarea name="bio" class="input" rows="4">{{ $profile->bio }}</textarea></div>
                <div class="grid md:grid-2 gap-4 mb-4">
                    <div><label class="label">Qualifications</label><input type="text" name="qualifications" value="{{ $profile->qualifications }}" class="input" placeholder="e.g. B.Ed, 8 yrs experience"></div>
                    <div><label class="label">Country</label><input type="text" name="country" value="{{ $profile->country }}" class="input"></div>
                </div>
                <div class="mb-4"><label class="label">Availability</label><input type="text" name="availability" value="{{ $profile->availability }}" class="input" placeholder="e.g. Weekdays 4–8pm GMT"></div>
            @endif

            @if($user->isCEO())
                <div class="mb-4" style="padding:16px;background:var(--surface);border-radius:12px;">
                    <label class="label">Your Photo for the About Page</label>
                    <div class="flex items-center gap-4" style="flex-wrap:wrap;">
                        @php $founderPhoto = \App\Models\SiteSetting::get('founder_photo'); @endphp
                        <div style="width:90px;height:90px;border-radius:50%;overflow:hidden;background:#Eee;border:3px solid var(--pink);flex-shrink:0;">
                            @if($founderPhoto)<img src="{{ $founderPhoto }}" style="width:100%;height:100%;object-fit:cover;">@else<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:32px;color:#bbb;">👤</div>@endif
                        </div>
                        <div style="flex:1;min-width:200px;">
                            <input type="file" name="founder_photo" accept="image/*" class="input">
                            <p style="font-size:12px;color:var(--muted);margin-top:4px;">This appears on the public About page. Square images work best.</p>
                        </div>
                    </div>
                </div>
            @endif

            <button class="btn-primary">Save Profile</button>
        </form>
    </div>
</div>

{{-- Change password --}}
<div class="panel" style="max-width:680px;">
    <div class="panel-head"><div class="panel-title">🔒 Change Password</div></div>
    <div class="panel-body padded">
        <form method="POST" action="{{ route('password.change') }}">@csrf
            <div class="mb-4"><label class="label">Current Password</label><input type="password" name="current_password" class="input" required></div>
            <div class="grid md:grid-2 gap-4 mb-4">
                <div><label class="label">New Password</label><input type="password" name="password" class="input" required minlength="8"></div>
                <div><label class="label">Confirm New Password</label><input type="password" name="password_confirmation" class="input" required></div>
            </div>
            <button class="btn-secondary">Update Password</button>
        </form>
    </div>
</div>
@endsection
