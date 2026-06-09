<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        // ── One-time merge: "Racks et équipements industriels" + "Bacs de rétention et shelters"
        //    → single category "Racks, Bacs de rétention".
        //    Move any products off the merged-away "bacs" row BEFORE it gets repurposed below
        //    (the bacs row is reused as the new "Skid" category via old_slugs — no row is deleted).
        $mergeTarget = ProductCategory::whereIn('slug', ['racks-bacs-retention', 'racks-equipements-industriels'])->first();
        $bacs        = ProductCategory::where('slug', 'bacs-retention-shelters')->first();
        if ($mergeTarget && $bacs && $mergeTarget->id !== $bacs->id) {
            Product::where('product_category_id', $bacs->id)
                ->update(['product_category_id' => $mergeTarget->id]);
        }

        // Canonical short descriptions — concise, complete, no truncation.
        // Used on both homepage cards and /produits catalogue cards via x-cat-card component.
        $categories = [
            [
                'slug'          => 'cuves-acier-inox',
                'old_slugs'     => [],
                'featured'      => true,
                'featured_order'=> 1,
                'sort_order'    => 1,
                'name_fr'       => 'Cuves Acier carbone et Inox',
                'name_en'       => 'Carbon steel and stainless tanks',
                'desc_fr'       => 'Cuve de stockage, Cuves sous pression, Cuves pour Chimie, Cuves agroalimentaires, Cuves pour hydrocarbures.',
                'desc_en'       => 'Storage tanks, pressure vessels, chemical tanks, food-grade tanks and hydrocarbon tanks.',
            ],
            [
                'slug'          => 'escaliers-metalliques',
                'old_slugs'     => ['escaliers-metalliques-sur-mesure', 'escaliers-metalliques'],
                'featured'      => true,
                'featured_order'=> 2,
                'sort_order'    => 2,
                'name_fr'       => 'Escaliers métalliques',
                'name_en'       => 'Metal stairs',
                'desc_fr'       => "Escaliers industriels et architecturaux en acier, intérieur et extérieur, conformes aux normes européennes de sécurité et d'accessibilité.",
                'desc_en'       => 'Industrial and architectural steel stairs, indoor and outdoor, compliant with European safety and accessibility standards.',
            ],
            [
                'slug'          => 'garde-corps-mains-courantes',
                'old_slugs'     => ['garde-corps-rampes-inox-acier', 'garde-corps-rampes'],
                'featured'      => true,
                'featured_order'=> 3,
                'sort_order'    => 3,
                'name_fr'       => 'Garde-corps et mains courantes',
                'name_en'       => 'Railings and handrails',
                'desc_fr'       => 'Solutions en inox, acier et aluminium alliant sécurité structurelle et design architectural, sur mesure selon vos cotes.',
                'desc_en'       => 'Stainless steel, steel and aluminium solutions combining structural safety and architectural design, made to measure to your dimensions.',
            ],
            [
                'slug'          => 'structures-metalliques',
                'old_slugs'     => ['charpente-structures-metalliques-acier', 'structures-metalliques'],
                'featured'      => true,
                'featured_order'=> 4,
                'sort_order'    => 4,
                'name_fr'       => 'Structures métalliques',
                'name_en'       => 'Metal structures',
                'desc_fr'       => 'Charpentes, ossatures spécifiques et pièces mécano-soudées pour bâtiments industriels, certifiées selon Eurocodes et normes soudure.',
                'desc_en'       => 'Frameworks, custom load-bearing structures and welded assemblies for industrial buildings, certified to Eurocodes and welding standards.',
            ],
            [
                'slug'          => 'racks-bacs-retention',
                'old_slugs'     => ['racks-equipements-industriels', 'rack-industriel-shelter-stockage', 'racks-shelters'],
                'featured'      => true,
                'featured_order'=> 5,
                'sort_order'    => 5,
                'name_fr'       => 'Racks, Bacs de rétention',
                'name_en'       => 'Racks and retention basins',
                'desc_fr'       => 'Racks et Bacs de rétention de stockage et solutions logistiques métalliques sur mesure, optimisés pour la charge et la durabilité.',
                'desc_en'       => 'Storage racks and retention basins, plus custom metal logistics solutions, optimised for load and durability.',
            ],
            [
                'slug'          => 'skid-machines-shelter',
                'old_slugs'     => ['bacs-retention-shelters', 'verriere-atelier-cloison-vitree', 'verrieres-cloisons'],
                'featured'      => true,
                'featured_order'=> 6,
                'sort_order'    => 6,
                'name_fr'       => 'Skid pour machines et shelter',
                'name_en'       => 'Machine skids and shelters',
                'desc_fr'       => 'Skid pour machines aux normes CE, shelters techniques et abris métalliques pour matériel industriels.',
                'desc_en'       => 'CE-standard machine skids, technical shelters and metal enclosures for industrial equipment.',
            ],
            [
                'slug'          => 'menuiseries-metalliques',
                'old_slugs'     => ['fenetres-portes-menuiseries-metalliques', 'menuiseries-metalliques'],
                'featured'      => false,
                'featured_order'=> null,
                'sort_order'    => 7,
                'name_fr'       => 'Menuiseries métalliques',
                'name_en'       => 'Metal joinery',
                'desc_fr'       => 'Portes blindées, baies vitrées style industriel et fenêtres acier haute performance, avec isolation thermique et sécurité.',
                'desc_en'       => 'Armoured doors, industrial-style glazed bays and high-performance steel windows, with thermal insulation and security.',
            ],
            [
                'slug'          => 'balcons',
                'old_slugs'     => ['bardages-facades', 'bardage-facade-metallique-industriel'],
                'featured'      => false,
                'featured_order'=> null,
                'sort_order'    => 8,
                'name_fr'       => 'Balcons',
                'name_en'       => 'Balconies',
                'desc_fr'       => 'Balcons métalliques sur mesure, préfabriqués en structure acier avec garde-corps intégrés.',
                'desc_en'       => 'Custom metal balconies, prefabricated steel structure with integrated railings.',
            ],
            [
                'slug'          => 'portails-clotures',
                'old_slugs'     => ['portail-cloture-acier-sur-mesure', 'portails-clotures'],
                'featured'      => false,
                'featured_order'=> null,
                'sort_order'    => 9,
                'name_fr'       => 'Portails et clôtures',
                'name_en'       => 'Gates and fences',
                'desc_fr'       => 'Portails motorisés et clôtures en panneaux rigides, fer forgé ou tôle perforée, sur mesure et conformes aux normes de sécurité.',
                'desc_en'       => 'Motorised gates and fences in rigid panels, wrought iron or perforated sheet, made to measure and compliant with safety standards.',
            ],
            [
                'slug'          => 'terrasses-balcons',
                'old_slugs'     => ['terrasse-balcon-garde-corps-exterieur', 'terrasses-balcons'],
                'featured'      => false,
                'featured_order'=> null,
                'sort_order'    => 10,
                'name_fr'       => 'Terrasses et balcons métalliques',
                'name_en'       => 'Metal terraces and balconies',
                'desc_fr'       => 'Terrasses suspendues et balcons préfabriqués en structure acier S235/S355, avec garde-corps intégrés et planchers antidérapants.',
                'desc_en'       => 'Suspended terraces and prefabricated balconies in S235/S355 steel structure, with integrated railings and anti-slip floors.',
            ],
        ];

        foreach ($categories as $data) {
            // Find by new slug first, then try old slugs to update in place (preserves IDs)
            $cat = ProductCategory::where('slug', $data['slug'])->first();

            if (!$cat) {
                foreach ($data['old_slugs'] as $oldSlug) {
                    $cat = ProductCategory::where('slug', $oldSlug)->first();
                    if ($cat) break;
                }
            }

            $cat ??= new ProductCategory();

            $cat->slug           = $data['slug'];
            $cat->sort_order     = $data['sort_order'];
            $cat->published      = true;
            $cat->featured       = $data['featured'];
            $cat->featured_order = $data['featured_order'];

            $cat->setTranslation('name', 'fr', $data['name_fr']);
            $cat->setTranslation('name', 'en', $data['name_en']);
            $cat->setTranslation('description', 'fr', $data['desc_fr']);
            $cat->setTranslation('description', 'en', $data['desc_en']);

            $cat->save();
        }
    }
}
