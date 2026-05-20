<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $xml .= '  <sitemap><loc>' . url('/sitemap-fr.xml') . '</loc></sitemap>' . "\n";
        $xml .= '  <sitemap><loc>' . url('/sitemap-en.xml') . '</loc></sitemap>' . "\n";
        $xml .= '</sitemapindex>';

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }

    public function lang(Request $request): Response
    {
        $lang = str_contains($request->path(), '-fr.') ? 'fr' : 'en';
        $urls = $this->staticUrls($lang);

        ProductCategory::where('published', true)
            ->get()
            ->each(function ($cat) use (&$urls, $lang) {
                $urls[] = [
                    'loc'        => $cat->getUrlForLang($lang),
                    'changefreq' => 'monthly',
                    'priority'   => '0.7',
                    'lastmod'    => $cat->updated_at->toAtomString(),
                ];
            });

        Product::where('published', true)
            ->with('category')
            ->get()
            ->each(function ($prod) use (&$urls, $lang) {
                $urls[] = [
                    'loc'        => $prod->getUrlForLang($lang),
                    'changefreq' => 'weekly',
                    'priority'   => '0.6',
                    'lastmod'    => $prod->updated_at->toAtomString(),
                ];
            });

        BlogPost::whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->get()
            ->each(function ($post) use (&$urls, $lang) {
                $urls[] = [
                    'loc'        => route('blog.show', ['lang' => $lang, 'slug' => $post->slug]),
                    'changefreq' => 'weekly',
                    'priority'   => '0.6',
                    'lastmod'    => $post->updated_at->toAtomString(),
                ];
            });

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . "</loc>\n";
            $xml .= "    <changefreq>{$url['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$url['priority']}</priority>\n";
            if (isset($url['lastmod'])) {
                $xml .= "    <lastmod>{$url['lastmod']}</lastmod>\n";
            }
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }

    private function staticUrls(string $lang): array
    {
        $routes = $lang === 'fr' ? [
            ['name' => 'home',           'params' => ['lang' => 'fr'], 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['name' => 'services',       'params' => ['lang' => 'fr'], 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['name' => 'products',       'params' => ['lang' => 'fr'], 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['name' => 'blog',           'params' => ['lang' => 'fr'], 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['name' => 'why',            'params' => ['lang' => 'fr'], 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['name' => 'method',         'params' => ['lang' => 'fr'], 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['name' => 'about',          'params' => ['lang' => 'fr'], 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['name' => 'contact',        'params' => ['lang' => 'fr'], 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['name' => 'legal.mentions', 'params' => ['lang' => 'fr'], 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['name' => 'legal.privacy',  'params' => ['lang' => 'fr'], 'priority' => '0.3', 'changefreq' => 'yearly'],
        ] : [
            ['name' => 'home',              'params' => ['lang' => 'en'], 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['name' => 'services',          'params' => ['lang' => 'en'], 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['name' => 'products.en',       'params' => ['lang' => 'en'], 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['name' => 'blog',              'params' => ['lang' => 'en'], 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['name' => 'why.en',            'params' => ['lang' => 'en'], 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['name' => 'method.en',         'params' => ['lang' => 'en'], 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['name' => 'about.en',          'params' => ['lang' => 'en'], 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['name' => 'contact',           'params' => ['lang' => 'en'], 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['name' => 'legal.mentions.en', 'params' => ['lang' => 'en'], 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['name' => 'legal.privacy.en',  'params' => ['lang' => 'en'], 'priority' => '0.3', 'changefreq' => 'yearly'],
        ];

        return array_map(fn ($r) => [
            'loc'        => route($r['name'], $r['params']),
            'changefreq' => $r['changefreq'],
            'priority'   => $r['priority'],
        ], $routes);
    }
}
