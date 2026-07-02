@php
    $role = Auth::user()->role;
    // Role-scoped menus — internal routes only, never the public site.
    $links = match($role) {
        'CEO' => [
            ['route' => 'dashboard.ceo',  'icon' => '📊', 'label' => 'Overview'],
            ['route' => 'directory.students', 'icon' => '🎓', 'label' => 'Students'],
            ['route' => 'directory.instructors', 'icon' => '🧑‍🏫', 'label' => 'Instructors'],
            ['route' => 'programs.index', 'icon' => '📚', 'label' => 'Programs'],
            ['route' => 'calendar',       'icon' => '🗓️', 'label' => 'Master Calendar'],
            ['route' => 'library.manage', 'icon' => '📂', 'label' => 'Resource Library'],
            ['route' => 'activities.manage', 'icon' => '🧩', 'label' => 'Activities'],
            ['route' => 'books.manage',    'icon' => '📚', 'label' => 'Reading Library'],
            ['route' => 'staff.index',    'icon' => '👥','label' => 'Staff & Tutors'],
            ['route' => 'recruitment',    'icon' => '📨', 'label' => 'Recruitment'],
            ['route' => 'shop.manage',    'icon' => '🛍️', 'label' => 'Shop'],
            ['route' => 'blog.manage',    'icon' => '✍️', 'label' => 'Blog Posts'],
            ['route' => 'messages',       'icon' => '💬', 'label' => 'Messages'],
            ['route' => 'cms.editor',     'icon' => '🎨', 'label' => 'Website Content'],
            ['route' => 'profile.edit',   'icon' => '👤', 'label' => 'My Profile'],
        ],
        'ADMIN' => [
            ['route' => 'dashboard.admin','icon' => '📊', 'label' => 'Overview'],
            ['route' => 'directory.students', 'icon' => '🎓', 'label' => 'Students'],
            ['route' => 'directory.instructors', 'icon' => '🧑‍🏫', 'label' => 'Instructors'],
            ['route' => 'calendar',       'icon' => '🗓️', 'label' => 'Master Calendar'],
            ['route' => 'library.manage', 'icon' => '📂', 'label' => 'Resource Library'],
            ['route' => 'activities.manage', 'icon' => '🧩', 'label' => 'Activities'],
            ['route' => 'books.manage',    'icon' => '📚', 'label' => 'Reading Library'],
            ['route' => 'staff.index',    'icon' => '👥','label' => 'Tutors'],
            ['route' => 'recruitment',    'icon' => '📨', 'label' => 'Recruitment'],
            ['route' => 'shop.manage',    'icon' => '🛍️', 'label' => 'Shop'],
            ['route' => 'blog.manage',    'icon' => '✍️', 'label' => 'Blog Posts'],
            ['route' => 'messages',       'icon' => '💬', 'label' => 'Messages'],
            ['route' => 'cms.editor',     'icon' => '🎨', 'label' => 'Website Content'],
            ['route' => 'profile.edit',   'icon' => '👤', 'label' => 'My Profile'],
        ],
        'TUTOR' => [
            ['route' => 'dashboard.tutor','icon' => '📊', 'label' => 'Overview'],
            ['route' => 'classroom',      'icon' => '🎓', 'label' => 'Classroom'],
            ['route' => 'instructor.portal','icon' => '📂', 'label' => 'Instructor Portal'],
            ['route' => 'messages',       'icon' => '💬', 'label' => 'Messages'],
            ['route' => 'profile.edit',   'icon' => '👤', 'label' => 'My Profile'],
        ],
        'PARENT' => [
            ['route' => 'dashboard.parent','icon' => '📊', 'label' => 'Overview'],
            ['route' => 'courses',         'icon' => '📚', 'label' => 'Browse Programs'],
            ['route' => 'shop',            'icon' => '🛍️', 'label' => 'Shop'],
            ['route' => 'messages',        'icon' => '💬', 'label' => 'Messages'],
            ['route' => 'data-export',     'icon' => '🔒', 'label' => 'My Data'],
        ],
        default => [
            ['route' => 'dashboard.student','icon' => '📊', 'label' => 'Overview'],
            ['route' => 'messages',         'icon' => '💬', 'label' => 'Messages'],
        ],
    };
@endphp
<aside class="sidebar">
    <a href="{{ route('home') }}" style="display:block;margin-bottom:24px;">
        <img src="{{ asset('images/Logo.png') }}" alt="Auntie Kash Kids" style="height:46px;">
    </a>
    <div style="margin-bottom:20px;padding:14px;background:var(--surface);border-radius:14px;display:flex;align-items:center;gap:10px;">
        @if(Auth::user()->photo())
            <img src="{{ Auth::user()->photo() }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;flex-shrink:0;">
        @else
            <span style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#7B2FF7,#FF3E9E);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;flex-shrink:0;">{{ strtoupper(substr(Auth::user()->name,0,2)) }}</span>
        @endif
        <span style="min-width:0;">
            <span style="font-weight:700;font-size:14px;color:var(--navy);display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Auth::user()->name }}</span>
            <span style="font-size:12px;color:var(--muted);">{{ ucfirst(strtolower($role)) }}</span>
        </span>
    </div>
    <nav>
        @foreach($links as $link)
            <a href="{{ route($link['route']) }}" class="sidebar-link {{ request()->routeIs($link['route']) ? 'active' : '' }}">
                <span>{{ $link['icon'] }}</span> {{ $link['label'] }}
            </a>
        @endforeach
    </nav>
    <form method="POST" action="{{ route('logout') }}" style="margin-top:20px;">@csrf
        <button type="submit" class="sidebar-link" style="width:100%;background:none;border:none;cursor:pointer;color:var(--pink);">
            <span>🚪</span> Logout
        </button>
    </form>
</aside>

<style>
.sidebar { width: 264px; background: white; border-right: 1px solid var(--border); padding: 24px 16px; flex-shrink: 0; }
.sidebar-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; font-family: var(--font-nunito); font-weight: 600; color: var(--text); margin-bottom: 4px; transition: all 150ms; text-decoration:none; }
.sidebar-link:hover { background: var(--surface); }
.sidebar-link.active { background: #F0E8FF; color: var(--purple); }
</style>
