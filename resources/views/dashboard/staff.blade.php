@extends('layouts.dashboard')
@section('title', 'Staff & Tutors')
@section('content')
@php $isCeo = Auth::user()->isCEO(); @endphp

<div class="dash-header">
    <div>
        <h1 class="dash-title">Staff & Tutors</h1>
        <p class="dash-sub">{{ $isCeo ? 'Manage admins and tutors.' : 'Register and manage tutors.' }}</p>
    </div>
    <div class="dash-date">📅 {{ now()->format('l, j F Y') }}</div>
</div>

{{-- Show generated credentials once after creation --}}
@if(session('newCredentials'))
    @php $c = session('newCredentials'); @endphp
    <div class="panel" style="border:2px solid #7ED321;margin-bottom:24px;">
        <div class="panel-head" style="background:#F2FFEA;"><div class="panel-title">✅ {{ $c['role'] }} account created</div></div>
        <div class="panel-body padded">
            <p style="margin-bottom:12px;color:#444;">Share these login details with <strong>{{ $c['name'] }}</strong>. They'll be asked to change the password on first login. (Also emailed to them.)</p>
            <div style="background:#F9F0FF;border-radius:12px;padding:16px;font-size:15px;">
                <div><strong>Login email:</strong> <code>{{ $c['email'] }}</code></div>
                <div style="margin-top:8px;"><strong>Temporary password:</strong> <code style="background:#fff;padding:3px 10px;border-radius:6px;font-size:16px;color:#7B2FF7;">{{ $c['password'] }}</code></div>
            </div>
        </div>
    </div>
@endif

<div class="dash-tabs">
    @if($isCeo)<button class="dash-tab active" data-tab="admins">🛡️ Admins</button>@endif
    <button class="dash-tab {{ $isCeo ? '' : 'active' }}" data-tab="tutors">🧑‍🏫 Tutors</button>
    <button class="dash-tab" data-tab="add">➕ Add New</button>
</div>

