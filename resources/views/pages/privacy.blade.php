@extends('layouts.app')
@section('title', 'Privacy Policy')
@section('content')
<section class="hero-bg" style="padding:50px 0;">
    <div class="container-narrow text-center">
        <h1 style="font-size:clamp(30px,4vw,44px);color:var(--navy);">Privacy Policy</h1>
        <p style="color:var(--muted);margin-top:8px;">Last updated {{ now()->format('F Y') }}</p>
    </div>
</section>
<section class="section">
    <div class="container-narrow" style="font-size:16px;line-height:1.8;color:#444;">
        <p style="margin-bottom:20px;">Auntie Kash Kids Academy ("we", "us", "our") is committed to protecting the privacy of every family who uses our platform. This policy explains what information we collect, why we collect it, how we use and protect it, and the rights you have over your data.</p>

        <h2 style="font-size:23px;color:var(--navy);margin:28px 0 12px;">1. Information We Collect</h2>
        <p style="margin-bottom:12px;">We collect only what we need to deliver our educational services:</p>
        <ul style="margin:0 0 20px 22px;line-height:2;">
            <li><strong>Parent/guardian details</strong> — name, email, phone, and country, provided when you register or submit a discovery form.</li>
            <li><strong>Child details</strong> — first name, age, and learning interests, provided by the parent/guardian only.</li>
            <li><strong>Account & usage data</strong> — login records, enrolments, and lesson progress.</li>
            <li><strong>Payment data</strong> — processed securely by our payment provider (Stripe). We never store full card numbers.</li>
        </ul>

        <h2 style="font-size:23px;color:var(--navy);margin:28px 0 12px;">2. How We Use Your Information</h2>
        <ul style="margin:0 0 20px 22px;line-height:2;">
            <li>To create and manage your account and your child's enrolments.</li>
            <li>To match your child to suitable programs and tutors.</li>
            <li>To process payments and send service-related communications.</li>
            <li>To improve our programs and the safety of our platform.</li>
        </ul>

        <h2 style="font-size:23px;color:var(--navy);margin:28px 0 12px;">3. Children's Privacy</h2>
        <p style="margin-bottom:20px;">We take children's privacy seriously. Children's information is only ever provided by a parent or guardian, is used solely to deliver our educational programs, and is never sold or shared for advertising. We comply with applicable children's data-protection laws including COPPA and GDPR-K principles.</p>

        <h2 style="font-size:23px;color:var(--navy);margin:28px 0 12px;">4. How We Protect Your Data</h2>
        <p style="margin-bottom:20px;">We use industry-standard security measures including encrypted connections (HTTPS), hashed passwords, access controls, and secure hosting. Staff accounts use forced password changes and role-based permissions to limit access to personal data.</p>

        <h2 style="font-size:23px;color:var(--navy);margin:28px 0 12px;">5. Your Rights</h2>
        <p style="margin-bottom:12px;">You have the right to access, export, correct, or delete your personal data at any time:</p>
        <ul style="margin:0 0 20px 22px;line-height:2;">
            <li><a href="{{ route('data-export') }}" style="color:var(--purple);font-weight:600;">Export your data</a> — download everything we hold in JSON, text, or PDF format.</li>
            <li><a href="{{ route('data-delete') }}" style="color:var(--purple);font-weight:600;">Delete your account</a> — permanently remove your data from our systems.</li>
        </ul>

        <h2 style="font-size:23px;color:var(--navy);margin:28px 0 12px;">6. Data Sharing</h2>
        <p style="margin-bottom:20px;">We do not sell your data. We share information only with trusted service providers who help us operate (such as our payment processor and email provider), and only to the extent needed to provide the service. We may disclose information if required by law.</p>

        <h2 style="font-size:23px;color:var(--navy);margin:28px 0 12px;">7. Contact Us</h2>
        <p style="margin-bottom:8px;">For any privacy questions or to exercise your rights, contact us at:</p>
        <p style="margin-bottom:20px;"><a href="mailto:hello@auntiekashkids.com" style="color:var(--purple);font-weight:600;">hello@auntiekashkids.com</a></p>
    </div>
</section>
@endsection
