<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Illuminate\Console\Command;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;

/**
 * Updates specific blog_posts rows (matched by slug) from the SEO dossier texts
 * stored in database/content/articles/{fr,en}/<slug>.md.
 *
 * Touches only: title, excerpt, body, meta_title, meta_description (fr + en)
 * and reading_time_minutes. Never inserts, never deletes. Dry-run by default.
 */
class ImportArticles extends Command
{
    protected $signature = 'articles:import {--apply : Write to the database (default is a dry run)}';
    protected $description = 'Import the SEO dossier articles (and fix FR meta of older posts) by slug';

    /** French meta for posts whose fr meta fields currently contain English. */
    private const FR_META_FIX = [
        'pourquoi-externaliser-production-europe-est' =>
            'Économies réelles, qualité aux normes européennes et cadre réglementaire commun : les arguments en faveur de la sous-traitance industrielle en Europe de l\'Est.',
        'bulgarie-vs-roumanie-sous-traitance-industrielle' =>
            'Comparaison point par point de la Bulgarie et de la Roumanie comme destinations d\'externalisation : coûts, savoir-faire, logistique et notre recommandation.',
        'reduire-couts-fabrication-metallerie' =>
            'Quatre leviers concrets pour réduire vos coûts de production en métallerie sans sacrifier la qualité, de l\'externalisation au regroupement des commandes.',
        'comment-choisir-sous-traitant-industriel-fiable' =>
            'Cinq critères clés pour repérer un sous-traitant industriel fiable : certifications, capacités, références, communication et stabilité financière.',
    ];


    /**
     * Factual corrections the dossier made in the EN texts but which are still in the FR
     * texts already in the DB (FR is the source language, so both must say the same).
     * [slug, FR text to find, replacement]. Matching ignores &nbsp; and &#39; encoding.
     */
    private const FR_FIXES = [
        ['bulgarie-alternative-asie-sous-traitance-metallique', 'nous accompagnons depuis plus de dix ans des entreprises françaises', 'nous accompagnons des entreprises françaises'],
        ['bulgarie-alternative-asie-sous-traitance-metallique', "Le Règlement européen sur la déforestation (EUDR) et les exigences RSE", "Le mécanisme d'ajustement carbone aux frontières (MACF/CBAM), qui s'applique à l'acier importé, et les exigences RSE"],
        ['bulgarie-alternative-asie-sous-traitance-metallique', 'Un conteneur Shanghai-Le Havre', "Un conteneur de Shanghai vers un port d'Europe du Nord"],
        ['bulgarie-alternative-asie-sous-traitance-metallique', "une tradition industrielle héritée de l'ère soviétique", 'une longue tradition industrielle'],
        ['bulgarie-alternative-asie-sous-traitance-metallique', '(3-7 jours)', '(3 à 4 jours en camion complet, 7 à 10 jours en groupage)'],
        ['bulgarie-vs-roumanie-sous-traitance-industrielle', "un accès à la Méditerranée via les ports de Varna et Bourgas", "un accès à la mer Noire via les ports de Varna et Bourgas"],
    ];

