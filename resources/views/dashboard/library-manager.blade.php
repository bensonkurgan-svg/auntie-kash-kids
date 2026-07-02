@extends('layouts.dashboard')
@section('title', 'Resource Library')
@section('content')
<div class="dash-header">
    <div><h1 class="dash-title">Resource Library</h1><p class="dash-sub">Add resources for instructors and articles for the Parent Resource Centre.</p></div>
</div>

<div class="panel">
    <div class="panel-head"><div class="panel-title">➕ Add Resource</div></div>
    <div class="panel-body padded">
        <form method="POST" action="{{ route('library.store') }}" enctype="multipart/form-data" onsubmit="if(document.getElementById('lib-editor').style.display!=='none'){document.getElementById('lib-body').value=document.getElementById('lib-editor').innerHTML;}">@csrf
            <div class="grid md:grid-2 gap-4 mb-3">
                <div><label class="label">Title</label><input type="text" name="title" class="input" required></div>
                <div><label class="label">Category</label><input type="text" name="category" class="input" placeholder="e.g. Reading & Literacy, Teaching Guidelines"></div>
            </div>
            <div class="grid md:grid-2 gap-4 mb-3">
                <div><label class="label">Audience</label><select name="audience" class="input" required><option value="INSTRUCTOR">Instructors (portal)</option><option value="PARENT">Parents (public centre)</option></select></div>
                <div><label class="label">Type</label>
                    <select name="content_type" class="input" id="libType" required>
                        <option value="DOCUMENT">Document (download)</option>
                        <option value="ARTICLE">Article (written)</option>
                        <option value="LINK">Web Link</option>
                        <option value="VIDEO">Video Link</option>
                    </select>
                </div>
            </div>
            <div class="mb-3"><label class="label">Short Description</label><textarea name="description" class="input" rows="2"></textarea></div>

            <div class="mb-3" id="libFile"><label class="label">File</label><input type="file" name="file" class="input"></div>
            <div class="mb-3" id="libUrl" style="display:none;"><label class="label">URL</label><input type="url" name="external_url" class="input" placeholder="https://..."></div>
            <div class="mb-3" id="libBody" style="display:none;">
                <label class="label">Article Content</label>
                <div class="rte-toolbar">
                    <button type="button" onmousedown="event.preventDefault();document.execCommand('bold')"><b>B</b></button>
                    <button type="button" onmousedown="event.preventDefault();document.execCommand('italic')"><i>I</i></button>
                    <button type="button" onmousedown="event.preventDefault();document.execCommand('formatBlock',false,'h2')">H2</button>
                    <button type="button" onmousedown="event.preventDefault();document.execCommand('insertUnorderedList')">• List</button>
                </div>
                <div id="lib-editor" class="rte-editor" contenteditable="true"><p></p></div>
                <textarea name="body" id="lib-body" style="display:none;"></textarea>
            </div>

            <label class="flex items-center gap-2 mb-4" style="font-size:14px;"><input type="checkbox" name="is_featured" value="1"> Feature this resource</label>
            <button class="btn-primary">Add Resource</button>
        </form>
    </div>
</div>

<div class="panel">
    <div class="panel-head"><div class="panel-title">All Resources</div><span class="dash-sub">{{ $resources->count() }} total</span></div>
    <div class="panel-body" style="overflow-x:auto;">
        <table class="dtable">
            <thead><tr><th>Title</th><th>Audience</th><th>Category</th><th>Type</th><th style="text-align:right;">Action</th></tr></thead>
            <tbody>
            @forelse($resources as $r)
                <tr>
                    <td data-label="Title" style="font-weight:700;color:var(--navy);">{{ $r->typeIcon() }} {{ $r->title }}</td>
                    <td data-label="Audience"><span class="role-pill {{ $r->audience=='PARENT' ? 'role-PARENT' : 'role-TUTOR' }}">{{ $r->audience }}</span></td>
                    <td data-label="Category">{{ $r->category ?: '—' }}</td>
                    <td data-label="Type">{{ $r->content_type }}</td>
                    <td data-label="Action" style="text-align:right;">
                        <form method="POST" action="{{ route('library.destroy',$r) }}" onsubmit="return confirm('Remove this resource?');" style="display:inline;">@csrf @method('DELETE')<button class="status-pill" style="border:none;cursor:pointer;background:#FFECEC;color:#c0392b;">Delete</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:30px;">No resources yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('libType').addEventListener('change', function(){
    var t=this.value;
    document.getElementById('libFile').style.display = (t==='DOCUMENT') ? 'block':'none';
    document.getElementById('libUrl').style.display = (t==='LINK'||t==='VIDEO') ? 'block':'none';
    document.getElementById('libBody').style.display = (t==='ARTICLE') ? 'block':'none';
});
</script>
@endsection
