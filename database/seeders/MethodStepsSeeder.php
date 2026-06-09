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
                'description'=> ['fr' => 'Nous étudions vos plans, cahier des charges et contraintes techniques pour bien cadrer votre projet et identifier les risques potentiels dès le départ.', 'en' => 'We study your drawings, specifications and technical constraints to properly frame your project and identify potential risks from the outset.'],
            ],
            [
                'number'     => '02',
                'sort_order' => 2,
                'title'      => ['fr' => 'Étude technique',          'en' => 'Technical study'],
                'description'=> ['fr' => 'Analyse de faisabilité, choix des procédés adaptés (découpe laser, pliage CNC, soudure MIG/TIG), optimisation des gammes et estimation des délais.', 'en' => 'Feasibility analysis, selection of suitable processes (laser cutting, CNC bending, MIG/TIG welding), process optimisation and lead-time estimation.'],
            ],
            [
                'number'     => '03',
                'sort_order' => 3,
                'title'      => ['fr' => 'Sélection fournisseur',    'en' => 'Supplier selection'],
                'description'=> ['fr' => 'Identification et qualification des ateliers partenaires les mieux adaptés à vos exigences techniques et tarifaires.', 'en' => 'Identification and qualification of the partner workshops best suited to your technical and cost requirements.'],
            ],
            [
                'number'     => '04',
                'sort_order' => 4,
                'title'      => ['fr' => 'Prototype / pré-série',    'en' => 'Prototype / pre-production'],
                'description'=> ['fr' => 'Fabrication de pièces de validation pour confirmer la conformité dimensionnelle, les tolérances et les finitions avant lancement de la production série.', 'en' => 'Production of validation parts to confirm dimensional conformity, tolerances and finishes before launching series production.'],
            ],
            [
                'number'     => '05',
                'sort_order' => 5,
                'title'      => ['fr' => 'Production',               'en' => 'Production'],
                'description'=> ['fr' => 'Lancement de la fabrication série avec suivi de production continu, reporting régulier et gestion proactive des écarts éventuels.', 'en' => 'Launch of series production with continuous monitoring, regular reporting and proactive management of any deviations.'],
            ],
            [
                'number'     => '06',
                'sort_order' => 6,
                'title'      => ['fr' => 'Rapport d\'avancement de fabrication', 'en' => 'Manufacturing progress report'],
                'description'=> ['fr' => 'Décrivant l\'état d\'avancement des opérations de fabrication, les travaux réalisés, les activités en cours et les étapes restantes afin d\'assurer le respect du planning, de la qualité et des exigences du projet.', 'en' => 'Describing the progress of manufacturing operations, the work completed, the activities in progress and the remaining steps, to ensure compliance with the schedule, quality and project requirements.'],
            ],
            [
                'number'     => '07',
                'sort_order' => 7,
                'title'      => ['fr' => 'Rapport d\'inspection / Contrôle qualité', 'en' => 'Inspection report / Quality control'],
                'description'=> ['fr' => 'Inspections in-process et finales, conformité aux normes européennes avant expédition, avec dossier qualité complet et traçabilité matière.', 'en' => 'In-process and final inspections, compliance with European standards before shipping, with a complete quality file and material traceability.'],
            ],
            [
                'number'     => '08',
                'sort_order' => 8,
                'title'      => ['fr' => 'Livraison',                'en' => 'Delivery'],
                'description'=> ['fr' => 'Transport optimisé vers la France (3–4 jours par route), livraison sur site ou en entreposage selon vos besoins logistiques.', 'en' => 'Optimised transport to France (3–4 days by road), delivery on site or to storage according to your logistics needs.'],
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