{{-- Admins tab (CEO only) --}}
@if($isCeo)
<div class="tab-panel active" data-panel="admins">
    <div class="panel">
        <div class="panel-head"><div class="panel-title">Admin Accounts</div><span class="dash-sub">{{ $admins->count() }} total</span></div>
        <div class="panel-body" style="overflow-x:auto;">
            <table class="dtable">
                <thead><tr><th>Name</th><th>Email</th><th>Status</th><th style="text-align:right;">Actions</th></tr></thead>
                <tbody>
                @forelse($admins as $a)
                    <tr>
                        <td data-label="Name" style="font-weight:700;color:var(--navy);">{{ $a->name }}</td>
                        <td data-label="Email">{{ $a->email }}</td>
                        <td data-label="Status"><span class="status-pill {{ $a->is_active ? 'st-ACTIVE' : 'st-DRAFT' }}">{{ $a->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td data-label="Actions" style="text-align:right;">
                            <div class="flex gap-2" style="justify-content:flex-end;flex-wrap:wrap;">
                                @if($a->is_active)
                                    <form method="POST" action="{{ route('staff.deactivate',$a) }}">@csrf<button class="status-pill st-PENDING" style="border:none;cursor:pointer;">Deactivate</button></form>
                                @else
                                    <form method="POST" action="{{ route('staff.reactivate',$a) }}">@csrf<button class="status-pill st-ACTIVE" style="border:none;cursor:pointer;">Reactivate</button></form>
                                @endif
                                <form method="POST" action="{{ route('staff.destroy',$a) }}" onsubmit="return confirm('Permanently delete {{ $a->name }}?');">@csrf @method('DELETE')<button class="status-pill" style="border:none;cursor:pointer;background:#FFECEC;color:#c0392b;">Delete</button></form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:30px;">No admins yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- Tutors tab --}}
<div class="tab-panel {{ $isCeo ? '' : 'active' }}" data-panel="tutors">
    <div class="panel">
        <div class="panel-head"><div class="panel-title">Tutor Accounts</div><span class="dash-sub">{{ $tutors->count() }} total</span></div>
        <div class="panel-body" style="overflow-x:auto;">
            <table class="dtable">
                <thead><tr><th>Tutor</th><th>Contact</th><th>Status</th><th style="text-align:right;">Actions</th></tr></thead>
                <tbody>
                @forelse($tutors as $t)
                    <tr>
                        <td data-label="Tutor">
                            <div class="user-cell">
                                @if($t->photo())<img src="{{ $t->photo() }}" style="width:34px;height:34px;border-radius:50%;object-fit:cover;">
                                @else<span class="avatar-chip" style="background:#E67E22;">{{ strtoupper(substr($t->name,0,2)) }}</span>@endif
                                <span class="nm">{{ $t->name }}</span>
                            </div>
                        </td>
                        <td data-label="Contact" style="font-size:13px;color:#555;">
                            {{ $t->work_email ?? $t->email }}<br>{{ $t->phone ?? '—' }}
                        </td>
                        <td data-label="Status"><span class="status-pill {{ $t->is_active ? 'st-ACTIVE' : 'st-DRAFT' }}">{{ $t->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td data-label="Actions" style="text-align:right;">
                            <div class="flex gap-2" style="justify-content:flex-end;flex-wrap:wrap;">
                                @if($t->is_active)
                                    <form method="POST" action="{{ route('staff.deactivate',$t) }}">@csrf<button class="status-pill st-PENDING" style="border:none;cursor:pointer;">Deactivate</button></form>
                                @else
                                    <form method="POST" action="{{ route('staff.reactivate',$t) }}">@csrf<button class="status-pill st-ACTIVE" style="border:none;cursor:pointer;">Reactivate</button></form>
                                @endif
                                @if($isCeo)
                                    <form method="POST" action="{{ route('staff.destroy',$t) }}" onsubmit="return confirm('Permanently delete {{ $t->name }}?');">@csrf @method('DELETE')<button class="status-pill" style="border:none;cursor:pointer;background:#FFECEC;color:#c0392b;">Delete</button></form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:30px;">No tutors yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add new tab --}}
<div class="tab-panel" data-panel="add">
    <div class="grid md:grid-2 gap-6">
        @if($isCeo)
        <div class="panel">
            <div class="panel-head"><div class="panel-title">🛡️ Add Admin</div></div>
            <div class="panel-body padded">
                <form method="POST" action="{{ route('staff.admin.store') }}">@csrf
                    <div class="mb-4"><label class="label">Full Name</label><input type="text" name="name" class="input" required></div>
                    <div class="mb-4"><label class="label">Email</label><input type="email" name="email" class="input" required></div>
                    <div class="mb-4"><label class="label">Phone (optional)</label><input type="text" name="phone" class="input"></div>
                    <button class="btn-primary w-full">Create Admin Account</button>
                    <p style="font-size:12px;color:var(--muted);margin-top:10px;">A secure password is generated automatically and emailed to them.</p>
                </form>
            </div>
        </div>
        @endif
        <div class="panel">
            <div class="panel-head"><div class="panel-title">🧑‍🏫 Add Tutor</div></div>
            <div class="panel-body padded">
                <form method="POST" action="{{ route('staff.tutor.store') }}">@csrf
                    <div class="mb-4"><label class="label">Full Name</label><input type="text" name="name" class="input" required></div>
                    <div class="mb-4"><label class="label">Login Email</label><input type="email" name="email" class="input" required></div>
                    <div class="mb-4"><label class="label">Work Email (optional)</label><input type="email" name="work_email" class="input"></div>
                    <div class="mb-4"><label class="label">Phone</label><input type="text" name="phone" class="input" required></div>
                    <div class="mb-4"><label class="label">Short Bio (optional)</label><textarea name="bio" class="input" rows="3"></textarea></div>
                    <button class="btn-primary w-full">Create Tutor Account</button>
                    <p style="font-size:12px;color:var(--muted);margin-top:10px;">A secure password is generated automatically and emailed to them.</p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.dash-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.dash-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        tab.classList.add('active');
        document.querySelector(`.tab-panel[data-panel="${tab.dataset.tab}"]`).classList.add('active');
    });
});
</script>
@endsection
