<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;">
    <div style="background:#1D1B4E;padding:24px;text-align:center;border-radius:16px 16px 0 0;">
        <h1 style="color:#FFD93D;margin:0;font-size:22px;">New Discovery Call Request</h1>
    </div>
    <div style="background:#fff;padding:28px;">
        <table style="width:100%;border-collapse:collapse;font-size:14px;">
            <tr style="border-bottom:1px solid #E8E8F0;"><td style="padding:10px 0;color:#7F8C8D;width:40%;">Parent Name</td><td style="padding:10px 0;color:#1D1B4E;font-weight:700;">{{ $form->parent_name }}</td></tr>
            <tr style="border-bottom:1px solid #E8E8F0;"><td style="padding:10px 0;color:#7F8C8D;">Email</td><td style="padding:10px 0;color:#1D1B4E;">{{ $form->parent_email }}</td></tr>
            <tr style="border-bottom:1px solid #E8E8F0;"><td style="padding:10px 0;color:#7F8C8D;">Phone</td><td style="padding:10px 0;color:#1D1B4E;">{{ $form->parent_phone ?: '—' }}</td></tr>
            <tr style="border-bottom:1px solid #E8E8F0;"><td style="padding:10px 0;color:#7F8C8D;">Preferred Contact</td><td style="padding:10px 0;color:#1D1B4E;">{{ $form->preferred_contact }}</td></tr>
            <tr style="border-bottom:1px solid #E8E8F0;"><td style="padding:10px 0;color:#7F8C8D;">Child Name</td><td style="padding:10px 0;color:#7B2FF7;font-weight:700;">{{ $form->child_name }}</td></tr>
            <tr style="border-bottom:1px solid #E8E8F0;"><td style="padding:10px 0;color:#7F8C8D;">Age / Grade</td><td style="padding:10px 0;color:#1D1B4E;">{{ $form->child_age }} / {{ $form->child_grade ?: '—' }}</td></tr>
            <tr style="border-bottom:1px solid #E8E8F0;"><td style="padding:10px 0;color:#7F8C8D;">Interests</td><td style="padding:10px 0;color:#1D1B4E;">{{ implode(', ', $form->interests ?: []) ?: '—' }}</td></tr>
            <tr style="border-bottom:1px solid #E8E8F0;"><td style="padding:10px 0;color:#7F8C8D;">Skills to Develop</td><td style="padding:10px 0;color:#1D1B4E;">{{ implode(', ', $form->skills_to_develop ?: []) ?: '—' }}</td></tr>
            <tr style="border-bottom:1px solid #E8E8F0;"><td style="padding:10px 0;color:#7F8C8D;">Preferred Days</td><td style="padding:10px 0;color:#1D1B4E;">{{ implode(', ', $form->preferred_days ?: []) ?: '—' }}</td></tr>
            <tr><td style="padding:10px 0;color:#7F8C8D;">Heard About Us</td><td style="padding:10px 0;color:#1D1B4E;">{{ $form->hear_about_us ?: '—' }}</td></tr>
        </table>
        @if($form->parent_goals)
        <div style="margin-top:16px;background:#F9F9FB;border-radius:12px;padding:16px;">
            <p style="color:#7F8C8D;font-size:12px;font-weight:700;text-transform:uppercase;margin:0 0 8px;">Parent Goals</p>
            <p style="color:#1D1B4E;font-size:14px;line-height:1.6;margin:0;">{{ $form->parent_goals }}</p>
        </div>
        @endif
        <div style="text-align:center;margin-top:24px;">
            <a href="{{ config('app.url') }}/dashboard/admin#discovery" style="display:inline-block;background:#1D1B4E;color:white;padding:12px 24px;border-radius:35px;text-decoration:none;font-weight:700;font-size:14px;">View in Dashboard</a>
        </div>
    </div>
</div>
