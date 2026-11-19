<?php

namespace App\Console\Commands;

use App\Models\MethodStep;
use App\Models\Page;
use App\Models\ProductCategory;
use App\Models\Service;
use Illuminate\Console\Command;

/**
 * Applies the SEO-dossier wording (EN version + the matching FR corrections) to
 * rows that hold site copy: pages, services, product categories.
 *
 * Only the listed translatable fields of the listed rows are touched. Nothing is
 * created or deleted. Dry run by default; prints old -> new for every change.
 * Idempotent: a field that already has the target value is reported as unchanged.
 */
class SyncSiteContent extends Command
{
    protected $signature = 'site:sync-content {--apply : Write to the database (default is a dry run)}';
    protected $description = 'Apply SEO dossier wording (EN + matching FR fixes) to pages, services and product categories';

    /** pages: slug => field => lang => value */
    private function pages(): array
    {
        return [
            'home' => [
                'meta_title'       => ['fr' => 'Sous-traitance métallerie en Bulgarie – Fab Sourcing', 'en' => 'Metal fabrication outsourcing in Bulgaria – Fab Sourcing'],
                'meta_description' => [
                    'fr' => 'Escaliers, garde-corps, structures acier fabriqués en Bulgarie par des ateliers EN 1090. Interlocuteur français, devis sous 48 h.',
                    'en' => 'Steel structures, stairs, railings and tanks made to your drawings in EN 1090 workshops in Bulgaria. EU quality, lower costs, quote within 48 h.',
                ],
                'hero_heading'     => ['fr' => "Sous-traitance métallerie\net structures acier\nen Bulgarie", 'en' => "Bulgaria:\nyour reliable source\nfor metal fabrication"],
                'hero_lede'        => ['en' => 'We help European companies cut their production costs by outsourcing metal fabrication to qualified partner workshops in Bulgaria – with one point of contact from quote to delivery.'],
            ],
            'services' => [
                'meta_title'       => ['fr' => 'Sous-traitance industrielle en Bulgarie : nos services'],
                'meta_description' => ['fr' => "Sourcing d'usines, fabrication, ingénierie Eurocodes, contrôle qualité et transport jusqu'à votre site. Un seul interlocuteur francophone."],
            ],
            'blog' => [
                'meta_title'       => ['fr' => 'Blog sous-traitance métallique – Fab Sourcing', 'en' => 'Metal subcontracting blog – Fab Sourcing'],
                'meta_description' => ['fr' => "Conseils d'acheteur : EN 1090, coût complet, choix d'un sous-traitant, fabrication en Bulgarie. Articles rédigés par un métallier.", 'en' => "Buyer's guides: EN 1090, total cost of ownership, choosing a subcontractor, manufacturing in Bulgaria. Articles written by a metalwork professional."],
            ],
            'methodology' => [
                'meta_title'       => ['en' => 'Our 8-step method – Fab Sourcing'],
                'meta_description' => ['en' => 'Our 8-step process: needs analysis, technical study, supplier selection, prototype, production, progress report, quality control, delivery.'],
            ],
        ];
    }

    /** method_steps: step number (sort_order) => field => lang => value */
    private function methodSteps(): array
    {
        return [
            4 => [
                'title'       => ['en' => 'Prototype / pre-series'],
                'description' => ['en' => 'Manufacture of validation parts to confirm dimensional compliance, tolerances and finishes before series production starts.'],
            ],
            8 => [
                'description' => [
                    'fr' => 'Transport routier optimisé : 3 à 4 jours en camion complet vers la France, 7 à 10 jours en groupage, avec livraison sur site ou en entrepôt selon vos besoins logistiques.',
                    'en' => 'Optimised road transport – 3–4 days by full truckload to France, 7–10 days groupage – with delivery to your site or to a warehouse, depending on your logistics needs.',
                ],
            ],
        ];
    }

