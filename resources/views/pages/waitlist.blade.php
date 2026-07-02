@extends('layouts.app')
@section('title', 'Waitlist Corner')
@section('content')
<section class="hero-bg" style="padding:56px 0;">
    <div class="container text-center">
        <span class="welcome-pill">WAITLIST CORNER</span>
        <h1 style="font-size:clamp(30px,4vw,48px);color:var(--navy);margin:12px 0;">Be First in Line ✨</h1>
        <p style="color:#555;font-size:18px;max-width:620px;margin:0 auto;">Join our waitlist and become one of our founding families. Get early access, special perks, and launch announcements.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid md:grid-2 gap-8" style="align-items:start;">
            <div>
                <h2 style="font-size:26px;color:var(--navy);margin-bottom:16px;">Why Join the Waitlist?</h2>
                <div class="flex flex-col gap-4">
                    <div class="flex gap-3"><span style="font-size:28px;">🥇</span><div><strong style="color:var(--navy);">Founding Family Status</strong><p style="color:#666;font-size:14px;">Be among the very first to shape our community.</p></div></div>
                    <div class="flex gap-3"><span style="font-size:28px;">🎁</span><div><strong style="color:var(--navy);">Early-Bird Perks</strong><p style="color:#666;font-size:14px;">Special offers and priority enrolment for new programs.</p></div></div>
                    <div class="flex gap-3"><span style="font-size:28px;">📣</span><div><strong style="color:var(--navy);">Launch Announcements</strong><p style="color:#666;font-size:14px;">Be the first to know when new programs open.</p></div></div>
                    <div class="flex gap-3"><span style="font-size:28px;">💛</span><div><strong style="color:var(--navy);">Help Shape the Vision</strong><p style="color:#666;font-size:14px;">Your feedback guides what we build next.</p></div></div>
                </div>
            </div>

            <div class="card">
                <h3 style="font-size:22px;color:var(--navy);margin-bottom:16px;">Join the Waitlist</h3>
                @if(session('success'))<div style="background:#F0FFF4;border:1px solid #7ED321;color:#2d6a0f;padding:12px;border-radius:10px;margin-bottom:16px;font-weight:600;">{{ session('success') }}</div>@endif
                @if($errors->any())<div style="background:#FFF0F0;border:1px solid #FFB0B0;color:#c0392b;padding:12px;border-radius:10px;margin-bottom:16px;">{{ $errors->first() }}</div>@endif
                <form method="POST" action="{{ route('waitlist.submit') }}">@csrf
                    <div class="mb-3"><label class="label">Your Name *</label><input type="text" name="parent_name" class="input" required></div>
                    <div class="grid md:grid-2 gap-3 mb-3">
                        <div><label class="label">Email *</label><input type="email" name="email" class="input" required></div>
                        <div><label class="label">Phone</label><input type="text" name="phone" class="input"></div>
                    </div>
                    <div class="grid md:grid-2 gap-3 mb-3">
                        <div><label class="label">Child's Name</label><input type="text" name="child_name" class="input"></div>
                        <div><label class="label">Child's Age</label><input type="number" name="child_age" class="input" min="3" max="18"></div>
                    </div>
                    <div class="mb-3"><label class="label">Program of Interest</label><input type="text" name="program_interest" class="input" placeholder="e.g. Reading, Public Speaking"></div>
                    <div class="mb-3">
                        <label class="label">I'd like to join as</label>
                        <select name="type" class="input">
                            <option value="WAITLIST">Waitlist member</option>
                            <option value="FOUNDING_FAMILY">Founding Family</option>
                        </select>
                    </div>
                    <div class="mb-4"><label class="label">Anything else? (optional)</label><textarea name="message" class="input" rows="2"></textarea></div>
                    <button class="btn-pink w-full">Join the Waitlist 🌈</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
