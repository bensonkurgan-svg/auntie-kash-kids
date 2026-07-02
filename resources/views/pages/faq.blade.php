@extends('layouts.app')
@section('title', 'Frequently Asked Questions')
@section('content')
<section class="hero-bg" style="padding:56px 0;">
    <div class="container text-center">
        <span class="welcome-pill">HELP CENTRE</span>
        <h1 style="font-size:clamp(30px,4vw,48px);color:var(--navy);margin:12px 0;">Frequently Asked Questions</h1>
        <p style="color:#555;font-size:18px;max-width:600px;margin:0 auto;">Everything you need to know about Auntie Kash Kids Academy.</p>
    </div>
</section>

<section class="section">
    <div class="container-narrow">
        @php
        $faqs = [
            ['What is Auntie Kash Kids?', 'Auntie Kash Kids is a children\'s educational media and enrichment brand dedicated to helping young minds learn, create, sing, speak, and shine through books, music, online learning, educational content, and creative experiences.'],
            ['What is Auntie Kash Kids Academy?', 'Auntie Kash Kids Academy is our online learning platform where children participate in live, interactive classes designed to build confidence, creativity, communication skills, leadership abilities, and a lifelong love of learning.'],
            ['What age groups do you serve?', 'Our programs are designed for children ages 5–16. Specific age recommendations are provided for each program.'],
            ['Do children need to be in Canada to join?', 'No. Our classes are open to children from around the world. We welcome families from different countries, cultures, and backgrounds.'],
            ['How do online classes work?', 'Classes are delivered live online by expert instructors. Children participate in interactive lessons, activities, discussions, presentations, projects, and collaborative learning experiences.'],
            ['What programs do you offer?', 'We offer a variety of enrichment programs including: 📚 Young Readers Club, ✍🏾 Young Authors Club, 🎤 Public Speaking Club, 📖 Storytelling Club, 💪 Confidence Builders Club, 🌟 Future Leaders Club, 🎨 Art & Creativity Club, 🎵 Music & Rhythm Club, 🌍 Cultural Explorers Club, 🇬🇧 English Communication & Confidence Club, 🇫🇷 French Language Club, 🇨🇳 Mandarin Explorers Club, 🔬 STEM Explorers Club, 💰 Junior Entrepreneurs Club, 🎬 Young Content Creators Club, 🎭 Drama & Performance Club, and more.'],
            ['How long is each class?', 'Most classes are approximately 60 minutes long.'],
            ['How many children are in a class?', 'We aim to keep classes small and interactive to ensure every child has opportunities to participate, ask questions, and receive personalized attention.'],
            ['How long are the programs?', 'Most programs run for 12 weeks and are divided into four learning modules designed to help children progressively develop new skills and confidence.'],
            ['Will my child receive a certificate?', 'Yes. Upon successful completion of a program, children receive an Auntie Kash Kids Academy Certificate of Completion. We highly encourage families to print, frame, and display certificates to celebrate their child\'s achievement and growth.'],
            ['What if I am not sure which program is right for my child?', 'We recommend completing our Program Matching Form. Our team will review your child\'s interests, strengths, goals, and age to recommend the most suitable programs.'],
            ['Do children need previous experience?', 'No. Our programs are designed to support beginners as well as children who already have experience and want to further develop their skills.'],
            ['What technology does my child need?', 'Children generally need: a computer, laptop, or tablet; reliable internet access; a camera and microphone; and a quiet learning space. Additional requirements may vary depending on the program.'],
            ['Are classes recorded?', 'Some classes or workshops may be recorded for educational and quality assurance purposes. Parents will be informed when recordings are being made.'],
            ['Will I receive updates about my child\'s progress?', 'Yes. Instructors may provide progress updates, feedback, and recommendations to help parents support their child\'s learning journey.'],
            ['How do I register?', 'Simply complete our Program Matching Form or Student Registration Form and our team will guide you through the enrollment process.'],
            ['How much do programs cost?', 'Program fees vary depending on the program and format. Current pricing information is available during registration and enrollment.'],
            ['Can my child enroll in more than one program?', 'Absolutely. Many children participate in multiple programs based on their interests and goals.'],
            ['How do I become an instructor?', 'Qualified educators, coaches, artists, child development professionals, language instructors, storytellers, and subject matter experts are welcome to apply through our Instructor Application Form.'],
            ['How can I stay informed about new programs and events?', 'Join our Founding Families Waitlist and follow Auntie Kash Kids on social media for updates, announcements, events, and new learning opportunities.'],
        ];
        @endphp

        <div class="faq-list">
            @foreach($faqs as $i => $faq)
                <div class="faq-item">
                    <button class="faq-q" onclick="toggleFaq({{ $i }})">
                        <span>{{ $faq[0] }}</span>
                        <span class="faq-icon" id="faq-icon-{{ $i }}">+</span>
                    </button>
                    <div class="faq-a" id="faq-a-{{ $i }}"><p>{{ $faq[1] }}</p></div>
                </div>
            @endforeach
        </div>

        <div class="text-center" style="margin-top:40px;padding:32px;background:var(--surface);border-radius:20px;">
            <h3 style="font-size:22px;color:var(--navy);margin-bottom:10px;">Still have questions?</h3>
            <p style="color:#666;margin-bottom:20px;">We're happy to help — reach out any time.</p>
            <div class="flex gap-3" style="justify-content:center;flex-wrap:wrap;">
                <a href="{{ route('discovery') }}" class="btn-pink">✨ Book a Free Trial</a>
                <a href="{{ route('contact') }}" class="btn-purple">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<style>
.faq-list { display:flex; flex-direction:column; gap:12px; }
.faq-item { border:1px solid var(--border); border-radius:14px; overflow:hidden; background:#fff; }
.faq-q { width:100%; display:flex; align-items:center; justify-content:space-between; gap:16px; padding:20px 24px; background:none; border:none; cursor:pointer; font-family:var(--font-nunito); font-weight:700; font-size:16px; color:var(--navy); text-align:left; transition:background 140ms; }
.faq-q:hover { background:var(--surface); }
.faq-icon { font-size:24px; color:var(--purple); flex-shrink:0; transition:transform 280ms; line-height:1; }
.faq-icon.open { transform:rotate(45deg); }
.faq-a { max-height:0; overflow:hidden; transition:max-height 320ms ease; }
.faq-a.open { max-height:500px; }
.faq-a p { padding:0 24px 20px; color:#555; line-height:1.8; font-size:15px; margin:0; }
</style>
<script>
function toggleFaq(i){
    var a = document.getElementById('faq-a-'+i);
    var icon = document.getElementById('faq-icon-'+i);
    a.classList.toggle('open');
    icon.classList.toggle('open');
}
</script>
@endsection
