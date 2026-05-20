<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search', '');
        $status = $request->input('status', '');

        $posts = BlogPost::query()
            ->when($search, fn($q) => $q->where('title', 'like', "%{$search}%"))
            ->when($status === 'draft',     fn($q) => $q->whereNull('published_at'))
            ->when($status === 'published', fn($q) => $q->whereNotNull('published_at')->where('published_at', '<=', now()))
            ->when($status === 'scheduled', fn($q) => $q->whereNotNull('published_at')->where('published_at', '>', now()))
            ->orderByDesc('published_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.blog.index', compact('posts', 'search', 'status'));
    }

    public function create(): View
    {
        return view('admin.blog.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title.fr'            => 'required|string|max:255',
            'title.en'            => 'nullable|string|max:255',
            'excerpt.fr'          => 'nullable|string',
            'excerpt.en'          => 'nullable|string',
            'body.fr'             => 'nullable|string',
            'body.en'             => 'nullable|string',
            'author_name'         => 'nullable|string|max:120',
            'meta_title.fr'       => 'nullable|string|max:255',
            'meta_title.en'       => 'nullable|string|max:255',
            'meta_description.fr' => 'nullable|string|max:500',
            'meta_description.en' => 'nullable|string|max:500',
            'featured_image_file' => 'nullable|image|max:4096',
        ]);

        $post = new BlogPost();
        $post->author_name = $request->input('author_name') ?: 'Thierry Sudol';
        $post->slug        = $this->uniqueSlug($request->input('slug') ?: $request->input('title.fr'));

        $this->fillTranslatable($post, $request);
        $this->handlePublishMode($post, $request);
        $this->handleFeaturedImage($post, $request);
        $post->reading_time_minutes = $this->calcReadingTime($request->input('body.fr', ''));
        $post->save();

        if ($request->input('action') === 'continue') {
            return redirect()->route('blog-posts.edit', $post)
                ->with('success', 'Статията е създадена. Продължете редактирането.');
        }
        return redirect()->route('blog-posts.index')
            ->with('success', 'Статия „' . $post->getTranslation('title', 'fr', false) . '" е създадена.');
    }

    public function edit(BlogPost $blogPost): View
    {
        return view('admin.blog.edit', ['post' => $blogPost]);
    }

    public function update(Request $request, BlogPost $blogPost): RedirectResponse
    {
        $request->validate([
            'title.fr'            => 'required|string|max:255',
            'title.en'            => 'nullable|string|max:255',
            'excerpt.fr'          => 'nullable|string',
            'excerpt.en'          => 'nullable|string',
            'body.fr'             => 'nullable|string',
            'body.en'             => 'nullable|string',
            'author_name'         => 'nullable|string|max:120',
            'meta_title.fr'       => 'nullable|string|max:255',
            'meta_title.en'       => 'nullable|string|max:255',
            'meta_description.fr' => 'nullable|string|max:500',
            'meta_description.en' => 'nullable|string|max:500',
            'featured_image_file' => 'nullable|image|max:4096',
        ]);

        $blogPost->author_name = $request->input('author_name') ?: 'Thierry Sudol';
        $blogPost->slug        = $this->uniqueSlug(
            $request->input('slug') ?: $request->input('title.fr'),
            $blogPost->id
        );

        $this->fillTranslatable($blogPost, $request);
        $this->handlePublishMode($blogPost, $request);
        $this->handleFeaturedImage($blogPost, $request);
        $blogPost->reading_time_minutes = $this->calcReadingTime($request->input('body.fr', ''));
        $blogPost->save();

        if ($request->input('action') === 'continue') {
            return redirect()->route('blog-posts.edit', $blogPost)
                ->with('success', 'Записано.');
        }
        return redirect()->route('blog-posts.index')
            ->with('success', 'Статията е обновена.');
    }

    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        if ($blogPost->featured_image) {
            Storage::disk('public')->delete($blogPost->featured_image);
        }
        $title = $blogPost->getTranslation('title', 'fr', false);
        $blogPost->delete();

        return redirect()->route('blog-posts.index')
            ->with('success', 'Статия „' . $title . '" е изтрита.');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function fillTranslatable(BlogPost $post, Request $request): void
    {
        foreach (['title', 'excerpt', 'body', 'meta_title', 'meta_description'] as $field) {
            foreach (['fr', 'en'] as $locale) {
                $v = $request->input("{$field}.{$locale}");
                if ($v !== null) $post->setTranslation($field, $locale, $v);
            }
        }
        foreach (['fr', 'en'] as $locale) {
            $tags = array_values(array_filter(
                $request->input("tags_{$locale}", []),
                fn($t) => trim($t) !== ''
            ));
            $post->setTranslation('tags', $locale, $tags);
        }
    }

    private function handlePublishMode(BlogPost $post, Request $request): void
    {
        $mode = $request->input('publish_mode', 'draft');
        if ($mode === 'now') {
            $post->published_at = now();
        } elseif ($mode === 'schedule' && $request->input('published_at')) {
            $post->published_at = $request->input('published_at');
        } else {
            $post->published_at = null;
        }
    }

    private function handleFeaturedImage(BlogPost $post, Request $request): void
    {
        if ($request->boolean('remove_featured_image') && $post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
            $post->featured_image = null;
            return;
        }
        if ($request->input('featured_image_path')) {
            $post->featured_image = $request->input('featured_image_path');
            return;
        }
        if ($request->hasFile('featured_image_file')) {
            if ($post->featured_image) Storage::disk('public')->delete($post->featured_image);
            $post->featured_image = $request->file('featured_image_file')->store('blog', 'public');
        }
    }

    private function uniqueSlug(string $base, ?int $excludeId = null): string
    {
        $slug = Str::slug($base) ?: 'post';
        $orig = $slug;
        $i    = 1;
        while (BlogPost::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()) {
            $slug = $orig . '-' . $i++;
        }
        return $slug;
    }

    private function calcReadingTime(string $html): int
    {
        $words = str_word_count(strip_tags($html));
        return max(1, (int) ceil($words / 200));
    }
}
