@extends('layouts.dashboard')
@section('title', 'Reading Library')
@section('content')
<div class="dash-header">
    <div><h1 class="dash-title">Reading Library</h1><p class="dash-sub">Add and manage books for the public reading library.</p></div>
    <div class="dash-date">{{ $books->count() }} books</div>
</div>

<div class="panel">
    <div class="panel-head"><div class="panel-title">➕ Add a Book</div></div>
    <div class="panel-body padded">
        <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data">@csrf
            <div class="grid md:grid-2 gap-4 mb-3">
                <div><label class="label">Title *</label><input type="text" name="title" class="input" required></div>
                <div><label class="label">Author</label><input type="text" name="author" class="input"></div>
            </div>
            <div class="grid md:grid-3 gap-4 mb-3">
                <div><label class="label">Category</label><select name="category" class="input"><option value="">—</option>@foreach($categories as $c)<option value="{{ $c }}">{{ $c }}</option>@endforeach</select></div>
                <div><label class="label">Age Group</label><select name="age_group" class="input"><option value="">—</option>@foreach($ageGroups as $a)<option value="{{ $a }}">Ages {{ $a }}</option>@endforeach</select></div>
                <div><label class="label">Country</label><input type="text" name="country" class="input" placeholder="e.g. Nigeria"></div>
            </div>
            <div class="grid md:grid-3 gap-4 mb-3">
                <div><label class="label">Genre</label><input type="text" name="genre" class="input"></div>
                <div><label class="label">Reading Level</label><input type="text" name="reading_level" class="input" placeholder="e.g. Beginner"></div>
                <div><label class="label">Reading Time</label><input type="text" name="reading_time" class="input" placeholder="e.g. 20 mins"></div>
            </div>
            <div class="mb-3"><label class="label">Themes</label><input type="text" name="themes" class="input" placeholder="e.g. Friendship, Courage, Family"></div>
            <div class="mb-3"><label class="label">About the Book</label><textarea name="about" class="input" rows="3"></textarea></div>
            <div class="grid md:grid-2 gap-4 mb-3">
                <div><label class="label">What Children Will Learn</label><textarea name="what_children_learn" class="input" rows="3"></textarea></div>
                <div><label class="label">Why We Recommend It</label><textarea name="why_recommend" class="input" rows="3"></textarea></div>
            </div>
            <div class="mb-3"><label class="label">Where to Find It (library, publisher, bookstore)</label><textarea name="where_to_find" class="input" rows="2"></textarea></div>
            <div class="grid md:grid-2 gap-4 mb-3">
                <div><label class="label">Purchase URL (Amazon, bookstore…)</label><input type="url" name="purchase_url" class="input" placeholder="https://..."></div>
                <div><label class="label">Cover Image</label><input type="file" name="cover" accept="image/*" class="input"></div>
            </div>
            <div class="flex gap-4 mb-4" style="flex-wrap:wrap;">
                <label class="flex items-center gap-2" style="font-size:14px;"><input type="checkbox" name="is_featured" value="1"> ⭐ Featured</label>
                <label class="flex items-center gap-2" style="font-size:14px;"><input type="checkbox" name="is_book_of_month" value="1"> 📅 Book of the Month</label>
            </div>
            <button class="btn-primary">Add Book</button>
        </form>
    </div>
</div>

