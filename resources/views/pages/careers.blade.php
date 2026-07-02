@extends('layouts.app')
@section('title', 'Careers')
@section('content')
<section class="hero-bg" style="padding:56px 0;">
    <div class="container text-center">
        <span class="welcome-pill">CAREERS</span>
        <h1 style="font-size:clamp(30px,4vw,48px);color:var(--navy);margin:12px 0;">Grow With Auntie Kash Kids</h1>
        <p style="color:#555;font-size:18px;max-width:620px;margin:0 auto;">We're building something special for children everywhere. Come help us bring learning to life.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 class="text-center" style="font-size:28px;color:var(--navy);margin-bottom:8px;">Opportunities We Hire For</h2>
        <p class="text-center text-muted" style="margin-bottom:36px;">We're always interested in meeting talented, passionate people.</p>
        <div class="grid md:grid-3 gap-6">
            @php $roles = [
                ['🧑‍🏫','Instructors','Teach literacy, public speaking, arts, leadership, and languages to children worldwide.'],
                ['🗂️','Administrative Support','Keep our operations running smoothly and support our families and instructors.'],
                ['📣','Marketing','Spread the word and grow our community of young learners and families.'],
                ['📚','Curriculum Development','Design engaging, age-appropriate programs that spark joy and learning.'],
                ['🎨','Content Creation','Create stories, videos, and learning materials that delight children.'],
                ['🌟','General Interest','Don\'t see your role? Tell us how you\'d love to contribute.'],
            ]; @endphp
            @foreach($roles as $r)
                <div class="card" data-reveal>
                    <div style="font-size:40px;margin-bottom:12px;">{{ $r[0] }}</div>
                    <h3 style="font-size:19px;color:var(--navy);margin-bottom:8px;">{{ $r[1] }}</h3>
                    <p style="color:#666;font-size:14px;line-height:1.6;">{{ $r[2] }}</p>
                </div>
            @endforeach
        </div>
        <div class="text-center" style="margin-top:40px;padding:32px;background:var(--surface);border-radius:20px;">
            <h3 style="font-size:22px;color:var(--navy);margin-bottom:10px;">Interested in joining us?</h3>
            <p style="color:#666;margin-bottom:20px;">Instructors can apply directly. For other roles, get in touch and tell us about yourself.</p>
            <div class="flex gap-3" style="justify-content:center;flex-wrap:wrap;">
                <a href="{{ route('become.instructor') }}" class="btn-pink">Apply as an Instructor</a>
                <a href="{{ route('contact') }}" class="btn-purple">Contact Us</a>
            </div>
        </div>
    </div>
</section>
@endsection
