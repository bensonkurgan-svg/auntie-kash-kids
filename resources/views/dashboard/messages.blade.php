@extends('layouts.dashboard')
@section('title', 'Messages')
@section('content')
@php $me = Auth::id(); @endphp
<div class="dash-header">
    <div><h1 class="dash-title">Messages</h1><p class="dash-sub">Private chats and the community board.</p></div>
</div>

<div class="dash-tabs">
    <button class="dash-tab active" data-tab="private">💬 Private</button>
    <button class="dash-tab" data-tab="public">📣 Community Board</button>
</div>

{{-- Private --}}
<div class="tab-panel active" data-panel="private">
    <div class="grid" style="grid-template-columns:240px 1fr;gap:16px;align-items:start;">
        {{-- Contacts --}}
        <div class="panel" style="margin:0;max-height:560px;overflow:auto;">
            <div class="panel-head"><div class="panel-title" style="font-size:15px;">Contacts</div></div>
            <div class="panel-body">
                @foreach($contacts as $c)
                    <a href="{{ route('messages', ['with'=>$c->id]) }}" class="flex items-center gap-2" style="padding:10px 16px;{{ $activeId==$c->id ? 'background:#F0E8FF;' : '' }}">
                        <span class="avatar-chip" style="background:#7B2FF7;">{{ strtoupper(substr($c->name,0,2)) }}</span>
                        <span style="min-width:0;"><span class="nm" style="display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $c->name }}</span><span class="em">{{ ucfirst(strtolower($c->role)) }}</span></span>
                    </a>
                @endforeach
            </div>
        </div>
        {{-- Conversation --}}
        <div class="panel" style="margin:0;display:flex;flex-direction:column;height:560px;">
            <div class="panel-body" style="flex:1;overflow:auto;padding:18px;" id="convo">
                @forelse($conversation as $msg)
                    <div style="display:flex;margin-bottom:10px;{{ $msg->sender_id==$me ? 'justify-content:flex-end;' : '' }}">
                        <div style="max-width:70%;padding:10px 14px;border-radius:14px;font-size:14px;line-height:1.5;{{ $msg->sender_id==$me ? 'background:linear-gradient(135deg,#7B2FF7,#FF3E9E);color:#fff;border-bottom-right-radius:4px;' : 'background:var(--surface);color:#333;border-bottom-left-radius:4px;' }}">
                            {{ $msg->body }}
                            <div style="font-size:10px;opacity:0.7;margin-top:4px;">{{ $msg->created_at->format('M j, g:i a') }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted" style="padding:40px;">No messages yet. Say hello! 👋</p>
                @endforelse
            </div>
            @if($activeId)
            <form method="POST" action="{{ route('messages.private') }}" style="border-top:1px solid var(--border);padding:14px;display:flex;gap:10px;">@csrf
                <input type="hidden" name="recipient_id" value="{{ $activeId }}">
                <input type="text" name="body" class="input" placeholder="Type a message…" required style="flex:1;" maxlength="2000">
                <button class="btn-primary" style="min-height:44px;">Send</button>
            </form>
            @endif
        </div>
    </div>
</div>

{{-- Public board --}}
<div class="tab-panel" data-panel="public">
    <div class="panel" style="display:flex;flex-direction:column;height:560px;">
        <div class="panel-body" style="flex:1;overflow:auto;padding:18px;">
            @forelse($publicMessages as $msg)
                <div style="margin-bottom:14px;">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="avatar-chip" style="background:#2AA7FF;width:28px;height:28px;font-size:11px;">{{ strtoupper(substr($msg->sender->name ?? '?',0,2)) }}</span>
                        <span style="font-weight:700;font-size:13px;color:var(--navy);">{{ $msg->sender->name ?? 'Unknown' }}</span>
                        <span class="role-pill role-{{ $msg->sender->role ?? 'STUDENT' }}" style="font-size:9px;">{{ $msg->sender->role ?? '' }}</span>
                        <span class="dash-sub" style="font-size:11px;">{{ $msg->created_at->diffForHumans() }}</span>
                    </div>
                    <div style="padding:10px 14px;background:var(--surface);border-radius:12px;font-size:14px;line-height:1.5;margin-left:36px;">{{ $msg->body }}</div>
                </div>
            @empty
                <p class="text-center text-muted" style="padding:40px;">No posts yet. Start the conversation!</p>
            @endforelse
        </div>
        <form method="POST" action="{{ route('messages.public') }}" style="border-top:1px solid var(--border);padding:14px;display:flex;gap:10px;">@csrf
            <input type="text" name="body" class="input" placeholder="Post to everyone…" required style="flex:1;" maxlength="2000">
            <button class="btn-primary" style="min-height:44px;">Post</button>
        </form>
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
// Auto-scroll conversation to bottom
const convo = document.getElementById('convo'); if(convo) convo.scrollTop = convo.scrollHeight;
</script>
@endsection
