@extends('layouts.dashboard')
@section('title', 'Blog Posts')
@section('content')
<div class="dash-header">
    <div><h1 class="dash-title">Blog Posts</h1><p class="dash-sub">Write and publish articles for parents and children.</p></div>
</div>

{{-- Editor --}}
<div class="panel">
    <div class="panel-head"><div class="panel-title">✍️ New Blog Post</div></div>
    <div class="panel-body padded">
        <form method="POST" action="{{ route('blog.store') }}" enctype="multipart/form-data" onsubmit="document.getElementById('bodyField').value = document.getElementById('editor').innerHTML;">@csrf
            <div class="grid md:grid-2 gap-4 mb-3">
                <div><label class="label">Title</label><input type="text" name="title" class="input" required></div>
                <div><label class="label">Category</label><input type="text" name="category" class="input" placeholder="e.g. Parenting"></div>
            </div>
            <div class="mb-3"><label class="label">Excerpt (optional)</label><input type="text" name="excerpt" class="input" placeholder="Short summary for the blog list"></div>
            <div class="mb-3"><label class="label">Cover Image (optional)</label><input type="file" name="cover_image" accept="image/*" class="input"></div>

            <label class="label">Content</label>
            <div class="rte-toolbar">
                <button type="button" onclick="document.execCommand('bold')" title="Bold"><b>B</b></button>
                <button type="button" onclick="document.execCommand('italic')" title="Italic"><i>I</i></button>
                <button type="button" onclick="document.execCommand('underline')" title="Underline"><u>U</u></button>
                <button type="button" onclick="document.execCommand('formatBlock',false,'h2')" title="Heading">H2</button>
                <button type="button" onclick="document.execCommand('formatBlock',false,'h3')" title="Subheading">H3</button>
                <button type="button" onclick="document.execCommand('insertUnorderedList')" title="Bullets">• List</button>
                <button type="button" onclick="document.execCommand('insertOrderedList')" title="Numbered">1. List</button>
                <button type="button" onclick="document.execCommand('formatBlock',false,'blockquote')" title="Quote">❝ Quote</button>
                <button type="button" onclick="addLink()" title="Link">🔗 Link</button>
                <button type="button" onclick="addImage()" title="Image">🖼️ Image</button>
            </div>
            <div id="editor" class="rte-editor" contenteditable="true"><p>Start writing your post here…</p></div>
            <textarea name="body" id="bodyField" style="display:none;"></textarea>

            <div class="flex gap-3 mt-4">
                <button class="btn-primary" name="status" value="PUBLISHED">Publish</button>
                <button class="btn-secondary" name="status" value="DRAFT">Save as Draft</button>
            </div>
        </form>
    </div>
</div>

{{-- Existing posts --}}
<div class="panel">
    <div class="panel-head"><div class="panel-title">All Posts</div><span class="dash-sub">{{ $posts->count() }} total</span></div>
    <div class="panel-body" style="overflow-x:auto;">
        <table class="dtable">
            <thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Date</th><th style="text-align:right;">Action</th></tr></thead>
            <tbody>
            @forelse($posts as $p)
                <tr>
                    <td data-label="Title" style="font-weight:700;color:var(--navy);">{{ $p->title }}</td>
                    <td data-label="Category">{{ $p->category }}</td>
                    <td data-label="Status"><span class="status-pill st-{{ $p->status }}">{{ $p->status }}</span></td>
                    <td data-label="Date" class="dash-sub">{{ $p->created_at->format('M j, Y') }}</td>
                    <td data-label="Action" style="text-align:right;">
                        <div class="flex gap-2" style="justify-content:flex-end;">
                            <button type="button" onclick="var r=document.getElementById('edit-post-{{ $p->id }}');r.style.display=(r.style.display==='table-row'?'none':'table-row')" class="status-pill st-CONTACTED" style="border:none;cursor:pointer;">✏️ Edit</button>
                            <form method="POST" action="{{ route('blog.destroy',$p) }}" onsubmit="return confirm('Delete this post?');" style="display:inline;">@csrf @method('DELETE')<button class="status-pill" style="border:none;cursor:pointer;background:#FFECEC;color:#c0392b;">Delete</button></form>
                        </div>
                    </td>
                </tr>
                {{-- Inline edit form --}}
                <tr id="edit-post-{{ $p->id }}" style="display:none;background:#FAFAFE;">
                    <td colspan="5" style="padding:20px 22px;">
                        <form method="POST" action="{{ route('blog.update',$p) }}" onsubmit="document.getElementById('editbody-{{ $p->id }}').value = document.getElementById('editor-{{ $p->id }}').innerHTML;">@csrf @method('PUT')
                            <div class="grid md:grid-2 gap-4 mb-3">
                                <div><label class="label">Title</label><input type="text" name="title" value="{{ $p->title }}" class="input" required></div>
                                <div><label class="label">Category</label><input type="text" name="category" value="{{ $p->category }}" class="input"></div>
                            </div>
                            <div class="mb-3"><label class="label">Excerpt</label><input type="text" name="excerpt" value="{{ $p->excerpt }}" class="input"></div>
                            <div class="mb-3">
                                <label class="label">Content</label>
                                <div class="rte-toolbar">
                                    <button type="button" onmousedown="event.preventDefault();document.execCommand('bold')"><b>B</b></button>
                                    <button type="button" onmousedown="event.preventDefault();document.execCommand('italic')"><i>I</i></button>
                                    <button type="button" onmousedown="event.preventDefault();document.execCommand('formatBlock',false,'h2')">H2</button>
                                    <button type="button" onmousedown="event.preventDefault();document.execCommand('insertUnorderedList')">• List</button>
                                </div>
                                <div id="editor-{{ $p->id }}" class="rte-editor" contenteditable="true">{!! $p->body !!}</div>
                                <textarea name="body" id="editbody-{{ $p->id }}" style="display:none;"></textarea>
                            </div>
                            <div class="flex gap-3">
                                <button class="btn-primary" name="status" value="PUBLISHED" style="min-height:42px;">Update & Publish</button>
                                <button class="btn-secondary" name="status" value="DRAFT" style="min-height:42px;">Save as Draft</button>
                            </div>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:30px;">No blog posts yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.rte-toolbar { display:flex; flex-wrap:wrap; gap:4px; padding:8px; background:var(--surface); border:1px solid var(--border); border-bottom:none; border-radius:12px 12px 0 0; }
.rte-toolbar button { background:#fff; border:1px solid var(--border); border-radius:7px; padding:6px 11px; cursor:pointer; font-size:13px; transition:all 120ms; }
.rte-toolbar button:hover { background:#F0E8FF; border-color:var(--purple); }
.rte-editor { min-height:260px; border:1px solid var(--border); border-radius:0 0 12px 12px; padding:16px; font-size:15px; line-height:1.7; outline:none; }
.rte-editor:focus { border-color:var(--purple); }
.rte-editor h2 { font-size:24px; margin:16px 0 8px; } .rte-editor h3 { font-size:19px; margin:14px 0 6px; }
.rte-editor blockquote { border-left:3px solid var(--purple); padding-left:14px; color:#666; font-style:italic; }
</style>
<script>
function addLink(){ const url = prompt('Enter URL:'); if(url) document.execCommand('createLink', false, url); }
function addImage(){ const url = prompt('Enter image URL:'); if(url) document.execCommand('insertImage', false, url); }
document.getElementById('editor').addEventListener('focus', function(){ if(this.innerHTML.includes('Start writing')) this.innerHTML='<p></p>'; });
</script>
@endsection
