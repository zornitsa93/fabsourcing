<?php

// CANONICAL services list. Do not modify without explicit user instruction.
// These come from the source PDF page 2 "NOS SERVICES" and represent the client's
// actual services as an outsourcing intermediary — NOT manufacturing techniques.

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        $canonical_slugs = [
            'sourcing-industriel',
            'sous-traitance',
            'industrialisation',
            'gestion-logistique',
            'controle-qualite',
        ];

        $services = [
            [
                'slug'       => 'sourcing-industriel',
                'number'     => '01',
                'col_span'   => 5,
                'featured'   => false,
                'published'  => true,
                'sort_order' => 1,
                'title_fr'   => 'Sourcing industriel',
                'title_en'   => 'Industrial sourcing',
                'desc_fr'    => 'Identification et qualification de fournisseurs adaptés à vos exigences techniques.',
                'desc_en'    => 'Identifying and qualifying suppliers that match your technical requirements.',
            ],
            [
                'slug'       => 'sous-traitance',
                'number'     => '02',
                'col_span'   => 7,
                'featured'   => true,
                'published'  => true,
                'sort_order' => 2,
                'title_fr'   => 'Sous-traitance (outsourcing)',
                'title_en'   => 'Subcontracting (outsourcing)',
                'desc_fr'    => 'Externalisation partielle ou complète de votre production.',
                'desc_en'    => 'Partial or complete outsourcing of your production.',
            ],
            [
                'slug'       => 'industrialisation',
                'number'     => '03',
                'col_span'   => 4,
                'featured'   => false,
                'published'  => true,
                'sort_order' => 3,
                'title_fr'   => 'Industrialisation',
                'title_en'   => 'Industrialization',
                'desc_fr'    => 'Optimisation des plans, choix des procédés, réduction des coûts.',
                'desc_en'    => 'Optimizing plans, selecting manufacturing processes, reducing costs.',
            ],
            [
                'slug'       => 'gestion-logistique',
                'number'     => '04',
                'col_span'   => 4,
                'featured'   => false,
                'published'  => true,
                'sort_order' => 4,
                'title_fr'   => 'Gestion logistique',
                'title_en'   => 'Logistics management',
                'desc_fr'    => 'Transport optimisé vers la France (3–4 jours par route).',
                'desc_en'    => 'Optimized transport to France (3–4 days by road).',
            ],
            [
                'slug'       => 'controle-qualite',
                'number'     => '05',
                'col_span'   => 4,
                'featured'   => false,
                'published'  => true,
                'sort_order' => 5,
                'title_fr'   => 'Contrôle qualité',
                'title_en'   => 'Quality control',
                'desc_fr'    => 'Inspection, suivi de production et conformité aux normes européennes.',
                'desc_en'    => 'Inspection, production monitoring, and compliance with European standards.',
            ],
        ];

        // Remove any services that are not in the canonical list
        Service::whereNotIn('slug', $canonical_slugs)->delete();

        foreach ($services as $data) {
            $service = Service::firstOrNew(['slug' => $data['slug']]);
            $service->number     = $data['number'];
            $service->col_span   = $data['col_span'];
            $service->featured   = $data['featured'];
            $service->published  = $data['published'];
            $service->sort_order = $data['sort_order'];
            $service->setTranslation('title',       'fr', $data['title_fr']);
            $service->setTranslation('title',       'en', $data['title_en']);
            $service->setTranslation('description', 'fr', $data['desc_fr']);
            $service->setTranslation('description', 'en', $data['desc_en']);
            $service->save();
        }
    }
}
