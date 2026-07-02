@extends('layouts.app')
@section('title', 'Terms & Conditions')
@section('content')
<section class="hero-bg" style="padding:50px 0;">
    <div class="container-narrow text-center">
        <h1 style="font-size:clamp(30px,4vw,44px);color:var(--navy);">Terms & Conditions</h1>
        <p style="color:var(--muted);margin-top:8px;">Last updated {{ now()->format('F Y') }}</p>
    </div>
</section>
<section class="section">
    <div class="container-narrow" style="font-size:16px;line-height:1.8;color:#444;">
        <p style="margin-bottom:20px;">Welcome to Auntie Kash Kids Academy. By accessing or using our website and services, you agree to these Terms & Conditions. Please read them carefully.</p>

        <h2 style="font-size:22px;color:var(--navy);margin:26px 0 12px;">1. Our Services</h2>
        <p style="margin-bottom:20px;">Auntie Kash Kids Academy provides live, online educational enrichment programs for children ages 5–16. Programs, schedules, and pricing are described during registration and may be updated from time to time.</p>

        <h2 style="font-size:22px;color:var(--navy);margin:26px 0 12px;">2. Registration & Accounts</h2>
        <p style="margin-bottom:20px;">A parent or legal guardian must register on behalf of any child. You agree to provide accurate information and to keep your account details secure. You are responsible for activity that occurs under your account.</p>

        <h2 style="font-size:22px;color:var(--navy);margin:26px 0 12px;">3. Enrolment & Payment</h2>
        <p style="margin-bottom:20px;">Program fees are presented at enrolment. Payment is processed securely through our payment provider. Fees must be paid in full to confirm a child's place unless otherwise agreed.</p>

        <h2 style="font-size:22px;color:var(--navy);margin:26px 0 12px;">4. Cancellations & Refunds</h2>
        <p style="margin-bottom:20px;">Cancellation and refund terms are provided at the time of enrolment and vary by program. Please contact us with any questions about a specific program before enrolling.</p>

        <h2 style="font-size:22px;color:var(--navy);margin:26px 0 12px;">5. Code of Conduct</h2>
        <p style="margin-bottom:20px;">We are committed to a safe, respectful, and inclusive learning environment. Children and families are expected to treat instructors and fellow learners with kindness and respect. We reserve the right to remove participants whose conduct is harmful or disruptive.</p>

        <h2 style="font-size:22px;color:var(--navy);margin:26px 0 12px;">6. Recordings & Media</h2>
        <p style="margin-bottom:20px;">Some sessions may be recorded for educational or quality-assurance purposes. Parents will be informed when recordings are made. Use of any photos or videos involving children is governed by our consent process.</p>

        <h2 style="font-size:22px;color:var(--navy);margin:26px 0 12px;">7. Intellectual Property</h2>
        <p style="margin-bottom:20px;">All content, materials, branding, and resources provided by Auntie Kash Kids Academy remain our property and may not be copied, redistributed, or resold without permission.</p>

        <h2 style="font-size:22px;color:var(--navy);margin:26px 0 12px;">8. Privacy</h2>
        <p style="margin-bottom:20px;">Your use of our services is also governed by our <a href="{{ route('privacy') }}" style="color:var(--purple);font-weight:600;">Privacy Policy</a>, which explains how we collect and protect your information.</p>

        <h2 style="font-size:22px;color:var(--navy);margin:26px 0 12px;">9. Changes to These Terms</h2>
        <p style="margin-bottom:20px;">We may update these Terms from time to time. Significant changes will be communicated, and continued use of our services means you accept the updated Terms.</p>

        <h2 style="font-size:22px;color:var(--navy);margin:26px 0 12px;">10. Contact Us</h2>
        <p style="margin-bottom:20px;">Questions about these Terms? Reach us at <a href="mailto:hello@auntiekashkids.com" style="color:var(--purple);font-weight:600;">hello@auntiekashkids.com</a>.</p>
    </div>
</section>
@endsection
