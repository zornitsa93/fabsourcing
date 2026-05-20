<?php
namespace Database\Seeders;

use App\Models\MethodStep;
use Illuminate\Database\Seeder;

class MethodStepsSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            [
                'number'     => '01',
                'sort_order' => 1,
                'title'      => ['fr' => 'Analyse du besoin',        'en' => 'Needs analysis'],
                'description'=> ['fr' => 'Nous étudions vos plans, cahier des charges et contraintes techniques pour bien cadrer votre projet.', 'en' => 'We study your drawings, specifications and technical constraints to properly frame your project.'],
            ],
            [
                'number'     => '02',
                'sort_order' => 2,
                'title'      => ['fr' => 'Étude technique',          'en' => 'Technical study'],
                'description'=> ['fr' => 'Analyse de faisabilité et identification des procédés adaptés à votre projet.', 'en' => 'Feasibility analysis and identification of suitable processes for your project.'],
            ],
            [
                'number'     => '03',
                'sort_order' => 3,
                'title'      => ['fr' => 'Sélection fournisseur',    'en' => 'Supplier selection'],
                'description'=> ['fr' => 'Identification et qualification des ateliers partenaires les mieux adaptés à vos exigences techniques.', 'en' => 'Identification and qualification of partner workshops best suited to your technical requirements.'],
            ],
            [
                'number'     => '04',
                'sort_order' => 4,
                'title'      => ['fr' => 'Prototype / pré-série',    'en' => 'Prototype / pre-production'],
                'description'=> ['fr' => 'Fabrication de pièces de validation pour confirmer la conformité avant le lancement de la production.', 'en' => 'Production of validation pieces to confirm conformity before launching production.'],
            ],
            [
                'number'     => '05',
                'sort_order' => 5,
                'title'      => ['fr' => 'Production',               'en' => 'Production'],
                'description'=> ['fr' => 'Lancement de la fabrication série avec suivi de production tout au long du processus.', 'en' => 'Launch of series production with monitoring throughout the process.'],
            ],
            [
                'number'     => '06',
                'sort_order' => 6,
                'title'      => ['fr' => 'Contrôle qualité',         'en' => 'Quality control'],
                'description'=> ['fr' => 'Inspection, suivi de production et conformité aux normes européennes avant expédition.', 'en' => 'Inspection, production monitoring and compliance with European standards before shipping.'],
            ],
            [
                'number'     => '07',
                'sort_order' => 7,
                'title'      => ['fr' => 'Livraison',                'en' => 'Delivery'],
                'description'=> ['fr' => 'Organisation du transport vers la France et coordination avec votre service réception.', 'en' => 'Transport organisation to France and coordination with your receiving department.'],
            ],
        ];

        foreach ($steps as $data) {
            $step = MethodStep::firstOrNew(['number' => $data['number']]);
            $step->sort_order = $data['sort_order'];
            foreach (['fr', 'en'] as $locale) {
                $step->setTranslation('title',       $locale, $data['title'][$locale]);
                $step->setTranslation('description', $locale, $data['description'][$locale]);
            }
            $step->save();
        }
    }
}