    /** services: FR title => field => lang => value */
    private function services(): array
    {
        return [
            'Sourcing industriel' => [
                'description' => ['en' => 'Identification and qualification of suppliers matching your technical requirements.'],
            ],
            'Sous-traitance (outsourcing)' => [
                'description' => ['en' => 'Partial or full outsourcing of your production.'],
            ],
            'Industrialisation' => [
                'description' => ['en' => 'Drawing optimisation, process selection, cost reduction.'],
            ],
            'Solutions en ingénierie et design' => [
                'title'       => ['en' => 'Engineering and design'],
                'description' => ['en' => 'Eurocode calculations, 3D design.'],
            ],
            'Gestion logistique' => [
                'description' => [
                    'fr' => "Transport routier optimisé jusqu'à votre site : 3 à 4 jours en camion complet vers la France, 7 à 10 jours en groupage.",
                    'en' => 'Optimised road transport to your site – 3–4 days by full truckload to France, 7–10 days groupage.',
                ],
            ],
            'Contrôle qualité' => [
                'description' => ['en' => 'Inspection, production monitoring and compliance with European standards.'],
            ],
        ];
    }

    /** product_categories: id => field => lang => value */
    private function categories(): array
    {
        return [
            10 => [
                'name'        => ['en' => 'Carbon steel and stainless steel tanks'],
                'description' => ['en' => 'Storage tanks, pressure vessels, tanks for the chemical and food industries, hydrocarbon tanks.'],
            ],
            2 => [
                'name' => ['en' => 'Metal staircases'],
            ],
            3 => [
                'description' => ['en' => 'Stainless steel, steel and aluminium solutions combining structural safety and architectural design, made to your dimensions.'],
            ],
            1 => [
                'description' => [
                    'fr' => 'Charpentes, ossatures spécifiques et pièces mécano-soudées pour bâtiments industriels, conçues selon les Eurocodes et les normes de soudage.',
                    'en' => 'Frames, specific structures and welded assemblies for industrial buildings, designed to Eurocodes and welding standards.',
                ],
            ],
            9 => [
                'name'        => ['en' => 'Racks and spill trays'],
                'description' => ['en' => 'Storage racks, spill containment trays and custom metal logistics solutions, optimised for load and durability.'],
            ],
            6 => [
                'description' => ['en' => 'CE-compliant machine skids, technical shelters and metal enclosures for industrial equipment.'],
            ],
            4 => [
                'name'        => ['en' => 'Metal doors and windows'],
                'description' => ['en' => 'Security doors, industrial-style glazed walls and high-performance steel windows, with thermal insulation and security.'],
            ],
            5 => [
                'description' => ['en' => 'Suspended terraces and prefabricated balconies with S235/S355 steel structure, integrated railings and anti-slip decking.'],
            ],
            7 => [
                'description' => ['en' => 'Motorised gates and fences in rigid panels, wrought iron or perforated sheet, made to measure and compliant with safety standards.'],
            ],
        ];
    }

    public function handle(): int
    {
        $apply = (bool) $this->option('apply');
        $this->info($apply ? 'APPLY MODE: writing to the database' : 'DRY RUN: nothing will be written');
        $errors = 0;

        foreach ($this->pages() as $slug => $fields) {
            $errors += $this->sync("page $slug", Page::where('slug', $slug)->first(), $fields, $apply);
        }
        foreach ($this->methodSteps() as $step => $fields) {
            $errors += $this->sync("method step $step", MethodStep::where('sort_order', $step)->first(), $fields, $apply);
        }
        foreach ($this->services() as $frTitle => $fields) {
            $row = Service::all()->first(fn ($s) => $s->getTranslation('title', 'fr', false) === $frTitle);
            $errors += $this->sync("service \"$frTitle\"", $row, $fields, $apply);
        }
        foreach ($this->categories() as $id => $fields) {
            $errors += $this->sync("category #$id", ProductCategory::find($id), $fields, $apply);
        }

        $this->info($errors ? "Done with $errors error(s)." : 'Done, no errors.');
        return $errors ? self::FAILURE : self::SUCCESS;
    }

    private function sync(string $label, $model, array $fields, bool $apply): int
    {
        if (!$model) {
            $this->error("[$label] row not found, skipped");
            return 1;
        }
        $changed = false;
        foreach ($fields as $field => $byLang) {
            foreach ($byLang as $lang => $new) {
                $old = $model->getTranslation($field, $lang, false);
                if ($old === $new) {
                    continue;
                }
                $this->line("<info>[$label]</info> $field.$lang");
                $this->line('   - ' . str_replace("\n", '⏎', mb_strimwidth((string) $old, 0, 150, '…')));
                $this->line('   + ' . str_replace("\n", '⏎', mb_strimwidth($new, 0, 150, '…')));
                if ($apply) {
                    $model->setTranslation($field, $lang, $new);
                    $changed = true;
                }
            }
        }
        if ($changed) {
            $model->save();
        }
        return 0;
    }
}
