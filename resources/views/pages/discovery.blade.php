@extends('layouts.app')
@section('title', 'Free Discovery Call')
@section('content')
<section class="section" style="background:linear-gradient(135deg,#F9F0FF,#FFF0FB);min-height:80vh;">
    <div class="container" style="max-width:680px;">
        <div class="text-center mb-8">
            <span class="badge badge-pink mb-4" style="display:inline-block;">Free Program Matching</span>
            <h1 style="font-size:clamp(28px,4vw,42px);color:var(--navy);margin-bottom:12px;">Find the Perfect Program for Your Child</h1>
            <p style="color:#555;font-size:16px;">Complete this short form and our team will personally recommend the best learning path — no commitment required.</p>
        </div>

        <div id="formCard" class="card" style="padding:32px;">
            {{-- Progress --}}
            <div class="flex items-center justify-between mb-8" id="progressBar">
                @foreach(['Your Info','Your Child','Interests','Goals','Scheduling'] as $i => $label)
                    <div class="flex items-center" style="flex:1;">
                        <div class="step-dot" data-step="{{ $i+1 }}" style="width:32px;height:32px;border-radius:50%;background:{{ $i===0 ? 'var(--purple)' : 'var(--border)' }};color:{{ $i===0 ? 'white' : 'var(--muted)' }};display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;flex-shrink:0;">{{ $i+1 }}</div>
                        @if($i < 4)<div style="flex:1;height:2px;background:var(--border);margin:0 4px;"></div>@endif
                    </div>
                @endforeach
            </div>

            <form id="discoveryForm">
                @csrf
                <input type="text" name="honeypot" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off">

                {{-- Step 1 --}}
                <div class="form-step" data-step="1">
                    <h2 style="font-size:22px;color:var(--navy);margin-bottom:20px;">Tell us about you</h2>
                    <div class="mb-4"><label class="label">Your Name *</label><input type="text" name="parent_name" class="input" required></div>
                    <div class="mb-4"><label class="label">Email *</label><input type="email" name="parent_email" class="input" required></div>
                    <div class="grid md:grid-2 gap-4 mb-4">
                        <div><label class="label">Phone</label><input type="tel" name="parent_phone" class="input"></div>
                        <div><label class="label">Country</label><select name="parent_country" class="input"><option value="">Select...</option>@foreach(['Nigeria','United Kingdom','Canada','USA','Ghana','Kenya','South Africa','Jamaica','Other'] as $c)<option>{{ $c }}</option>@endforeach</select></div>
                    </div>
                    <div class="mb-4"><label class="label">Preferred Contact Method</label><select name="preferred_contact" class="input"><option>Email</option><option>Phone</option><option>WhatsApp</option></select></div>
                </div>

                {{-- Step 2 --}}
                <div class="form-step" data-step="2" style="display:none;">
                    <h2 style="font-size:22px;color:var(--navy);margin-bottom:20px;">About your child</h2>
                    <div class="mb-4"><label class="label">Child's Name *</label><input type="text" name="child_name" class="input" required></div>
                    <div class="grid md:grid-2 gap-4 mb-4">
                        <div><label class="label">Age *</label><input type="number" name="child_age" class="input" min="3" max="18" required></div>
                        <div><label class="label">Grade/Year</label><input type="text" name="child_grade" class="input"></div>
                    </div>
                    <div class="mb-4"><label class="label">Primary Language</label><input type="text" name="primary_language" class="input" placeholder="e.g. English"></div>
                </div>

                {{-- Step 3 --}}
                <div class="form-step" data-step="3" style="display:none;">
                    <h2 style="font-size:22px;color:var(--navy);margin-bottom:20px;">Interests & strengths</h2>
                    <label class="label">What is your child interested in?</label>
                    <div class="grid md:grid-2 gap-2 mb-6">
                        @foreach(['Reading','Writing','Drawing','Music','Storytelling','Public Speaking','Technology','Science','Leadership Activities','Making Videos','Entrepreneurship','Chess','Learning Languages','Other'] as $opt)
                            <label class="check-pill"><input type="checkbox" name="interests[]" value="{{ $opt }}" style="display:none;"><span class="check-box"></span>{{ $opt }}</label>
                        @endforeach
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="form-step" data-step="4" style="display:none;">
                    <h2 style="font-size:22px;color:var(--navy);margin-bottom:20px;">Goals & preferences</h2>
                    <label class="label">Skills you'd like to develop</label>
                    <div class="grid md:grid-2 gap-2 mb-6">
                        @foreach(['Confidence','Public Speaking','Reading Skills','Writing Skills','Creativity','Leadership','Social Skills','English Communication','Critical Thinking','STEM Skills','Cultural Awareness','Language Learning'] as $opt)
                            <label class="check-pill"><input type="checkbox" name="skills_to_develop[]" value="{{ $opt }}" style="display:none;"><span class="check-box"></span>{{ $opt }}</label>
                        @endforeach
                    </div>
                    <div class="mb-4"><label class="label">Your goals for your child</label><textarea name="parent_goals" class="input" rows="3" style="resize:vertical;"></textarea></div>
                </div>

                {{-- Step 5 --}}
                <div class="form-step" data-step="5" style="display:none;">
                    <h2 style="font-size:22px;color:var(--navy);margin-bottom:20px;">Scheduling</h2>
                    <label class="label">Preferred days</label>
                    <div class="grid md:grid-2 gap-2 mb-6">
                        @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $opt)
                            <label class="check-pill"><input type="checkbox" name="preferred_days[]" value="{{ $opt }}" style="display:none;"><span class="check-box"></span>{{ $opt }}</label>
                        @endforeach
                    </div>
                    <div class="grid md:grid-2 gap-4 mb-4">
                        <div><label class="label">Preferred Time</label><input type="text" name="preferred_time" class="input" placeholder="e.g. After 4pm"></div>
                        <div><label class="label">Timezone</label><input type="text" name="time_zone" class="input" placeholder="e.g. GMT+1"></div>
                    </div>
                    <div class="mb-4"><label class="label">How did you hear about us?</label><select name="hear_about_us" class="input"><option value="">Select...</option>@foreach(['YouTube','TikTok','Facebook','Instagram','Google Search','Friend/Family','School','Workshop/Event','Other'] as $c)<option>{{ $c }}</option>@endforeach</select></div>
                </div>

                {{-- Nav buttons --}}
                <div class="flex justify-between mt-8">
                    <button type="button" id="prevBtn" class="btn-secondary" style="display:none;">← Back</button>
                    <div></div>
                    <button type="button" id="nextBtn" class="btn-primary">Next →</button>
                    <button type="submit" id="submitBtn" class="btn-pink" style="display:none;">Submit ✨</button>
                </div>
            </form>
        </div>

        {{-- Success --}}
        <div id="successCard" class="card text-center" style="padding:48px;display:none;">
            <div style="font-size:64px;margin-bottom:16px;">🎉</div>
            <h2 style="font-size:28px;color:var(--navy);margin-bottom:12px;">Thank You!</h2>
            <p style="color:#555;font-size:16px;line-height:1.7;margin-bottom:24px;" id="successMsg">We've received your request and will be in touch very soon!</p>
            <a href="{{ route('courses') }}" class="btn-purple">Explore Our Programs</a>
        </div>
    </div>
