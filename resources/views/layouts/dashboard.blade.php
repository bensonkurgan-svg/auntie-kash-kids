<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Auntie Kash Kids</title>
    <link rel="icon" href="{{ asset('images/Logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .dash-layout { display: flex; min-height: 100vh; background: var(--surface); }
        .sidebar { width: 260px; background: white; border-right: 1px solid var(--border); padding: 24px 16px; flex-shrink: 0; }
        .sidebar-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; font-family: var(--font-nunito); font-weight: 600; color: var(--text); margin-bottom: 4px; transition: all 150ms; }
        .sidebar-link:hover { background: var(--surface); }
        .sidebar-link.active { background: #F0E8FF; color: var(--purple); border-left: 3px solid var(--purple); }
        .dash-main { flex: 1; padding: 32px; overflow: auto; }
        .stat-card { background: white; border: 1px solid var(--border); border-radius: 16px; padding: 20px; }
        .stat-icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; font-size: 20px; }
        @media (max-width: 768px) { .sidebar { display: none; } .dash-main { padding: 20px; } }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 12px 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); font-weight: 700; background: var(--surface); }
        td { padding: 16px 20px; border-bottom: 1px solid var(--border); font-size: 14px; }
    </style>
</head>
<body>
    <div class="dash-layout">
        @include('partials.sidebar')
        <main class="dash-main">
            @if(session('success'))
                <div style="background:#F0FFF4;border:1px solid #7ED321;color:#2d6a0f;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-weight:600;">
                    {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