    public function handle(): int
    {
        $apply = (bool) $this->option('apply');
        $this->info($apply ? 'APPLY MODE: writing to the database' : 'DRY RUN: nothing will be written');

        $dir = database_path('content/articles');
        $slugs = collect(glob("$dir/{fr,en}/*.md", GLOB_BRACE))->map(fn ($f) => basename($f, '.md'))->unique()->values();

        $environment = new Environment(['html_input' => 'allow', 'allow_unsafe_links' => false]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new TableExtension());
        $converter = new MarkdownConverter($environment);
        $errors = 0;

        foreach ($slugs as $slug) {
            $post = BlogPost::where('slug', $slug)->first();
            if (!$post) {
                $this->error("[$slug] no such post in DB, skipped");
                $errors++;
                continue;
            }

            $data = ['fr' => null, 'en' => null];
            // No FR file: the FR text already in the DB stays as is, only EN is replaced.
            $langs = is_file("$dir/fr/$slug.md") ? ['fr', 'en'] : ['en'];
            $enOnly = $langs === ['en'];
            foreach ($langs as $lang) {
                $file = "$dir/$lang/$slug.md";
                if (!is_file($file)) {
                    $this->error("[$slug] missing $lang file");
                    $errors++;
                    continue 2;
                }
                $data[$lang] = $this->parse(file_get_contents($file), $converter, $lang, $slug);
                if ($data[$lang] === null) {
                    $errors++;
                    continue 2;
                }
            }

            $this->line("<info>[$slug]</info> id={$post->id}" . ($enOnly ? ' (EN only)' : ''));
            foreach ($langs as $lang) {
                $d = $data[$lang];
                $this->line(sprintf(
                    '  %s  title=%d chars, meta_title=%d, meta_desc=%d, body=%d chars (was %d), read=%d min',
                    $lang, mb_strlen($d['h1']), mb_strlen($d['meta_title']), mb_strlen($d['meta_description']),
                    mb_strlen($d['body']), mb_strlen((string) $post->getTranslation('body', $lang, false)), $d['minutes']
                ));
                if ($apply) {
                    $post->setTranslation('title', $lang, $d['h1']);
                    if (!$enOnly) {
                        $post->setTranslation('excerpt', $lang, $d['excerpt']);
                    }
                    $post->setTranslation('body', $lang, $d['body']);
                    $post->setTranslation('meta_title', $lang, $d['meta_title']);
                    $post->setTranslation('meta_description', $lang, $d['meta_description']);
                }
            }
            if ($apply) {
                if (!$enOnly) {
                    $post->reading_time_minutes = $data['fr']['minutes'];
                }
                $post->save();
            }
        }

        foreach (self::FR_META_FIX as $slug => $desc) {
            $post = BlogPost::where('slug', $slug)->first();
            if (!$post) {
                $this->error("[$slug] no such post in DB, skipped");
                $errors++;
                continue;
            }
            $frTitle = $post->getTranslation('title', 'fr', false);
            $this->line("<info>[$slug]</info> id={$post->id}  fr meta -> \"$frTitle | Fab Sourcing\"");
            if ($apply) {
                $post->setTranslation('meta_title', 'fr', "$frTitle | Fab Sourcing");
                $post->setTranslation('meta_description', 'fr', $desc);
                $post->save();
            }
        }


        foreach (self::FR_FIXES as [$slug, $find, $replace]) {
            $post = BlogPost::where('slug', $slug)->first();
            if (!$post) {
                $this->error("[$slug] no such post in DB, skipped");
                $errors++;
                continue;
            }
            $body = $post->getTranslation('body', 'fr', false);
            $regex = '/' . str_replace([" ", "'"],['(?:\\s|&nbsp;)', "(?:'|&#39;|’)"], preg_quote($find, '/')) . '/u';
            $n = preg_match_all($regex, $body);
            if ($n === 1) {
                $this->line("<info>[$slug]</info> FR fix: \"$find\" -> \"$replace\"");
                if ($apply) {
                    $post->setTranslation('body', 'fr', preg_replace($regex, str_replace('\\', '\\\\', $replace), $body, 1));
                    $post->save();
                }
            } elseif ($n === 0 && preg_match('/' . str_replace([" ", "'"],['(?:\\s|&nbsp;)', "(?:'|&#39;|’)"], preg_quote($replace, '/')) . '/u', $body)) {
                $this->line("[$slug] FR fix already applied: \"$replace\"");
            } else {
                $this->error("[$slug] FR fix matched $n times, skipped: \"$find\"");
                $errors++;
            }
        }

        $this->info($errors ? "Done with $errors error(s)." : 'Done, no errors.');
        return $errors ? self::FAILURE : self::SUCCESS;
    }

    private function parse(string $md, MarkdownConverter $converter, string $lang, string $slug): ?array
    {
        // [0]='' [1]=front matter [2]=meta table [3]=body [4]=internal-link notes
        $parts = preg_split('/^---\s*$/m', $md);
        if (count($parts) !== 5) {
            $this->error("[$slug/$lang] unexpected structure (" . count($parts) . ' parts, expected 5)');
            return null;
        }

        $meta = [];
        foreach (explode("\n", $parts[2]) as $row) {
            if (preg_match('/^\|\s*([^|]+?)\s*\|\s*(.+?)\s*\|$/', $row, $m)) {
                $meta[$m[1]] = $m[2];
            }
        }
        $get = fn (array $keys) => collect($keys)->map(fn ($k) => $meta[$k] ?? null)->filter()->first();

        $metaTitle = $get(['Title']);
        $metaDesc  = $get(['Meta description']);
        $h1        = $get(['H1']);
        $minutes   = (int) $get(['Temps de lecture', 'Reading time']);
        if (!$metaTitle || !$metaDesc || !$h1 || !$minutes) {
            $this->error("[$slug/$lang] missing title/meta/H1/reading time in table");
            return null;
        }

        $body = trim($parts[3]);
        // Author byline at the top (the template already shows the author)
        $body = preg_replace('/\A\s*Thierry Sudol,[^\n]*\n/u', '', $body);
        // Drop the "Chapô" / "Standfirst" label line
        $body = preg_replace('/^\*\*(Chapô|Standfirst)\*\*\s*/u', '', $body);
        // Unfinished placeholder sentence: remove, never publish brackets
        $body = preg_replace('/\s*\[(À compléter|To be completed)[^\]]*\]/u', '', $body);
        // Quote button -> real CTA link
        $body = preg_replace_callback(
            '/^\*\*\[(?:(?:Bouton|Button)\s*:\s*)?(.+?)\s*→(?:\s*(\S+?))?\]\*\*\s*$/mu',
            fn ($m) => '<p><a class="btn btn-primary" href="' . e($m[2] ?? "/$lang/contact") . '">' . e($m[1]) . '</a></p>',
            $body
        );

        // Every article ends with a quote button
        if (!str_contains($body, 'btn btn-primary')) {
            $label = $lang === 'fr' ? 'Demander un devis' : 'Request a quote';
            $body .= "\n\n<p><a class=\"btn btn-primary\" href=\"/$lang/contact\">$label</a></p>";
        }

        if (preg_match('/\[(À compléter|To be completed|Bouton|Button)/iu', $body)) {
            $this->error("[$slug/$lang] unresolved placeholder left in body");
            return null;
        }

        $html = trim((string) $converter->convert($body));
        $firstP = preg_match('/<p>(.*?)<\/p>/su', $html, $pm) ? trim(strip_tags($pm[1])) : '';

        return [
            'h1' => $h1,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDesc,
            'minutes' => $minutes,
            'excerpt' => $firstP,
            'body' => $html,
        ];
    }
}