</section>

<style>
.check-pill { display:flex; align-items:center; gap:10px; padding:12px; border:2px solid var(--border); border-radius:12px; cursor:pointer; font-size:14px; font-weight:600; color:var(--navy); transition:all 150ms; }
.check-pill.active { border-color:var(--purple); background:#F0E8FF; }
.check-box { width:18px; height:18px; border-radius:4px; background:var(--border); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.check-pill.active .check-box { background:var(--purple); }
.check-pill.active .check-box::after { content:'✓'; color:white; font-size:11px; font-weight:700; }
</style>

<script>
(function(){
    let step = 1; const total = 5;
    const form = document.getElementById('discoveryForm');
    const show = (n) => {
        document.querySelectorAll('.form-step').forEach(s => s.style.display = s.dataset.step == n ? 'block' : 'none');
        document.querySelectorAll('.step-dot').forEach(d => {
            const active = d.dataset.step <= n;
            d.style.background = active ? 'var(--purple)' : 'var(--border)';
            d.style.color = active ? 'white' : 'var(--muted)';
        });
        document.getElementById('prevBtn').style.display = n > 1 ? 'inline-flex' : 'none';
        document.getElementById('nextBtn').style.display = n < total ? 'inline-flex' : 'none';
        document.getElementById('submitBtn').style.display = n === total ? 'inline-flex' : 'none';
    };
    const validate = (n) => {
        const reqs = document.querySelector(`.form-step[data-step="${n}"]`).querySelectorAll('[required]');
        for (const r of reqs) { if (!r.value) { r.focus(); r.style.borderColor = '#C0392B'; return false; } }
        return true;
    };
    document.getElementById('nextBtn').addEventListener('click', () => { if (validate(step) && step < total) { step++; show(step); } });
    document.getElementById('prevBtn').addEventListener('click', () => { if (step > 1) { step--; show(step); } });
    document.querySelectorAll('.check-pill').forEach(pill => {
        pill.addEventListener('click', (e) => { if (e.target.tagName !== 'INPUT') { const cb = pill.querySelector('input'); cb.checked = !cb.checked; } pill.classList.toggle('active', pill.querySelector('input').checked); });
    });
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (!validate(step)) return;
        const btn = document.getElementById('submitBtn'); btn.textContent = 'Submitting...'; btn.disabled = true;
        const fd = new FormData(form);
        try {
            const res = await fetch('{{ route('discovery.store') }}', { method:'POST', body: fd, headers: {'X-Requested-With':'XMLHttpRequest'} });
            if (res.ok) {
                const name = fd.get('child_name');
                document.getElementById('successMsg').textContent = `We've received your request for ${name} and our team will be in touch very soon to schedule your free discovery call!`;
                document.getElementById('formCard').style.display = 'none';
                document.getElementById('successCard').style.display = 'block';
                window.scrollTo({top:0,behavior:'smooth'});
            } else { alert('Something went wrong. Please try again.'); btn.textContent = 'Submit ✨'; btn.disabled = false; }
        } catch { alert('Network error. Please try again.'); btn.textContent = 'Submit ✨'; btn.disabled = false; }
    });
    show(1);
})();
</script>
@endsection
