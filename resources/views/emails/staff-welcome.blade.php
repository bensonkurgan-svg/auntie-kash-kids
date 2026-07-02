<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;">
    <div style="background:linear-gradient(135deg,#7B2FF7,#FF3E9E);padding:28px;text-align:center;border-radius:16px 16px 0 0;">
        <h1 style="color:white;margin:0;font-size:24px;">Welcome to the Team! 🌟</h1>
    </div>
    <div style="background:#fff;padding:32px;">
        <p style="color:#444;font-size:16px;line-height:1.7;">Hi {{ explode(' ', $user->name)[0] }},</p>
        <p style="color:#444;font-size:16px;line-height:1.7;">An account has been created for you on the Auntie Kash Kids Academy platform as a <strong>{{ ucfirst(strtolower($user->role)) }}</strong>. Here are your login details:</p>
        <div style="background:#F9F0FF;border-radius:12px;padding:20px;margin:20px 0;border-left:4px solid #7B2FF7;">
            <p style="margin:0 0 8px;font-size:14px;"><strong>Login email:</strong> {{ $user->email }}</p>
            <p style="margin:0;font-size:14px;"><strong>Temporary password:</strong> <code style="background:#fff;padding:3px 8px;border-radius:5px;font-size:15px;">{{ $plainPassword }}</code></p>
        </div>
        <p style="color:#C0392B;font-size:14px;line-height:1.7;"><strong>Important:</strong> For your security, you'll be asked to set a new password the first time you log in.</p>
        <div style="text-align:center;margin-top:24px;">
            <a href="{{ config('app.url') }}/login" style="display:inline-block;background:linear-gradient(135deg,#7B2FF7,#FF3E9E);color:white;padding:14px 32px;border-radius:35px;text-decoration:none;font-weight:700;">Log In Now</a>
        </div>
    </div>
    <div style="background:#1D1B4E;padding:18px;text-align:center;border-radius:0 0 16px 16px;">
        <p style="color:rgba(255,255,255,0.5);margin:0;font-size:12px;">© 2026 Auntie Kash Kids Academy</p>
    </div>
</div>
