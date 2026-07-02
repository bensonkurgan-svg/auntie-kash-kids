@extends('layouts.app')
@section('title', $course->title)
@section('content')
<section class="hero-bg" style="padding:50px 0;">
    <div class="container">
        <a href="{{ route('courses') }}" style="color:var(--purple);font-weight:600;font-size:14px;">← Back to Programs</a>
        <div class="flex items-center gap-4 mt-4">
            <div style="font-size:56px;">{{ $course->icon }}</div>
            <div>
                <h1 style="font-size:clamp(28px,4vw,42px);color:var(--navy);">{{ $course->title }}</h1>
                <p style="color:var(--muted);">with {{ $course->tutorProfile->user->name ?? 'Auntie Kash Tutor' }}</p>
            </div>
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="grid lg:grid-3 gap-6">
            <div style="grid-column:span 2;">
                <h2 style="font-size:24px;color:var(--navy);margin-bottom:16px;">About This Program</h2>
                <p style="color:#444;font-size:16px;line-height:1.8;margin-bottom:32px;">{{ $course->description }}</p>

                <h2 style="font-size:24px;color:var(--navy);margin-bottom:16px;">Curriculum</h2>
                @foreach($course->modules as $module)
                    <div class="card mb-4">
                        <h3 style="font-size:17px;color:var(--navy);margin-bottom:12px;">{{ $module->title }}</h3>
                        <ul style="list-style:none;display:flex;flex-direction:column;gap:8px;">
                            @foreach($module->lessons as $lesson)
                                <li class="flex items-center gap-3" style="font-size:14px;color:#555;">
                                    <span style="color:var(--purple);">▸</span> {{ $lesson->title }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach

                <div class="card" style="margin-top:24px;background:var(--surface);">
                    <h3 style="font-size:17px;color:var(--navy);margin-bottom:8px;">Your Tutor</h3>
                    <div class="flex items-center gap-3 mb-4">
                        @if($course->tutorProfile->photo_url)<img src="{{ $course->tutorProfile->photo_url }}" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">@else<span style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#7B2FF7,#FF3E9E);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;">{{ Str::substr($course->tutorProfile->user->name ?? 'AK', 0, 2) }}</span>@endif
                        <div>
                            <div style="font-weight:700;color:var(--navy);">{{ $course->tutorProfile->user->name ?? 'Tutor' }}</div>
                            <div style="font-size:13px;color:var(--yellow);">★ {{ $course->tutorProfile->rating ?? '5.0' }} ({{ $course->tutorProfile->review_count ?? 0 }} reviews)</div>
                        </div>
                    </div>
                    <p style="font-size:14px;color:#666;line-height:1.6;">{{ $course->tutorProfile->bio ?? '' }}</p>
                </div>
            </div>

            <div>
                <div class="card" style="position:sticky;top:90px;">
                    <div style="font-size:36px;font-weight:800;color:var(--navy);font-family:var(--font-fredoka);">${{ number_format($course->price, 2) }}</div>
                    <p style="color:var(--muted);font-size:14px;margin-bottom:20px;">per month</p>
                    @auth
                        @if(Auth::user()->isParent())
                            <button class="btn-pink w-full mb-2" onclick="document.getElementById('enrollModal').style.display='flex'">Enroll Now →</button>
                        @else
                            <a href="{{ route('discovery') }}" class="btn-pink w-full mb-2">✨ Book Free Trial</a>
                        @endif
                    @else
                        <a href="{{ route('signup') }}" class="btn-pink w-full mb-2">Sign Up to Enroll</a>
                    @endauth
                    <a href="{{ route('discovery') }}" class="btn-secondary w-full">✨ Free Trial Class</a>
                    <ul style="list-style:none;margin-top:24px;display:flex;flex-direction:column;gap:12px;font-size:14px;color:#555;">
                        <li>📚 {{ $course->modules->count() }} modules</li>
                        <li>🎓 {{ $course->lesson_count }} lessons</li>
                        <li>👥 {{ $enrollmentCount }} students enrolled</li>
                        <li>🌍 Live online classes</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Self-enroll modal (parents only) --}}
@auth
@if(Auth::user()->isParent())
@php $myStudents = \App\Models\Student::where('parent_id', Auth::id())->get(); @endphp
<div id="enrollModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:100;align-items:center;justify-content:center;padding:20px;">
    <div class="card" style="max-width:420px;width:100%;">
        <h3 style="color:var(--navy);margin-bottom:6px;">Enroll in {{ $course->title }}</h3>
        <p style="color:var(--muted);font-size:14px;margin-bottom:18px;">${{ number_format($course->price,2) }}/month · Choose which child to enroll.</p>
        <div id="enrollMsg" style="display:none;background:#FFF0F0;color:#c0392b;padding:10px;border-radius:8px;font-size:13px;margin-bottom:12px;"></div>

        @if($myStudents->count())
            <label class="label">Select child</label>
            <select id="studentSelect" class="input mb-4">
                @foreach($myStudents as $s)<option value="{{ $s->id }}">{{ $s->name }}{{ $s->age ? " (age {$s->age})" : '' }}</option>@endforeach
            </select>
        @else
            <label class="label">Child's name</label>
            <input type="text" id="newChildName" class="input mb-2" placeholder="e.g. Amara">
            <input type="number" id="newChildAge" class="input mb-4" placeholder="Age" min="3" max="18">
        @endif

        <button onclick="proceedCheckout()" class="btn-pink w-full mb-2" id="checkoutBtn">Proceed to Checkout →</button>
        <button onclick="document.getElementById('enrollModal').style.display='none'" class="btn-secondary w-full">Cancel</button>
    </div>
</div>
<script>
async function proceedCheckout(){
    const btn = document.getElementById('checkoutBtn');
    const msg = document.getElementById('enrollMsg');
    btn.textContent = 'Processing…'; btn.disabled = true;
    let studentId = document.getElementById('studentSelect')?.value;

    // If no existing children, create one first
    if (!studentId) {
        const name = document.getElementById('newChildName')?.value;
        const age = document.getElementById('newChildAge')?.value;
        if (!name) { showErr('Please enter your child\'s name.'); return; }
        const res = await fetch('{{ route('students.store') }}', {
            method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({name, age})
        });
        if (!res.ok) { showErr('Could not add child. Try again.'); return; }
        studentId = (await res.json()).id;
    }

    // Create Stripe checkout session
    const cres = await fetch('{{ route('stripe.checkout') }}', {
        method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({course_id: {{ $course->id }}, student_id: studentId})
    });
    if (!cres.ok) { showErr('Checkout unavailable. Please try again later.'); return; }
    const data = await cres.json();
    if (data.url) { window.location = data.url; }
    else { showErr('Could not start checkout.'); }

    function showErr(m){ msg.textContent = m; msg.style.display='block'; btn.textContent='Proceed to Checkout →'; btn.disabled=false; }
}
function showErr(m){ const msg=document.getElementById('enrollMsg'); const btn=document.getElementById('checkoutBtn'); msg.textContent=m; msg.style.display='block'; btn.textContent='Proceed to Checkout →'; btn.disabled=false; }
</script>
@endif
@endauth
@endsection
