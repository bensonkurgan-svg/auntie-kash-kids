@extends('layouts.dashboard')
@section('title', 'CMS Editor')
@section('content')
<div class="mb-8">
    <h1 style="font-size:30px;color:var(--navy);">CMS Content Editor</h1>
    <p style="color:var(--muted);margin-top:4px;">Edit your website content. {{ Auth::user()->isCEO() ? 'Changes publish immediately.' : 'Changes are submitted to the CEO for approval.' }}</p>
</div>

<div class="flex gap-2 mb-6" style="border-bottom:2px solid var(--border);">
    <button class="cms-tab active" data-tab="homepage" style="padding:12px 20px;background:none;border:none;border-bottom:2px solid var(--purple);color:var(--purple);font-weight:700;cursor:pointer;margin-bottom:-2px;">Homepage</button>
    <button class="cms-tab" data-tab="about" style="padding:12px 20px;background:none;border:none;color:var(--muted);font-weight:700;cursor:pointer;">About Page</button>
</div>

{{-- Homepage tab --}}
<form method="POST" action="{{ route('cms.submit') }}" class="cms-panel" data-panel="homepage">
    @csrf
    <input type="hidden" name="page_key" value="homepage">
    <div class="card mb-4">
        <h3 style="color:var(--navy);margin-bottom:16px;">Hero Section</h3>
        <div class="mb-4"><label class="label">Headline</label><input type="text" name="changes[hero][headline]" value="{{ $homepage['hero']['headline'] }}" class="input"></div>
        <div class="mb-4"><label class="label">Subheading</label><textarea name="changes[hero][subheading]" class="input" rows="2">{{ $homepage['hero']['subheading'] }}</textarea></div>
        <div class="grid md:grid-2 gap-4">
            <div><label class="label">Primary Button</label><input type="text" name="changes[hero][primaryCTA]" value="{{ $homepage['hero']['primaryCTA'] }}" class="input"></div>
            <div><label class="label">Secondary Button</label><input type="text" name="changes[hero][secondaryCTA]" value="{{ $homepage['hero']['secondaryCTA'] }}" class="input"></div>
        </div>
    </div>
    <div class="card mb-4">
        <h3 style="color:var(--navy);margin-bottom:16px;">About Preview</h3>
        <div><label class="label">Blurb</label><textarea name="changes[aboutPreview][blurb]" class="input" rows="3">{{ $homepage['aboutPreview']['blurb'] }}</textarea></div>
    </div>
    <button type="submit" class="btn-primary">{{ Auth::user()->isCEO() ? 'Publish Now' : 'Submit for Approval' }}</button>
</form>

{{-- About tab --}}
<form method="POST" action="{{ route('cms.submit') }}" class="cms-panel" data-panel="about" style="display:none;">
    @csrf
    <input type="hidden" name="page_key" value="about">
    <div class="card mb-4">
        <h3 style="color:var(--navy);margin-bottom:16px;">About Content</h3>
        <div class="mb-4"><label class="label">Headline</label><input type="text" name="changes[headline]" value="{{ $about['headline'] }}" class="input"></div>
        <div class="mb-4"><label class="label">Tagline</label><input type="text" name="changes[tagline]" value="{{ $about['tagline'] }}" class="input"></div>
        <div class="mb-4"><label class="label">Intro</label><textarea name="changes[intro]" class="input" rows="3">{{ $about['intro'] }}</textarea></div>
        <div class="mb-4"><label class="label">Mission</label><textarea name="changes[mission]" class="input" rows="3">{{ $about['mission'] }}</textarea></div>
        <div class="mb-4"><label class="label">Vision</label><textarea name="changes[vision]" class="input" rows="3">{{ $about['vision'] }}</textarea></div>
    </div>
    <div class="card mb-4">
        <h3 style="color:var(--navy);margin-bottom:16px;">Founder</h3>
        <div class="mb-4"><label class="label">Founder Name</label><input type="text" name="changes[founderName]" value="{{ $about['founderName'] }}" class="input"></div>
        <div class="mb-4"><label class="label">Founder Title</label><input type="text" name="changes[founderTitle]" value="{{ $about['founderTitle'] }}" class="input"></div>
        <div><label class="label">Founder Bio</label><textarea name="changes[founderBio]" class="input" rows="4">{{ $about['founderBio'] }}</textarea></div>
    </div>
    <button type="submit" class="btn-primary">{{ Auth::user()->isCEO() ? 'Publish Now' : 'Submit for Approval' }}</button>
</form>

<script>
document.querySelectorAll('.cms-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.cms-tab').forEach(t => { t.style.borderBottom = 'none'; t.style.color = 'var(--muted)'; t.classList.remove('active'); });
        tab.style.borderBottom = '2px solid var(--purple)'; tab.style.color = 'var(--purple)'; tab.classList.add('active');
        document.querySelectorAll('.cms-panel').forEach(p => p.style.display = p.dataset.panel === tab.dataset.tab ? 'block' : 'none');
    });
});
</script>
@endsection
