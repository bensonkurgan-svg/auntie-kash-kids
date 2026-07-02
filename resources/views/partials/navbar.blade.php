@php
    // Pull all published programs for the Programs dropdown
    $navCourses = \App\Models\Course::where('is_published', true)->orderBy('id')->get(['slug','title','icon']);
@endphp
<nav class="navbar">
    <div class="container flex items-center justify-between" style="height:80px;gap:20px;">
        {{-- Logo --}}
        <a href="{{ route('home') }}" style="flex-shrink:0;display:block;">
            <img src="{{ asset('images/Logo.png') }}" alt="Auntie Kash Kids" class="nav-logo">
        </a>

        {{-- Desktop nav --}}
        <div class="nav-desktop-menu items-center" style="gap:22px;flex:1;justify-content:center;">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'text-pink' : '' }}">Home</a>
            <div class="nav-item">
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'text-pink' : '' }}">About <span class="nav-caret">▼</span></a>
                <div class="nav-dropdown">
                    <a href="{{ route('about') }}"><span>ℹ️</span> About Us</a>
                    <a href="{{ route('meet.auntie.kash') }}"><span>💛</span> Meet Auntie Kash</a>
                    <a href="{{ route('faq') }}"><span>❓</span> FAQ</a>
                    <a href="{{ route('anthem') }}"><span>🎵</span> Our Anthem</a>
                </div>
            </div>

            {{-- Programs dropdown --}}
            <div class="nav-item">
                <a href="{{ route('courses') }}" class="nav-link {{ request()->routeIs('courses') ? 'text-pink' : '' }}">Programs <span class="nav-caret">▼</span></a>
                <div class="nav-dropdown wide">
                    @foreach($navCourses as $c)
                        <a href="{{ route('courses.show', $c->slug) }}"><span style="font-size:18px;">{{ $c->icon }}</span> {{ $c->title }}</a>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('instructors') }}" class="nav-link {{ request()->routeIs('instructors') ? 'text-pink' : '' }}">Instructors</a>

            {{-- Parents dropdown --}}
            <div class="nav-item">
                <a href="{{ route('resources') }}" class="nav-link {{ request()->routeIs('resources') ? 'text-pink' : '' }}">Parents <span class="nav-caret">▼</span></a>
                <div class="nav-dropdown">
                    <a href="{{ route('parent.resources') }}"><span>📚</span> Parent Resource Centre</a>
                    <a href="{{ route('activities') }}"><span>🧩</span> Printable Activities</a>
                    <a href="{{ route('resources') }}"><span>📖</span> Activity Resources</a>
                    <a href="{{ route('reading.library') }}"><span>📖</span> Reading Library</a>
                    <a href="{{ route('discovery') }}"><span>📅</span> Book Free Trial</a>
                    <a href="{{ route('data-export') }}"><span>🔒</span> Privacy & Data</a>
                </div>
            </div>

            <a href="{{ route('events') }}" class="nav-link {{ request()->routeIs('events') ? 'text-pink' : '' }}">Events</a>
            <a href="{{ route('blog') }}" class="nav-link {{ request()->routeIs('blog') ? 'text-pink' : '' }}">Blog</a>
            <a href="{{ route('shop') }}" class="nav-link {{ request()->routeIs('shop') ? 'text-pink' : '' }}">Shop</a>

            {{-- Join Us dropdown --}}
            <div class="nav-item">
                <a href="{{ route('become.instructor') }}" class="nav-link">Join Us <span class="nav-caret">▼</span></a>
                <div class="nav-dropdown">
                    <a href="{{ route('become.instructor') }}"><span>🧑‍🏫</span> Become an Instructor</a>
                    <a href="{{ route('waitlist') }}"><span>✨</span> Waitlist Corner</a>
                    <a href="{{ route('careers') }}"><span>💼</span> Careers</a>
                    <a href="{{ route('press') }}"><span>📰</span> Media & Press</a>
                </div>
            </div>

            <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'text-pink' : '' }}">Contact</a>
        </div>

        {{-- Auth buttons --}}
        <div class="nav-desktop-auth items-center gap-3" style="flex-shrink:0;">
            @auth
                <a href="{{ Auth::user()->dashboardRoute() }}" class="btn-login">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">@csrf
                    <button type="submit" class="nav-link" style="background:none;border:none;cursor:pointer;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-login">👤 Login</a>
                <a href="{{ route('signup') }}" class="btn-signup">✨ Sign Up</a>
            @endauth
        </div>

        {{-- Mobile toggle (animated hamburger) --}}
        <button data-menu-toggle class="hamburger" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>

    {{-- Mobile slide-in app menu --}}
    <div data-mobile-overlay class="mobile-overlay"></div>
    <div data-mobile-menu class="mobile-app-menu">
        <div class="mobile-menu-head">
            <img src="{{ asset('images/Logo.png') }}" alt="Auntie Kash Kids" style="height:40px;">
            <button data-menu-close class="mobile-close" aria-label="Close">×</button>
        </div>
        <div class="mobile-menu-body">
            <a href="{{ route('home') }}" class="m-item">🏠 Home</a>
            <button class="m-item m-accordion" data-accordion>ℹ️ About <span class="m-caret">▾</span></button>
            <div class="m-submenu">
                <a href="{{ route('about') }}" class="m-subitem">ℹ️ About Us</a>
                <a href="{{ route('meet.auntie.kash') }}" class="m-subitem">💛 Meet Auntie Kash</a>
                <a href="{{ route('faq') }}" class="m-subitem">❓ FAQ</a>
                <a href="{{ route('anthem') }}" class="m-subitem">🎵 Our Anthem</a>
            </div>

            {{-- Programs accordion --}}
            <button class="m-item m-accordion" data-accordion>📚 Programs <span class="m-caret">▾</span></button>
            <div class="m-submenu">
                @foreach($navCourses as $c)
                    <a href="{{ route('courses.show', $c->slug) }}" class="m-subitem">{{ $c->icon }} {{ $c->title }}</a>
                @endforeach
                <a href="{{ route('courses') }}" class="m-subitem" style="font-weight:700;color:var(--purple);">View All Programs →</a>
            </div>

            <a href="{{ route('instructors') }}" class="m-item">🧑‍🏫 Instructors</a>

            {{-- Parents accordion --}}
            <button class="m-item m-accordion" data-accordion>👨‍👩‍👧 Parents <span class="m-caret">▾</span></button>
            <div class="m-submenu">
                <a href="{{ route('parent.resources') }}" class="m-subitem">📚 Parent Resource Centre</a>
                <a href="{{ route('activities') }}" class="m-subitem">🧩 Printable Activities</a>
                <a href="{{ route('reading.library') }}" class="m-subitem">📖 Reading Library</a>
                <a href="{{ route('discovery') }}" class="m-subitem">✨ Book Free Trial</a>
                <a href="{{ route('data-export') }}" class="m-subitem">🔒 Privacy & Data</a>
            </div>

            <a href="{{ route('events') }}" class="m-item">🎉 Events</a>
            <a href="{{ route('blog') }}" class="m-item">✍️ Blog</a>
            <a href="{{ route('shop') }}" class="m-item">🛍️ Shop</a>
            <button class="m-item m-accordion" data-accordion>🤝 Join Us <span class="m-caret">▾</span></button>
            <div class="m-submenu">
                <a href="{{ route('become.instructor') }}" class="m-subitem">🧑‍🏫 Become an Instructor</a>
                <a href="{{ route('waitlist') }}" class="m-subitem">✨ Waitlist Corner</a>
                <a href="{{ route('careers') }}" class="m-subitem">💼 Careers</a>
                <a href="{{ route('press') }}" class="m-subitem">📰 Media & Press</a>
            </div>
            <a href="{{ route('contact') }}" class="m-item">✉️ Contact</a>
        </div>
        <div class="mobile-menu-foot">
            @auth
                <a href="{{ Auth::user()->dashboardRoute() }}" class="btn-login w-full" style="justify-content:center;">Dashboard</a>
            @else
                <div class="flex gap-3">
                    <a href="{{ route('login') }}" class="btn-login" style="flex:1;justify-content:center;">Login</a>
                    <a href="{{ route('signup') }}" class="btn-signup" style="flex:1;justify-content:center;">Sign Up</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
