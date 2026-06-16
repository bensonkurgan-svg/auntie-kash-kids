<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;">
    <div style="background:linear-gradient(135deg,#7B2FF7,#FF3E9E);padding:32px 24px;text-align:center;border-radius:16px 16px 0 0;">
        <h1 style="color:white;margin:0;font-size:28px;">Auntie Kash Kids Academy</h1>
        <p style="color:rgba(255,255,255,0.85);margin:8px 0 0;font-size:15px;">Where Young Minds Learn, Create, Sing, Speak &amp; Shine&trade;</p>
    </div>
    <div style="background:#fff;padding:36px 32px;">
        <h2 style="color:#1D1B4E;margin:0 0 16px;">Thank you, {{ explode(' ', $form->parent_name)[0] }}! 🎉</h2>
        <p style="color:#444;font-size:16px;line-height:1.7;">We have received your discovery call request for <strong>{{ $form->child_name }}</strong> and we are excited to connect with you!</p>
        <p style="color:#444;font-size:16px;line-height:1.7;">A member of our team will be in touch very soon to schedule your <strong>free discovery call</strong> and recommend the best learning path for {{ $form->child_name }}.</p>
        <div style="background:linear-gradient(135deg,#F9F0FF,#FFF0FB);border-radius:16px;padding:20px;margin:24px 0;border-left:4px solid #7B2FF7;">
            <p style="color:#7B2FF7;font-weight:700;margin:0 0 8px;font-size:15px;">What happens next?</p>
            <ul style="color:#444;margin:0;padding-left:20px;font-size:14px;line-height:2;">
                <li>Our team reviews your submission</li>
                <li>We match {{ $form->child_name }} to the best program(s)</li>
                <li>We contact you to schedule your free call</li>
                <li>{{ $form->child_name }}'s learning journey begins! 🌟</li>
            </ul>
        </div>
        <div style="text-align:center;margin-top:28px;">
            <a href="{{ config('app.url') }}/courses" style="display:inline-block;background:linear-gradient(135deg,#7B2FF7,#FF3E9E);color:white;padding:14px 32px;border-radius:35px;text-decoration:none;font-weight:700;font-size:15px;">Explore Our Programs</a>
        </div>
    </div>
    <div style="background:#1D1B4E;padding:20px;text-align:center;border-radius:0 0 16px 16px;">
        <p style="color:rgba(255,255,255,0.5);margin:0;font-size:12px;">© 2026 Auntie Kash Kids Academy · hello@auntiekashkids.com</p>
    </div>
</div>
