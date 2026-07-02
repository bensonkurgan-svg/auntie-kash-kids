<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\ContentPost;
use HTMLPurifier;
use HTMLPurifier_Config;

class BlogController extends Controller
{
    /** CEO/Admin: list + manage blog posts. */
    public function index()
    {
        $posts = ContentPost::where('type','BLOG_POST')->latest()->get();
        return view('dashboard.blog-manager', compact('posts'));
    }

    public function store(Request $request)
    {
        if (! Auth::user()->canManageContent()) abort(403);

        $data = $request->validate([
            'title'    => ['required','string','max:255'],
            'excerpt'  => ['nullable','string','max:500'],
            'body'     => ['required','string'],
            'category' => ['nullable','string','max:100'],
            'cover_image' => ['nullable','image','max:5120'],
            'status'   => ['required','in:DRAFT,PUBLISHED'],
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('blog-images', 'public');
        }

        ContentPost::create([
            'type'      => 'BLOG_POST',
            'title'     => $data['title'],
            'slug'      => Str::slug($data['title']).'-'.Str::random(4),
            'excerpt'   => $data['excerpt'] ?? Str::limit(strip_tags($data['body']), 160),
            'body'      => $this->sanitize($data['body']),
            'category'  => $data['category'] ?? 'General',
            'cover_image' => $coverPath ? '/storage/'.$coverPath : null,
            'status'    => $data['status'],
            'read_time' => ceil(str_word_count(strip_tags($data['body'])) / 200).' min read',
            'author_id' => Auth::id(),
            'published_at' => $data['status'] === 'PUBLISHED' ? now() : null,
        ]);

        return back()->with('success', 'Blog post '.strtolower($data['status']).'.');
    }

    public function update(Request $request, ContentPost $post)
    {
        if (! Auth::user()->canManageContent()) abort(403);
        $data = $request->validate([
            'title'    => ['required','string','max:255'],
            'excerpt'  => ['nullable','string','max:500'],
            'body'     => ['required','string'],
            'category' => ['nullable','string','max:100'],
            'status'   => ['required','in:DRAFT,PUBLISHED'],
        ]);
        $post->update([
            'title'   => $data['title'],
            'excerpt' => $data['excerpt'] ?? $post->excerpt,
            'body'    => $this->sanitize($data['body']),
            'category'=> $data['category'] ?? $post->category,
            'status'  => $data['status'],
            'published_at' => $data['status'] === 'PUBLISHED' ? ($post->published_at ?? now()) : null,
        ]);
        return back()->with('success', 'Blog post updated.');
    }

    public function destroy(ContentPost $post)
    {
        if (! Auth::user()->canManageContent()) abort(403);
        $post->delete();
        return back()->with('success', 'Blog post deleted.');
    }

    /** Strip dangerous HTML — allow only safe formatting tags. */
    private function sanitize(string $html): string
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p,br,b,strong,i,em,u,h2,h3,h4,ul,ol,li,blockquote,a[href|target],img[src|alt],figure,figcaption');
        $config->set('HTML.TargetBlank', true);
        $config->set('AutoFormat.RemoveEmpty', true);
        $config->set('Cache.DefinitionImpl', null);
        return (new HTMLPurifier($config))->purify($html);
    }
}
