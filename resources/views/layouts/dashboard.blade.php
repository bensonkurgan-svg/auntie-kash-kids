<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Auntie Kash Kids</title>
    <link rel="icon" href="{{ asset('images/round-logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .dash-layout { display: flex; min-height: 100vh; background: var(--surface); }
        .dash-main { flex: 1; padding: 32px; overflow: auto; min-width: 0; }

        /* Mobile top bar — hidden on desktop */
        .mobile-topbar { display: none; }
        .drawer-overlay { display: none; }

        @media (max-width: 900px) {
            .dash-main { padding: 20px 16px; }
            /* Mobile top bar */
            .mobile-topbar {
                display: flex; align-items: center; justify-content: space-between;
                background: #fff; border-bottom: 1px solid var(--border);
                padding: 12px 16px; position: sticky; top: 0; z-index: 40;
                box-shadow: 0 2px 10px rgba(80,60,160,0.05);
            }
            .mobile-topbar .ham {
                background: none; border: none; font-size: 26px; cursor: pointer;
                color: var(--purple); line-height: 1; padding: 4px 8px;
            }
            .mobile-topbar img { height: 38px; }
            /* Sidebar becomes a slide-out drawer */
            .sidebar {
                position: fixed; top: 0; left: 0; height: 100vh; z-index: 60;
                transform: translateX(-100%); transition: transform 240ms ease;
                box-shadow: 8px 0 30px rgba(0,0,0,0.15);
            }
            .sidebar.open { transform: translateX(0); }
            .drawer-overlay {
                position: fixed; inset: 0; background: rgba(0,0,0,0.4);
                z-index: 55; opacity: 0; pointer-events: none; transition: opacity 240ms;
            }
            .drawer-overlay.open { display: block; opacity: 1; pointer-events: auto; }
        }
    </style>
</head>
<body>
    {{-- Mobile top bar with hamburger --}}
    <div class="mobile-topbar">
        <button class="ham" data-drawer-toggle aria-label="Open menu">☰</button>
        <a href="{{ route('home') }}"><img src="{{ asset('images/Logo.png') }}" alt="Auntie Kash Kids"></a>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button type="submit" style="background:none;border:none;color:var(--pink);font-weight:700;font-size:13px;cursor:pointer;">Logout</button>
        </form>
    </div>

    <div class="drawer-overlay" data-drawer-overlay></div>

    <div class="dash-layout">
        @include('partials.sidebar')
        <main class="dash-main">
            @if(session('success'))
                <div style="background:#F0FFF4;border:1px solid #7ED321;color:#2d6a0f;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-weight:600;">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div style="background:#FFF0F0;border:1px solid #FFB0B0;color:#C0392B;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-weight:600;">
                    {{ $errors->first() }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        (function(){
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('[data-drawer-overlay]');
            const toggle = document.querySelector('[data-drawer-toggle]');
            const close = () => { sidebar?.classList.remove('open'); overlay?.classList.remove('open'); };
            toggle?.addEventListener('click', () => { sidebar?.classList.toggle('open'); overlay?.classList.toggle('open'); });
            overlay?.addEventListener('click', close);
            document.querySelectorAll('.sidebar-link').forEach(l => l.addEventListener('click', close));
        })();
    </script>
    @stack('scripts')
</body>
</html>