<div class="panel">
    <div class="panel-head"><div class="panel-title">All Books</div></div>
    <div class="panel-body" style="overflow-x:auto;">
        <table class="dtable">
            <thead><tr><th>Title</th><th>Author</th><th>Category</th><th>Age</th><th>Flags</th><th style="text-align:right;">Actions</th></tr></thead>
            <tbody>
            @forelse($books as $book)
                <tr>
                    <td data-label="Title" style="font-weight:700;color:var(--navy);">{{ $book->title }}</td>
                    <td data-label="Author">{{ $book->author ?: '—' }}</td>
                    <td data-label="Category">{{ $book->category ?: '—' }}</td>
                    <td data-label="Age">{{ $book->age_group ? 'Ages '.$book->age_group : '—' }}</td>
                    <td data-label="Flags">
                        @if($book->is_book_of_month)<span class="status-pill st-APPROVED">📅 BOTM</span>@endif
                        @if($book->is_featured)<span class="status-pill st-ACTIVE">⭐</span>@endif
                        @if(!$book->is_published)<span class="status-pill st-DRAFT">Hidden</span>@endif
                    </td>
                    <td data-label="Actions" style="text-align:right;white-space:nowrap;">
                        <a href="{{ route('reading.book', $book) }}" target="_blank" class="status-pill st-CONTACTED" style="text-decoration:none;">View</a>
                        <button type="button" onclick="document.getElementById('edit-{{ $book->id }}').style.display=(document.getElementById('edit-{{ $book->id }}').style.display==='table-row'?'none':'table-row')" class="status-pill" style="border:none;cursor:pointer;background:#F0E8FF;color:#7B2FF7;">Edit</button>
                        <form method="POST" action="{{ route('books.destroy',$book) }}" onsubmit="return confirm('Remove this book?');" style="display:inline;">@csrf @method('DELETE')<button class="status-pill" style="border:none;cursor:pointer;background:#FFECEC;color:#c0392b;">Delete</button></form>
                    </td>
                </tr>
                <tr id="edit-{{ $book->id }}" style="display:none;background:#FAFAFE;">
                    <td colspan="6" style="padding:18px;">
                        <form method="POST" action="{{ route('books.update',$book) }}" enctype="multipart/form-data">@csrf @method('PUT')
                            <div class="grid md:grid-3 gap-3 mb-3">
                                <div><label class="label">Title *</label><input type="text" name="title" value="{{ $book->title }}" class="input" required></div>
                                <div><label class="label">Author</label><input type="text" name="author" value="{{ $book->author }}" class="input"></div>
                                <div><label class="label">Country</label><input type="text" name="country" value="{{ $book->country }}" class="input"></div>
                            </div>
                            <div class="grid md:grid-3 gap-3 mb-3">
                                <div><label class="label">Category</label><select name="category" class="input"><option value="">—</option>@foreach($categories as $c)<option value="{{ $c }}" {{ $book->category==$c?'selected':'' }}>{{ $c }}</option>@endforeach</select></div>
                                <div><label class="label">Age Group</label><select name="age_group" class="input"><option value="">—</option>@foreach($ageGroups as $a)<option value="{{ $a }}" {{ $book->age_group==$a?'selected':'' }}>Ages {{ $a }}</option>@endforeach</select></div>
                                <div><label class="label">Genre</label><input type="text" name="genre" value="{{ $book->genre }}" class="input"></div>
                            </div>
                            <div class="mb-3"><label class="label">About</label><textarea name="about" class="input" rows="2">{{ $book->about }}</textarea></div>
                            <div class="grid md:grid-2 gap-3 mb-3">
                                <div><label class="label">Purchase URL</label><input type="url" name="purchase_url" value="{{ $book->purchase_url }}" class="input"></div>
                                <div><label class="label">Replace Cover</label><input type="file" name="cover" accept="image/*" class="input"></div>
                            </div>
                            <input type="hidden" name="reading_level" value="{{ $book->reading_level }}">
                            <input type="hidden" name="reading_time" value="{{ $book->reading_time }}">
                            <input type="hidden" name="themes" value="{{ $book->themes }}">
                            <input type="hidden" name="what_children_learn" value="{{ $book->what_children_learn }}">
                            <input type="hidden" name="why_recommend" value="{{ $book->why_recommend }}">
                            <input type="hidden" name="where_to_find" value="{{ $book->where_to_find }}">
                            <div class="flex gap-4 mb-3" style="flex-wrap:wrap;">
                                <label class="flex items-center gap-2" style="font-size:14px;"><input type="checkbox" name="is_featured" value="1" {{ $book->is_featured?'checked':'' }}> ⭐ Featured</label>
                                <label class="flex items-center gap-2" style="font-size:14px;"><input type="checkbox" name="is_book_of_month" value="1" {{ $book->is_book_of_month?'checked':'' }}> 📅 Book of the Month</label>
                                <label class="flex items-center gap-2" style="font-size:14px;"><input type="checkbox" name="is_published" value="1" {{ $book->is_published?'checked':'' }}> Published</label>
                            </div>
                            <button class="btn-primary" style="min-height:42px;">Save Changes</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:30px;">No books yet. Add your first book above.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
