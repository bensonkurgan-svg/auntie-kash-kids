<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Book;

class LibraryBookController extends Controller
{
    // Category + age definitions from the client's spec
    public const CATEGORIES = [
        'Nigerian Classics', 'African Classics', 'World Classics',
        'Folktales', 'Biographies', 'STEM & Science', 'Bible Stories',
    ];
    public const AGE_GROUPS = ['3-5', '6-8', '9-12', '13-18'];

    /** Public reading library landing page. */
    public function index(Request $request)
    {
        $search   = $request->input('q');
        $category = $request->input('category');
        $age      = $request->input('age');
        $country  = $request->input('country');

        $query = Book::published();
        if ($search) {
            $query->where(fn($q) => $q->where('title','like',"%{$search}%")
                ->orWhere('author','like',"%{$search}%")
                ->orWhere('genre','like',"%{$search}%")
                ->orWhere('themes','like',"%{$search}%"));
        }
        if ($category) $query->where('category', $category);
        if ($age)      $query->where('age_group', $age);
        if ($country)  $query->where('country', $country);

        $books = $query->latest()->get();

        $bookOfMonth = Book::published()->where('is_book_of_month', true)->latest()->first();
        $featured    = Book::published()->where('is_featured', true)->latest()->take(4)->get();
        $recent      = Book::published()->latest()->take(6)->get();
        $countries   = Book::published()->whereNotNull('country')->distinct()->orderBy('country')->pluck('country');

        $isFiltering = $search || $category || $age || $country;

        return view('pages.reading-library', compact(
            'books','bookOfMonth','featured','recent','countries',
            'search','category','age','country','isFiltering'
        ));
    }

    /** Individual book page. */
    public function show(Book $book)
    {
        abort_if(! $book->is_published, 404);
        $related = Book::published()
            ->where('id','!=',$book->id)
            ->where(fn($q) => $q->where('category',$book->category)->orWhere('age_group',$book->age_group))
            ->take(4)->get();
        return view('pages.book', compact('book','related'));
    }

    /** CEO/Admin: manage books. */
    public function manage()
    {
        if (! Auth::user()->canManageContent()) abort(403);
        $books = Book::latest()->get();
        $categories = self::CATEGORIES;
        $ageGroups = self::AGE_GROUPS;
        return view('dashboard.library-books-manager', compact('books','categories','ageGroups'));
    }

    public function store(Request $request)
    {
        if (! Auth::user()->canManageContent()) abort(403);
        $data = $this->validateBook($request);
        $data['cover_image'] = $this->handleCover($request);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_book_of_month'] = $request->boolean('is_book_of_month');
        $data['is_published'] = true;
        $data['created_by'] = Auth::id();

        // Only one book of the month at a time
        if ($data['is_book_of_month']) {
            Book::where('is_book_of_month', true)->update(['is_book_of_month' => false]);
        }
        Book::create($data);
        return back()->with('success', 'Book added to the library.');
    }

    public function update(Request $request, Book $book)
    {
        if (! Auth::user()->canManageContent()) abort(403);
        $data = $this->validateBook($request);
        if ($cover = $this->handleCover($request)) $data['cover_image'] = $cover;
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_book_of_month'] = $request->boolean('is_book_of_month');
        $data['is_published'] = $request->boolean('is_published', true);

        if ($data['is_book_of_month']) {
            Book::where('is_book_of_month', true)->where('id','!=',$book->id)->update(['is_book_of_month' => false]);
        }
        $book->update($data);
        return back()->with('success', 'Book updated.');
    }

    public function destroy(Book $book)
    {
        if (! Auth::user()->canManageContent()) abort(403);
        $book->delete();
        return back()->with('success', 'Book removed.');
    }

    private function validateBook(Request $request): array
    {
        return $request->validate([
            'title'        => ['required','string','max:255'],
            'author'       => ['nullable','string','max:255'],
            'country'      => ['nullable','string','max:100'],
            'age_group'    => ['nullable','string','max:20'],
            'genre'        => ['nullable','string','max:100'],
            'reading_level'=> ['nullable','string','max:100'],
            'reading_time' => ['nullable','string','max:100'],
            'category'     => ['nullable','string','max:100'],
            'about'        => ['nullable','string','max:2000'],
            'what_children_learn' => ['nullable','string','max:2000'],
            'themes'       => ['nullable','string','max:255'],
            'why_recommend'=> ['nullable','string','max:2000'],
            'where_to_find'=> ['nullable','string','max:1000'],
            'purchase_url' => ['nullable','url','max:500'],
        ]);
    }

    private function handleCover(Request $request): ?string
    {
        $request->validate(['cover' => ['nullable','image','max:5120']]);
        if ($request->hasFile('cover')) {
            return '/storage/'.$request->file('cover')->store('book-covers', 'public');
        }
        return null;
    }
}
