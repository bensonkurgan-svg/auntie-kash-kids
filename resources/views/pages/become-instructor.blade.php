@extends('layouts.app')
@section('title', 'Become an Instructor')
@section('content')
<section class="hero-bg" style="padding:56px 0;">
    <div class="container text-center">
        <span class="welcome-pill">JOIN OUR TEAM</span>
        <h1 style="font-size:clamp(30px,4vw,48px);color:var(--navy);margin:12px 0;">Become an Auntie Kash Kids Instructor</h1>
        <p style="color:#555;font-size:18px;max-width:640px;margin:0 auto;">Inspire the next generation. Teach with purpose, creativity, and joy — from anywhere in the world.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid md:grid-2 gap-8" style="align-items:start;">
            <div>
                <h2 style="font-size:28px;color:var(--navy);margin-bottom:16px;">Who We're Looking For</h2>
                <p style="color:#555;line-height:1.8;margin-bottom:24px;">We welcome passionate, qualified educators who love working with children and believe every young mind can shine. Whether you specialise in literacy, public speaking, the creative arts, leadership, or languages, we'd love to hear from you.</p>

                <h3 style="font-size:19px;color:var(--navy);margin-bottom:10px;">✅ Required Qualifications</h3>
                <ul style="list-style:none;line-height:2;color:#555;margin-bottom:24px;">
                    <li>🎓 A teaching qualification or relevant subject expertise</li>
                    <li>👶 Experience working with children (ages 5–16)</li>
                    <li>💻 Reliable internet and a quiet teaching space</li>
                    <li>❤️ Patience, warmth, and enthusiasm</li>
                    <li>🛡️ Willingness to complete safeguarding checks</li>
                </ul>

                <h3 style="font-size:19px;color:var(--navy);margin-bottom:10px;">🌟 Benefits of Teaching With Us</h3>
                <ul style="list-style:none;line-height:2;color:#555;margin-bottom:24px;">
                    <li>🌍 Teach from anywhere, flexible hours</li>
                    <li>📚 Full curriculum and resources provided</li>
                    <li>💛 Supportive, mission-driven community</li>
                    <li>📈 Professional development opportunities</li>
                </ul>

                <h3 style="font-size:19px;color:var(--navy);margin-bottom:10px;">📋 Application Process</h3>
                <ol style="line-height:2;color:#555;padding-left:20px;">
                    <li>Submit your application using the form</li>
                    <li>Initial review by our team</li>
                    <li>Interview and teaching demonstration</li>
                    <li>Safeguarding checks and onboarding</li>
                </ol>
            </div>

            {{-- Application form --}}
            <div class="card" style="position:sticky;top:90px;">
                <h3 style="font-size:22px;color:var(--navy);margin-bottom:16px;">Apply Now</h3>
                @if(session('success'))<div style="background:#F0FFF4;border:1px solid #7ED321;color:#2d6a0f;padding:12px;border-radius:10px;margin-bottom:16px;font-weight:600;">{{ session('success') }}</div>@endif
                @if($errors->any())<div style="background:#FFF0F0;border:1px solid #FFB0B0;color:#c0392b;padding:12px;border-radius:10px;margin-bottom:16px;">{{ $errors->first() }}</div>@endif
                <form method="POST" action="{{ route('become.instructor.submit') }}" enctype="multipart/form-data">@csrf
                    <div class="mb-3"><label class="label">Full Name *</label><input type="text" name="name" class="input" required></div>
                    <div class="grid md:grid-2 gap-3 mb-3">
                        <div><label class="label">Email *</label><input type="email" name="email" class="input" required></div>
                        <div><label class="label">Phone</label><input type="text" name="phone" class="input"></div>
                    </div>
                    <div class="grid md:grid-2 gap-3 mb-3">
                        <div><label class="label">Country</label><input type="text" name="country" class="input"></div>
                        <div><label class="label">Subjects You Teach</label><input type="text" name="subjects" class="input" placeholder="e.g. Reading, Drama"></div>
                    </div>
                    <div class="mb-3"><label class="label">Qualifications</label><input type="text" name="qualifications" class="input" placeholder="e.g. B.Ed, TEFL"></div>
                    <div class="mb-3"><label class="label">Teaching Experience</label><textarea name="experience" class="input" rows="3"></textarea></div>
                    <div class="mb-3"><label class="label">Why do you want to teach with us?</label><textarea name="cover_note" class="input" rows="3"></textarea></div>
                    <div class="mb-4"><label class="label">Upload CV (PDF/Word, optional)</label><input type="file" name="cv" accept=".pdf,.doc,.docx" class="input"></div>
                    <button class="btn-pink w-full">Submit Application</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
