<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;
use App\Models\BlogPost;
use App\Models\Page;
use Illuminate\Http\Request;

class BlogController extends WebPagesController
{
    public function index(Request $request, string $lang = 'fr')
    {
        $search = $request->input('search', '');
        $tag    = $request->input('tag', '');

        $query = BlogPost::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        if ($tag) {
            $query->where('tags', 'like', '%' . addslashes($tag) . '%');
        }

        $posts = $query->paginate(10)->withQueryString();

        // Build tag cloud from ALL published posts
        $allTags = BlogPost::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->pluck('tags')
            ->flatMap(function ($tagsJson) use ($lang) {
                if (is_array($tagsJson)) {
                    return $tagsJson[$lang] ?? $tagsJson['fr'] ?? [];
                }
                $decoded = json_decode($tagsJson, true);
                return $decoded[$lang] ?? $decoded['fr'] ?? [];
            })
            ->filter()
            ->countBy()
            ->sortByDesc(fn ($count) => $count)
            ->take(20)
            ->keys()
            ->all();

        $blogPage = Page::where('slug', 'blog')->first();

        $langSwitcherUrls = [
            'fr' => route('blog', 'fr') . ($search ? '?search=' . urlencode($search) : ''),
            'en' => route('blog', 'en') . ($search ? '?search=' . urlencode($search) : ''),
        ];

        return view('web.blog.index', array_merge(
            $this->commonForWebPages($lang),
            compact('posts', 'allTags', 'search', 'tag', 'blogPage', 'langSwitcherUrls')
        ));
    }

    public function show(string $lang, string $slug)
    {
        $post = BlogPost::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('slug', $slug)
            ->firstOrFail();

        // 3 related posts sharing at least one tag
        $tags = $post->getTranslation('tags', $lang, false)
              ?: $post->getTranslation('tags', 'fr', false)
              ?: [];

        $related = collect();
        if (!empty($tags)) {
            $firstTag = $tags[0];
            $related = BlogPost::whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->where('id', '!=', $post->id)
                ->where('tags', 'like', '%' . addslashes($firstTag) . '%')
                ->orderByDesc('published_at')
                ->take(3)
                ->get();
        }
        if ($related->count() < 3) {
            $related = $related->merge(
                BlogPost::whereNotNull('published_at')
                    ->where('published_at', '<=', now())
                    ->where('id', '!=', $post->id)
                    ->whereNotIn('id', $related->pluck('id'))
                    ->orderByDesc('published_at')
                    ->take(3 - $related->count())
                    ->get()
            );
        }

        $langSwitcherUrls = [
            'fr' => route('blog.show', ['lang' => 'fr', 'slug' => $slug]),
            'en' => route('blog.show', ['lang' => 'en', 'slug' => $slug]),
        ];

        return view('web.blog.show', array_merge(
            $this->commonForWebPages($lang),
            compact('post', 'related', 'langSwitcherUrls')
        ));
    }
}
