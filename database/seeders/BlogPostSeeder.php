<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'slug'        => 'pourquoi-externaliser-production-europe-est',
                'published_at'=> '2026-03-28 10:00:00',
                'tags_fr'     => ['sous-traitance', 'Europe de l\'Est', 'externalisation', 'métallerie'],
                'title_fr'    => 'Pourquoi externaliser sa production en Europe de l\'Est ?',
                'excerpt_fr'  => 'Face à la hausse des coûts en Europe occidentale, l\'Europe de l\'Est s\'impose comme une destination industrielle de premier choix. Découvrez pourquoi de plus en plus d\'entreprises françaises franchissent le pas.',
                'body_fr'     => '<h2>Une réponse concrète à la pression des coûts</h2><p>Depuis plusieurs années, les industriels français font face à une équation difficile : maintenir la qualité de leur production tout en maîtrisant des coûts qui ne cessent d\'augmenter. Salaires, charges sociales, énergie, matières premières — chaque poste pèse davantage sur les marges. L\'externalisation vers l\'Europe de l\'Est est apparue comme une réponse structurelle à cette pression.</p><h2>Des économies réelles, sans compromis sur la qualité</h2><p>En Bulgarie et en Roumanie, les coûts de main-d\'œuvre qualifiée en métallurgie sont 40 à 60 % inférieurs à ceux pratiqués en France ou en Allemagne. Ce différentiel se traduit directement sur le prix de revient des pièces et des ensembles métalliques. Pourtant, ces économies ne s\'accompagnent pas d\'une baisse de qualité : les ateliers partenaires travaillent selon les mêmes exigences européennes (conformité CE, traçabilité, qualité) que leurs homologues occidentaux.</p><h2>La proximité géographique, un avantage décisif</h2><p>Contrairement à la sous-traitance asiatique, l\'Europe de l\'Est offre une proximité géographique précieuse. Les délais de transport vers la France se comptent en jours, non en semaines. Cette accessibilité facilite les visites d\'ateliers, les contrôles qualité sur site et les ajustements rapides en cours de production.</p><h2>Un cadre réglementaire commun</h2><p>Les pays membres de l\'UE partagent le même cadre juridique et réglementaire : conformité CE, protection de la propriété intellectuelle, droit du travail harmonisé. Cette homogénéité réduit les risques contractuels et simplifie la gestion administrative des commandes internationales.</p><h2>Un accompagnement local fait la différence</h2><p>Le succès d\'une externalisation repose largement sur la qualité de l\'intermédiaire. Fab Sourcing sélectionne et qualifie les ateliers partenaires selon des critères stricts : capacité de production, respect des normes, stabilité financière, références clients. Nos ingénieurs assurent le suivi de chaque commande, de la consultation technique à la livraison.</p>',
            ],
            [
                'slug'        => 'bulgarie-vs-roumanie-sous-traitance-industrielle',
                'published_at'=> '2026-02-14 10:00:00',
                'tags_fr'     => ['Bulgarie', 'Roumanie', 'comparatif', 'sous-traitance industrielle'],
                'title_fr'    => 'Bulgarie vs Roumanie : quel pays choisir pour la sous-traitance industrielle ?',
                'excerpt_fr'  => 'Ces deux pays d\'Europe de l\'Est sont souvent mis en concurrence pour la sous-traitance industrielle. Coûts, compétences, logistique, stabilité : notre analyse point par point pour vous aider à choisir.',
                'body_fr'     => '<h2>Deux destinations complémentaires</h2><p>La Bulgarie et la Roumanie sont régulièrement citées côte à côte comme destinations privilégiées pour la sous-traitance industrielle en Europe. Toutes deux membres de l\'UE, elles partagent des atouts communs — coûts compétitifs, main-d\'œuvre qualifiée, standards européens — mais présentent des profils distincts selon les secteurs et les besoins.</p><h2>Coûts de production</h2><p>La Bulgarie affiche les coûts salariaux les plus bas de l\'UE. Le salaire minimum y est inférieur à celui pratiqué en Roumanie, ce qui se traduit par des prix de revient légèrement plus compétitifs pour les productions à forte intensité de main-d\'œuvre. La Roumanie, dont l\'économie a connu une croissance plus rapide, voit ses coûts progressivement se rapprocher de la moyenne européenne, tout en restant très inférieurs à la France ou à l\'Allemagne.</p><h2>Tissu industriel et compétences</h2><p>La Roumanie dispose d\'un tissu industriel plus dense, héritage de son importante base manufacturière soviétique reconvertie. Elle est particulièrement reconnue pour l\'automobile, la mécanique lourde et les équipements industriels. La Bulgarie, de son côté, excelle dans la métallerie fine, la chaudronnerie, les structures métalliques et les produits finis à haute valeur ajoutée.</p><h2>Logistique et accessibilité</h2><p>Les deux capitales sont bien connectées aux hubs européens. La Roumanie bénéficie d\'une façade maritime sur la mer Noire via le port de Constanța, utile pour certains flux. La Bulgarie offre également un accès à la Méditerranée via les ports de Varna et Bourgas.</p><h2>Notre recommandation</h2><p>Pour des productions de métallerie, structures métalliques, garde-corps ou verrières, la Bulgarie constitue notre recommandation principale. C\'est pourquoi Fab Sourcing a établi son réseau de partenaires industriels exclusivement en Bulgarie, fort d\'une expérience terrain approfondie.</p>',
            ],
            [
                'slug'        => 'reduire-couts-fabrication-metallerie',
                'published_at'=> '2025-12-18 10:00:00',
                'tags_fr'     => ['réduction des coûts', 'métallerie', 'fabrication', 'optimisation'],
                'title_fr'    => 'Réduire ses coûts de fabrication en métallerie',
                'excerpt_fr'  => 'La métallerie est un secteur où les coûts de production peuvent rapidement peser sur la compétitivité. Voici les leviers concrets pour les réduire sans sacrifier la qualité.',
                'body_fr'     => '<h2>Pourquoi la métallerie est-elle particulièrement concernée ?</h2><p>La métallerie — fabrication de structures métalliques, escaliers, garde-corps, menuiseries, bardages — est un secteur où la main-d\'œuvre représente une part importante du coût de revient. Coupage, soudure, finition, assemblage : chaque étape mobilise du personnel qualifié. Dans un contexte de tensions sur le marché du travail en Europe occidentale, ces coûts ont fortement progressé.</p><h2>Levier 1 : l\'externalisation partielle ou totale</h2><p>Externaliser tout ou partie de la production vers un partenaire en Europe de l\'Est permet de bénéficier d\'un différentiel de coût significatif sans changer de zone réglementaire. Les pièces ou ensembles sont fabriqués selon vos plans et spécifications, dans des ateliers certifiés, puis livrés prêts à poser.</p><h2>Levier 2 : la standardisation des plans</h2><p>Des plans bien documentés, avec des tolérances clairement définies, réduisent les allers-retours techniques et les non-conformités. Un dossier technique complet transmis au sous-traitant dès le départ permet d\'optimiser les temps de production et de minimiser les corrections coûteuses.</p><h2>Levier 3 : le regroupement des commandes</h2><p>Les coûts fixes de mise en fabrication (programmation des machines, réglages, logistique) se diluent avec le volume. Regrouper plusieurs projets en une seule commande, ou anticiper les besoins récurrents pour commander en série, permet de réduire sensiblement le coût unitaire.</p><h2>Levier 4 : le choix du bon partenaire</h2><p>Un sous-traitant fiable, doté d\'un parc machine adapté et d\'une expérience avérée dans votre type de production, livrera moins de rebuts, respectera mieux les délais et nécessitera moins de contrôles correctifs. La sélection rigoureuse du partenaire est elle-même un levier d\'économie à long terme.</p><p>Fab Sourcing accompagne ses clients dans l\'optimisation de leur chaîne de production depuis la Bulgarie, en combinant compétitivité des coûts et exigence qualité.</p>',
            ],
            [
                'slug'        => 'comment-choisir-sous-traitant-industriel-fiable',
                'published_at'=> '2025-10-22 10:00:00',
                'tags_fr'     => ['sous-traitant', 'critères de sélection', 'qualité', 'partenariat industriel'],
                'title_fr'    => 'Comment choisir un sous-traitant industriel fiable ?',
                'excerpt_fr'  => 'Choisir un sous-traitant industriel ne se résume pas à comparer des prix. Voici les critères essentiels pour identifier un partenaire de confiance, capable de tenir ses engagements dans la durée.',
                'body_fr'     => '<h2>Le prix n\'est pas le seul critère</h2><p>Lorsqu\'une entreprise cherche un sous-traitant industriel, le réflexe naturel est de comparer les devis. C\'est nécessaire, mais largement insuffisant. Un sous-traitant bon marché qui livre en retard, produit des non-conformités ou manque de réactivité coûte au final bien plus cher qu\'un partenaire sérieux et légèrement plus cher.</p><h2>Critère 1 : les certifications et accréditations</h2><p>Les certifications (ISO 9001, EN 1090 pour les structures métalliques, EN 3834 pour la soudure) attestent de la mise en place de processus qualité formalisés. Elles ne garantissent pas l\'excellence, mais constituent un minimum indispensable pour tout partenariat sérieux. Demandez toujours les certificats à jour.</p><h2>Critère 2 : les capacités techniques et le parc machine</h2><p>Votre sous-traitant doit disposer des équipements adaptés à vos pièces : découpe laser ou plasma, plieuse à commande numérique, postes de soudure MIG/TIG, cabine de peinture. Une visite d\'atelier, même virtuelle, permet d\'évaluer rapidement l\'adéquation entre votre besoin et leurs capacités réelles.</p><h2>Critère 3 : les références et l\'expérience sectorielle</h2><p>Demandez des références dans votre secteur d\'activité. Un sous-traitant habitué à fabriquer des garde-corps pour des chantiers de construction ne sera pas forcément adapté à des structures industrielles complexes, et vice-versa. L\'expérience sectorielle réduit la courbe d\'apprentissage et les risques de dérive.</p><h2>Critère 4 : la communication et la réactivité</h2><p>La qualité de la relation commerciale et technique est déterminante. Un bon sous-traitant répond rapidement aux demandes de devis, pose des questions pertinentes sur les spécifications, et communique proactivement en cas d\'aléa. La barrière de la langue peut être un obstacle : assurez-vous d\'avoir un interlocuteur technique francophone ou anglophone.</p><h2>Critère 5 : la stabilité financière</h2><p>Un sous-traitant en difficulté financière peut interrompre votre production sans préavis. Avant de placer une première commande importante, renseignez-vous sur la solidité de l\'entreprise : ancienneté, effectifs, clients de référence. Fab Sourcing réalise ces vérifications systématiquement pour chacun de ses partenaires.</p>',
            ],
        ];

        foreach ($posts as $data) {
            $post = BlogPost::firstOrNew(['slug' => $data['slug']]);
            $post->author_name         = 'Thierry Sudol';
            $post->published_at        = $data['published_at'];

            $post->setTranslation('title',   'fr', $data['title_fr']);
            $post->setTranslation('excerpt', 'fr', $data['excerpt_fr']);
            $post->setTranslation('body',    'fr', $data['body_fr']);
            $post->setTranslation('tags',    'fr', $data['tags_fr']);
            $post->setTranslation('tags',    'en', []);

            $post->reading_time_minutes = max(1, (int) ceil(
                str_word_count(strip_tags($data['body_fr'])) / 200
            ));

            $post->save();
        }
    }
}
