<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Canonical short descriptions — concise, complete, no truncation.
        // Used on both homepage cards and /produits catalogue cards via x-cat-card component.
        $categories = [
            [
                'slug'          => 'escaliers-metalliques',
                'old_slugs'     => ['escaliers-metalliques-sur-mesure', 'escaliers-metalliques'],
                'featured'      => true,
                'featured_order'=> 1,
                'sort_order'    => 1,
                'name_fr'       => 'Escaliers métalliques',
                'name_en'       => 'Metal stairs',
                'desc_fr'       => 'Escaliers industriels et architecturaux en acier, intérieur et extérieur, aux normes européennes.',
                'desc_en'       => 'Industrial and architectural steel stairs, indoor and outdoor, to European standards.',
            ],
            [
                'slug'          => 'garde-corps-mains-courantes',
                'old_slugs'     => ['garde-corps-rampes-inox-acier', 'garde-corps-rampes'],
                'featured'      => true,
                'featured_order'=> 2,
                'sort_order'    => 2,
                'name_fr'       => 'Garde-corps et mains courantes',
                'name_en'       => 'Railings and handrails',
                'desc_fr'       => 'Solutions en inox, acier et aluminium pour la sécurité et le design, sur mesure.',
                'desc_en'       => 'Stainless steel, steel and aluminium solutions for safety and design, made to measure.',
            ],
            [
                'slug'          => 'structures-metalliques',
                'old_slugs'     => ['charpente-structures-metalliques-acier', 'structures-metalliques'],
                'featured'      => true,
                'featured_order'=> 3,
                'sort_order'    => 3,
                'name_fr'       => 'Structures métalliques',
                'name_en'       => 'Metal structures',
                'desc_fr'       => 'Charpentes, structures spécifiques et pièces mécano-soudées pour bâtiments industriels.',
                'desc_en'       => 'Frameworks, custom structures and welded assemblies for industrial buildings.',
            ],
            [
                'slug'          => 'racks-equipements-industriels',
                'old_slugs'     => ['rack-industriel-shelter-stockage', 'racks-shelters'],
                'featured'      => true,
                'featured_order'=> 4,
                'sort_order'    => 4,
                'name_fr'       => 'Racks et équipements industriels',
                'name_en'       => 'Industrial racks and equipment',
                'desc_fr'       => 'Racks de stockage et solutions logistiques métalliques sur mesure pour entrepôts.',
                'desc_en'       => 'Storage racks and custom metal logistics solutions for warehouses.',
            ],
            [
                'slug'          => 'bacs-retention-shelters',
                'old_slugs'     => ['verriere-atelier-cloison-vitree', 'verrieres-cloisons'],
                'featured'      => true,
                'featured_order'=> 5,
                'sort_order'    => 5,
                'name_fr'       => 'Bacs de rétention et shelters',
                'name_en'       => 'Retention basins and shelters',
                'desc_fr'       => 'Bacs de rétention acier aux normes CE, shelters techniques et abris métalliques.',
                'desc_en'       => 'CE-standard steel retention basins, technical shelters and metal enclosures.',
            ],
            [
                'slug'          => 'menuiseries-metalliques',
                'old_slugs'     => ['fenetres-portes-menuiseries-metalliques', 'menuiseries-metalliques'],
                'featured'      => false,
                'featured_order'=> null,
                'sort_order'    => 6,
                'name_fr'       => 'Menuiseries métalliques',
                'name_en'       => 'Metal joinery',
                'desc_fr'       => 'Portes blindées, baies vitrées style industriel et fenêtres acier haute performance.',
                'desc_en'       => 'Armoured doors, industrial-style glazed bays and high-performance steel windows.',
            ],
            [
                'slug'          => 'bardages-facades',
                'old_slugs'     => ['bardage-facade-metallique-industriel', 'bardages-facades'],
                'featured'      => false,
                'featured_order'=> null,
                'sort_order'    => 7,
                'name_fr'       => 'Bardages et façades',
                'name_en'       => 'Cladding and facades',
                'desc_fr'       => 'Revêtements architecturaux en acier, aluminium, zinc ou inox, finitions au choix.',
                'desc_en'       => 'Architectural cladding in steel, aluminium, zinc or stainless steel, choice of finishes.',
            ],
            [
                'slug'          => 'portails-clotures',
                'old_slugs'     => ['portail-cloture-acier-sur-mesure', 'portails-clotures'],
                'featured'      => false,
                'featured_order'=> null,
                'sort_order'    => 8,
                'name_fr'       => 'Portails et clôtures',
                'name_en'       => 'Gates and fences',
                'desc_fr'       => 'Portails motorisés et clôtures en panneaux rigides, fer forgé ou tôle perforée.',
                'desc_en'       => 'Motorised gates and fences in rigid panels, wrought iron or perforated sheet.',
            ],
            [
                'slug'          => 'terrasses-balcons',
                'old_slugs'     => ['terrasse-balcon-garde-corps-exterieur', 'terrasses-balcons'],
                'featured'      => false,
                'featured_order'=> null,
                'sort_order'    => 9,
                'name_fr'       => 'Terrasses et balcons métalliques',
                'name_en'       => 'Metal terraces and balconies',
                'desc_fr'       => 'Terrasses suspendues et balcons préfabriqués en structure acier IPN/HEA.',
                'desc_en'       => 'Suspended terraces and prefabricated balconies in IPN/HEA steel structure.',
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
