@extends('layouts.web')

@section('title', $lang === 'fr'
    ? 'Sous-traitance Bulgarie : le coût réel (TCO) — Fab Sourcing'
    : 'Subcontracting in Bulgaria: the real cost (TCO) — Fab Sourcing')

@section('description', $lang === 'fr'
    ? "Prix unitaire ≠ coût réel : la méthode TCO de Fab Sourcing pour comparer les offres de sous-traitance métallique en Bulgarie sur 7 postes de coût."
    : 'Unit price ≠ real cost: the Fab Sourcing TCO method for comparing metal subcontracting quotes in Bulgaria across 7 cost items.')

@push('seo')
<x-seo
  :title="$lang === 'fr'
    ? 'Sous-traitance Bulgarie : le coût réel (TCO) — Fab Sourcing'
    : 'Subcontracting in Bulgaria: the real cost (TCO) — Fab Sourcing'"
  :description="$lang === 'fr'
    ? 'Le prix unitaire ne suffit pas. Découvrez la méthode TCO de Fab Sourcing pour comparer objectivement les offres de sous-traitance métallique en Bulgarie.'
    : 'Unit price is not enough. Discover the Fab Sourcing TCO method for objectively comparing metal subcontracting quotes in Bulgaria.'"
  :canonical="request()->url()"
  :lang="$lang"
  :hreflang-fr="$langSwitcherUrls['fr']"
  :hreflang-en="$langSwitcherUrls['en'] ?? null"
  og-type="website"
  :og-image="asset('images/og-default.jpg')"
/>
@endpush

@section('content')

@if($lang === 'fr')
{{-- ═══════════════════════════════════════════════════
     FR — Article : Le coût total de possession (TCO)
══════════════════════════════════════════════════════ --}}
@push('head')
<style>
  .tco-article > p:first-of-type { font-size: 19px; color: #0f1e3d; }
  .tco-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; margin: 24px 0; border: 1px solid rgba(15,30,61,0.10); border-radius: 12px; }
  .tco-table { width: 100%; border-collapse: collapse; font-size: 14px; line-height: 1.5; }
  .tco-table th, .tco-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid rgba(15,30,61,0.08); vertical-align: top; }
  .tco-table th { font-family: var(--font-mono, ui-monospace, monospace); font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase; color: #6b7280; background: #f4f6f9; font-weight: 600; }
  .tco-table tbody tr:last-child td { border-bottom: none; }
  .tco-table tbody tr:hover { background: #f9fafb; }
  .tco-table td:first-child { color: #0f1e3d; font-weight: 500; }
  .tco-formula { font-family: var(--font-mono, ui-monospace, monospace); font-size: 14px; line-height: 1.6; background: #f4f6f9; border-left: 3px solid #2b62d9; border-radius: 0 12px 12px 0; padding: 16px 20px; margin: 20px 0; color: #0f1e3d; overflow-x: auto; }
  .tco-article h2[id] { scroll-margin-top: 100px; }
  .tco-next { background: #f4f6f9; border-radius: 16px; padding: 32px; margin-top: 28px; }
  .tco-next ol { padding-left: 20px; margin: 12px 0 0; }
  .tco-next li { margin-bottom: 8px; }
</style>
@endpush

{{-- Page hero --}}
<div class="page-hero">
  <div class="container">
    <div class="reveal" style="max-width:820px">
      <div class="breadcrumb">
        <a href="{{ route('home', $lang) }}">Accueil</a>
        <span>/</span>
        <span>Pourquoi l'Est</span>
      </div>
      <h1 class="h-1">
        Les coûts de la sous-traitance en Bulgarie : <em>pourquoi le prix unitaire ne suffit pas</em> à comparer les offres.
      </h1>
      <div style="margin-top:28px">
        <a href="#conclusion" class="btn btn-primary">
          Voir Conclusion <span class="arrow">→</span>
        </a>
      </div>
    </div>
  </div>
</div>

{{-- Article body --}}
<section class="section">
  <div class="container">
    <div class="article-layout">
      <div class="article-main article-body tco-article">

        <p>Vous avez reçu trois devis pour la fabrication de vos châssis métalliques. Le premier affiche 12 € pièce, le deuxième 15 €, le troisième 18 €. Le choix semble évident : le moins cher, naturellement.</p>

        <p>Erreur. Dans l'externalisation industrielle, le prix unitaire est le piège le plus coûteux. Un devis 20 % moins cher peut, à l'usage, coûter 30 % plus cher que l'offre initialement la plus élevée. La raison ? La majorité des industriels compare les offres sur un critère unique — le prix de fabrication — alors que le vrai coût d'une externalisation s'étend bien au-delà.</p>

        <p>Chez Fab Sourcing, nous avons accompagné plus de 50 industriels français dans leur démarche d'externalisation. Voici notre méthode de calcul du Coût Total de Possession (TCO) pour choisir un sous-traitant métallique en toute rationalité.</p>

        <h2>1. Le piège du prix unitaire : une histoire vraie</h2>

        <p><strong>Le contexte.</strong> Un équipementier agroalimentaire de la région lyonnaise cherchait à externaliser la fabrication de 2 000 bacs de rétention acier par an. Trois offres sur la table :</p>

        <div class="tco-table-wrap">
          <table class="tco-table">
            <thead>
              <tr><th>Offre</th><th>Prix unitaire</th><th>Localisation</th><th>Délai annoncé</th></tr>
            </thead>
            <tbody>
              <tr><td>Offre A</td><td>145 €</td><td>Turquie (nouveau contact)</td><td>4 semaines</td></tr>
              <tr><td>Offre B</td><td>168 €</td><td>Bulgarie (via Fab Sourcing)</td><td>3 semaines</td></tr>
              <tr><td>Offre C</td><td>152 €</td><td>Portugal (contact direct)</td><td>5 semaines</td></tr>
            </tbody>
          </table>
        </div>

        <p>L'industriel a d'abord penché pour l'Offre A, la moins chère. Nous l'avons accompagné dans une analyse TCO complète. Voici ce qui est apparu :</p>

        <p><strong>Coûts cachés de l'Offre A (contact direct) :</strong></p>
        <ul>
          <li>Transport : 18 €/pièce (groupage irrégulier, pas de relation contractuelle de volume)</li>
          <li>Douane : 0 € (UE), mais formalités administratives complexes (facturation Turquie, TVA intracommunautaire mal maîtrisée)</li>
          <li>Stocks de sécurité : nécessité de stocker 500 pièces en France (coût de possession : 8 €/pièce/an)</li>
          <li>Non-conformités : 8 % de taux de rebut sur les deux premières livraisons (coût : 12 €/pièce rebutée + retard de production)</li>
          <li>Coordination : 15 heures/mois de suivi par l'acheteur (coût chargé : 45 €/heure)</li>
          <li>Déplacements d'audit : 2 voyages/an (1 200 €/voyage)</li>
        </ul>

        <p><strong>TCO réel Offre A :</strong> 145 + 18 + 8 + (8% × 12) + (15h × 45€ / 167 pièces/mois) + (2 400€ / 2 000 pièces) = <strong>189 €/pièce</strong></p>

        <p><strong>TCO réel Offre B (via Fab Sourcing) :</strong> 168 + 12 (transport optimisé) + 2 (stock minimal) + 1% NC + 2h coordination + 0 déplacement = <strong>184 €/pièce</strong></p>

        <p><strong>Résultat :</strong> l'Offre B, initialement 16 % plus chère au prix unitaire, s'avérait 3 % moins chère en TCO. Et avec une qualité constante, des délais tenus et un interlocuteur francophone.</p>

        <p>L'industriel a choisi l'Offre B. Deux ans plus tard, son taux de conformité est à 99,2 % et il a externalisé 60 % de sa production métallique avec nous.</p>

        <h2>2. Les 7 postes de coût du TCO en externalisation métallique</h2>

        <p>Pour comparer objectivement deux offres de sous-traitance, intégrez ces 7 postes :</p>

        <h3>Poste 1 — Prix de fabrication (PF)</h3>
        <p><strong>Ce que c'est :</strong> Le coût direct de la pièce FOB atelier (matière + découpe + pliage + soudure + finition).</p>
        <p><strong>Ce qu'il faut vérifier :</strong></p>
        <ul>
          <li>La matière est-elle incluse ? À quel cours (LME du jour ou cours moyen) ?</li>
          <li>Les chutes de découpe sont-elles optimisées ou facturées en surplus ?</li>
          <li>Les finitions sont-elles détaillées (type de poudre, épaisseur, couleur RAL) ?</li>
        </ul>
        <p><strong>Piège classique :</strong> Un devis à 12 € qui devient 14 € après ajustement des finitions « non comprises initialement ».</p>

        <h3>Poste 2 — Transport et logistique (T)</h3>
        <p><strong>Ce que c'est :</strong> Du porte de l'atelier sous-traitant à votre site de production.</p>
        <p><strong>Composantes :</strong></p>
        <ul>
          <li>Transport routier (Europe de l'Est : 3-7 jours, 0,08-0,12 €/tonne/km)</li>
          <li>Assurance transport</li>
          <li>Dégroupage / groupage</li>
          <li>Manutention déchargement</li>
        </ul>
        <p><strong>Piège classique :</strong> Le sous-traitant indique « transport à étudier ». Vous découvrez ensuite que le groupage n'existe pas pour ce volume, et que le dédié coûte 40 % plus cher.</p>
        <p><strong>Avantage Fab Sourcing :</strong> Transport optimisé par consolidation de volumes avec d'autres clients français. Tarif négocié et maîtrisé dès le devis.</p>

        <h3>Poste 3 — Stocks et immobilisation (S)</h3>
        <p><strong>Ce que c'est :</strong> Le coût de possession des stocks nécessaires pour compenser les aléas de livraison.</p>
        <p><strong>Formule simplifiée :</strong></p>
        <div class="tco-formula">Coût stock = (Volume moyen stocké × Prix unitaire × Taux de possession) / Nombre de pièces annuelles</div>
        <p>Taux de possession annuel : 15-25 % (immobilisation financière, magasinage, assurance, obsolescence, détérioration).</p>
        <p><strong>Exemple concret :</strong></p>
        <ul>
          <li>Volume annuel : 2 000 pièces</li>
          <li>Stock de sécurité nécessaire : 300 pièces (15 jours de couverture face à des délais incertains)</li>
          <li>Prix unitaire : 150 €</li>
          <li>Taux de possession : 20 %/an</li>
        </ul>
        <div class="tco-formula">Coût stock = (300 × 150 × 0,20) / 2 000 = 4,50 €/pièce</div>
        <p>Avec un sous-traitant fiable et des délais courts (3-7 jours), le stock de sécurité tombe à 50 pièces :</p>
        <div class="tco-formula">Coût stock = (50 × 150 × 0,20) / 2 000 = 0,75 €/pièce</div>
        <p><strong>Économie réalisée :</strong> 3,75 €/pièce, soit 7 500 €/an, rien que sur ce poste.</p>

        <h3>Poste 4 — Qualité et non-conformités (Q)</h3>
        <p><strong>Ce que c'est :</strong> Le coût des pièces défectueuses, des retours, des retouches et des retards de production induits.</p>
        <p><strong>Composantes :</strong></p>
        <ul>
          <li>Taux de rebut (pièces irrécupérables)</li>
          <li>Coût de retouche (main-d'œuvre interne ou externe)</li>
          <li>Coût de retard (pénalités client, heures supplémentaires pour rattraper le retard)</li>
          <li>Coût de réputation (perte de confiance du client final)</li>
        </ul>
        <p><strong>Piège classique :</strong> Un sous-traitant à 145 € avec 5 % de NC vs un sous-traitant à 168 € avec 0,5 % de NC.</p>
        <div class="tco-formula">Coût NC Offre A = 5% × (145 + 30 € de retard/production) = 8,75 €/pièce<br>Coût NC Offre B = 0,5% × (168 + 5 € de gestion) = 0,87 €/pièce</div>
        <p><strong>Différence :</strong> 7,88 €/pièce au profit de l'offre « plus chère ».</p>

        <h3>Poste 5 — Coordination et gestion de projet (C)</h3>
        <p><strong>Ce que c'est :</strong> Le temps interne consacré au suivi de la sous-traitance.</p>
        <p><strong>Composantes :</strong></p>
        <ul>
          <li>Échanges techniques (plans, modifications, ajustements)</li>
          <li>Suivi de production et relances</li>
          <li>Gestion des litiges et non-conformités</li>
          <li>Déplacements d'audit (si nécessaire)</li>
        </ul>
        <p>Coût horaire chargé d'un acheteur / responsable qualité : 45-65 €/heure.</p>
        <p><strong>Exemple :</strong></p>
        <ul>
          <li>Sous-traitant direct : 12 heures/mois de coordination</li>
          <li>Sous-traitant via Fab Sourcing : 2 heures/mois (un seul interlocuteur francophone gère tout)</li>
        </ul>
        <div class="tco-formula">Coût coordination A = (12h × 50 €) / 167 pièces = 3,60 €/pièce<br>Coût coordination B = (2h × 50 €) / 167 pièces = 0,60 €/pièce</div>
        <p><strong>Économie :</strong> 3 €/pièce, soit 6 000 €/an.</p>

        <h3>Poste 6 — Outils et amortissement de prototypage (O)</h3>
        <p><strong>Ce que c'est :</strong> Les coûts non récurrents liés au lancement d'une nouvelle production.</p>
        <p><strong>Composantes :</strong></p>
        <ul>
          <li>Programmation CN et réglages machine</li>
          <li>Fabrication et validation du prototype</li>
          <li>Outils spécifiques (gabarits, moules, matrices)</li>
          <li>Amortissement sur les premières séries</li>
        </ul>
        <p><strong>Piège classique :</strong> Le devis indique « programmation incluse » mais limite à 500 pièces. Au-delà, un supplément est facturé.</p>

        <h3>Poste 7 — Risques et opportunités (R)</h3>
        <p><strong>Ce que c'est :</strong> Les coûts probabilistes liés aux risques de la relation sous-traitance.</p>
        <p><strong>Risques à quantifier :</strong></p>
        <ul>
          <li>Rupture de supply chain : dépendance à un seul fournisseur lointain</li>
          <li>Fluctuation de change : si facturation en devise locale non couverte</li>
          <li>Évolution réglementaire : nouvelles normes environnementales, taxe carbone aux frontières</li>
          <li>Perte de savoir-faire interne : désindustrialisation progressive des compétences</li>
        </ul>
        <p><strong>Opportunités à valoriser :</strong></p>
        <ul>
          <li>Flexibilité : capacité d'absorber des pics sans investissement</li>
          <li>Innovation : accès aux technologies du sous-traitant sans capital immobilisé</li>
          <li>Concentration stratégique : recentrage sur le cœur de métier</li>
        </ul>

        <h2>3. La formule TCO complète</h2>
        <div class="tco-formula">TCO = PF + T + S + Q + C + O + R</div>
        <p>Pour une comparaison rigoureuse, calculez le TCO sur 3 ans avec les hypothèses suivantes :</p>
        <ul>
          <li>Volume annuel stable ou croissant</li>
          <li>Taux d'inflation matière : 3-5 %/an</li>
          <li>Taux d'actualisation : 5 %/an</li>
        </ul>
        <p><strong>Exemple de tableau comparatif :</strong></p>

        <div class="tco-table-wrap">
          <table class="tco-table">
            <thead>
              <tr><th>Poste de coût</th><th>Offre A Turquie (direct)</th><th>Offre B (Bulgarie via Fab Sourcing)</th><th>Offre C (Portugal)</th></tr>
            </thead>
            <tbody>
              <tr><td>Prix fabrication (PF)</td><td>145 €</td><td>168 €</td><td>220 €</td></tr>
              <tr><td>Transport (T)</td><td>18 €</td><td>12 €</td><td>5 €</td></tr>
              <tr><td>Stock (S)</td><td>8 €</td><td>2 €</td><td>1 €</td></tr>
              <tr><td>Non-conformités (Q)</td><td>8 €</td><td>1 €</td><td>0,5 €</td></tr>
              <tr><td>Coordination (C)</td><td>4 €</td><td>1 €</td><td>0,5 €</td></tr>
              <tr><td>Prototypage (O)</td><td>3 €</td><td>2 €</td><td>1 €</td></tr>
              <tr><td>Risques (R)</td><td>5 €</td><td>2 €</td><td>0 €</td></tr>
              <tr><td>TCO 1ère année</td><td>191 €</td><td>188 €</td><td>228 €</td></tr>
              <tr><td>TCO 2ème année</td><td>196 €</td><td>190 €</td><td>234 €</td></tr>
              <tr><td>TCO 3ème année</td><td>201 €</td><td>193 €</td><td>240 €</td></tr>
            </tbody>
          </table>
        </div>

        <p><strong>Enseignement :</strong> L'Offre B devient la plus compétitive dès la deuxième année, et l'écart se creuse grâce à l'amélioration continue des processus et la réduction des stocks de sécurité.</p>

        <h2>4. Les 5 questions à poser à votre sous-traitant avant de signer</h2>
        <p>Pour éviter les mauvaises surprises, exigez des réponses écrites à ces questions :</p>
        <ol>
          <li><strong>« Quel est votre taux de non-conformité moyen sur les 12 derniers mois, et comment le mesurez-vous ? »</strong><br>Un sous-traitant sérieux connaît ce chiffre et le traque. Méfiance si la réponse est vague (« très faible »).</li>
          <li><strong>« Quel est votre délai de livraison réel moyen vs. le délai annoncé ? »</strong><br>Demandez le taux de respect des délais (objectif : &gt; 95 %). Un sous-traitant qui annonce 3 semaines mais livre en 5 semaines 30 % du temps génère des coûts de stock énormes.</li>
          <li><strong>« Quels sont les coûts non inclus dans votre devis ? »</strong><br>Transport, emballage spécifique, outillage, modifications de plans en cours de production, pénalités de retard.</li>
          <li><strong>« Comment gérez-vous la traçabilité des matières premières ? »</strong><br>Mill-test, certificats d'origine, traçabilité lot par lot. Indispensable pour les industries réglementées (agroalimentaire, nucléaire, aéronautique).</li>
          <li><strong>« Quel est votre plan de continuité en cas de problème majeur ? »</strong><br>Pannes machine, départs en masse, crises sanitaires. Un sous-traitant unique sans plan B représente un risque supply chain majeur.</li>
        </ol>

        <h2>5. Pourquoi Fab Sourcing optimise votre TCO</h2>
        <p>Notre modèle d'intermédiation industrielle ne se contente pas de trouver un atelier bon marché. Nous structurons l'ensemble de la relation pour réduire vos coûts cachés :</p>

        <div class="tco-table-wrap">
          <table class="tco-table">
            <thead>
              <tr><th>Votre problème</th><th>Notre solution</th><th>Impact TCO</th></tr>
            </thead>
            <tbody>
              <tr><td>Transport coûteux et imprévisible</td><td>Consolidation de volumes, tarifs négociés</td><td>-20 à -30 % sur le poste transport</td></tr>
              <tr><td>Stocks de sécurité excessifs</td><td>Délais courts (3-7 jours) et fiabilité</td><td>-70 % sur le poste stock</td></tr>
              <tr><td>Non-conformités et retards</td><td>Audit qualité préalable, suivi continu</td><td>-80 % sur le poste qualité</td></tr>
              <tr><td>Coordination chronophage</td><td>Interlocuteur unique francophone</td><td>-85 % sur le poste coordination</td></tr>
              <tr><td>Risque supply chain</td><td>Réseau multi-ateliers, plan de continuité</td><td>Risque probabilisé réduit de 60 %</td></tr>
            </tbody>
          </table>
        </div>

        <h2 id="conclusion">Conclusion</h2>
        <p>Le prix unitaire est une donnée. Le TCO est la réalité. En externalisation industrielle, choisir sur le prix seul, c'est comme choisir un fournisseur d'électricité uniquement sur le prix du kWh sans regarder la fiabilité du réseau, la qualité du service client et les coûts de coupure.</p>
        <p>La bonne question n'est pas « Combien coûte la pièce ? » mais « Combien me coûte, au total et sur la durée, cette relation de sous-traitance ? »</p>
        <p>Chez Fab Sourcing, nous construisons des partenariats industriels sur la base d'un TCO transparent et optimisé. Nos devis détaillent chaque poste de coût, nos contrats intègrent des indicateurs de performance (KPI) mesurables, et notre méthode garantit une amélioration continue du TCO année après année.</p>

      </div>
    </div>
  </div>
</section>

{{-- Post-article CTA + next steps --}}
<section class="section" style="padding-top:0">
  <div class="container">
    <div class="article-layout">
      <div class="article-main">
        <a href="{{ route('contact', $lang) }}" class="btn btn-primary" style="font-size:16px; padding:16px 26px">
          Demander une analyse TCO <span class="arrow">→</span>
        </a>
        <div class="tco-next">
          <h3 style="margin:0 0 4px; font-size:20px; font-weight:700; color:#0f1e3d">Prochaines étapes :</h3>
          <ol>
            <li>Je vous envoie un accusé de réception sous 1 heure</li>
            <li>Notre bureau d'études analyse votre besoin sous 48h</li>
            <li>Vous recevez un devis détaillé avec planning prévisionnel</li>
          </ol>
          <p style="margin:16px 0 0; color:#4a5568; line-height:1.6">
            Pour accélérer le processus, vous pouvez m'envoyer vos plans directement :
            <a href="mailto:tsudol.fabtec@yahoo.com">tsudol.fabtec@yahoo.com</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

@else
{{-- ═══════════════════════════════════════════════════
     EN — Article: Total Cost of Ownership (TCO)
══════════════════════════════════════════════════════ --}}
@push('head')
<style>
  .tco-article > p:first-of-type { font-size: 19px; color: #0f1e3d; }
  .tco-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; margin: 24px 0; border: 1px solid rgba(15,30,61,0.10); border-radius: 12px; }
  .tco-table { width: 100%; border-collapse: collapse; font-size: 14px; line-height: 1.5; }
  .tco-table th, .tco-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid rgba(15,30,61,0.08); vertical-align: top; }
  .tco-table th { font-family: var(--font-mono, ui-monospace, monospace); font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase; color: #6b7280; background: #f4f6f9; font-weight: 600; }
  .tco-table tbody tr:last-child td { border-bottom: none; }
  .tco-table tbody tr:hover { background: #f9fafb; }
  .tco-table td:first-child { color: #0f1e3d; font-weight: 500; }
  .tco-formula { font-family: var(--font-mono, ui-monospace, monospace); font-size: 14px; line-height: 1.6; background: #f4f6f9; border-left: 3px solid #2b62d9; border-radius: 0 12px 12px 0; padding: 16px 20px; margin: 20px 0; color: #0f1e3d; overflow-x: auto; }
  .tco-article h2[id] { scroll-margin-top: 100px; }
  .tco-next { background: #f4f6f9; border-radius: 16px; padding: 32px; margin-top: 28px; }
  .tco-next ol { padding-left: 20px; margin: 12px 0 0; }
  .tco-next li { margin-bottom: 8px; }
</style>
@endpush

{{-- Page hero --}}
<div class="page-hero">
  <div class="container">
    <div class="reveal" style="max-width:820px">
      <div class="breadcrumb">
        <a href="{{ route('home', $lang) }}">Home</a>
        <span>/</span>
        <span>Why East EU</span>
      </div>
      <h1 class="h-1">
        The cost of subcontracting in Bulgaria: <em>why unit price alone isn't enough</em> to compare quotes.
      </h1>
      <div style="margin-top:28px">
        <a href="#conclusion" class="btn btn-primary">
          See conclusion <span class="arrow">→</span>
        </a>
      </div>
    </div>
  </div>
</div>

{{-- Article body --}}
<section class="section">
  <div class="container">
    <div class="article-layout">
      <div class="article-main article-body tco-article">

        <p>You have received three quotes to manufacture your metal frames. The first comes in at €12 per unit, the second at €15, the third at €18. The choice seems obvious: the cheapest, naturally.</p>

        <p>Wrong. In industrial outsourcing, unit price is the most expensive trap of all. A quote 20% cheaper can, in practice, cost 30% more than the option that initially looked the most expensive. Why? Most manufacturers compare offers on a single criterion — the manufacturing price — when the true cost of outsourcing extends far beyond that.</p>

        <p>At Fab Sourcing, we have supported more than 50 French manufacturers in their outsourcing projects. Here is our method for calculating the Total Cost of Ownership (TCO) so you can choose a metal subcontractor with complete clarity.</p>

        <h2>1. The unit-price trap: a true story</h2>

        <p><strong>The context.</strong> A food-industry equipment manufacturer in the Lyon region was looking to outsource the production of 2,000 steel retention basins per year. Three offers were on the table:</p>

        <div class="tco-table-wrap">
          <table class="tco-table">
            <thead>
              <tr><th>Offer</th><th>Unit price</th><th>Location</th><th>Quoted lead time</th></tr>
            </thead>
            <tbody>
              <tr><td>Offer A</td><td>€145</td><td>Turkey (new contact)</td><td>4 weeks</td></tr>
              <tr><td>Offer B</td><td>€168</td><td>Bulgaria (via Fab Sourcing)</td><td>3 weeks</td></tr>
              <tr><td>Offer C</td><td>€152</td><td>Portugal (direct contact)</td><td>5 weeks</td></tr>
            </tbody>
          </table>
        </div>

        <p>The manufacturer initially leaned towards Offer A, the cheapest. We guided them through a full TCO analysis. Here is what emerged:</p>

        <p><strong>Hidden costs of Offer A (direct contact):</strong></p>
        <ul>
          <li>Transport: €18/unit (irregular groupage, no contractual volume relationship)</li>
          <li>Customs: €0 (EU), but complex administrative formalities (Turkish invoicing, poorly managed intra-EU VAT)</li>
          <li>Safety stock: the need to hold 500 units in France (carrying cost: €8/unit/year)</li>
          <li>Non-conformities: 8% reject rate on the first two deliveries (cost: €12/rejected unit + production delay)</li>
          <li>Coordination: 15 hours/month of follow-up by the buyer (loaded cost: €45/hour)</li>
          <li>Audit travel: 2 trips/year (€1,200/trip)</li>
        </ul>

        <p><strong>Real TCO of Offer A:</strong> 145 + 18 + 8 + (8% × 12) + (15h × €45 / 167 units/month) + (€2,400 / 2,000 units) = <strong>€189/unit</strong></p>

        <p><strong>Real TCO of Offer B (via Fab Sourcing):</strong> 168 + 12 (optimised transport) + 2 (minimal stock) + 1% NC + 2h coordination + 0 travel = <strong>€184/unit</strong></p>

        <p><strong>Result:</strong> Offer B, initially 16% more expensive on unit price, turned out to be 3% cheaper on TCO. And with consistent quality, lead times met and a French-speaking contact.</p>

        <p>The manufacturer chose Offer B. Two years later, their conformity rate stands at 99.2% and they have outsourced 60% of their metal production to us.</p>

        <h2>2. The 7 cost items of TCO in metal outsourcing</h2>

        <p>To compare two subcontracting offers objectively, factor in these 7 items:</p>

        <h3>Item 1 — Manufacturing price (MP)</h3>
        <p><strong>What it is:</strong> The direct ex-works cost of the part (material + cutting + bending + welding + finishing).</p>
        <p><strong>What to check:</strong></p>
        <ul>
          <li>Is the material included? At what price (daily LME or average rate)?</li>
          <li>Are cutting offcuts optimised or charged as an extra?</li>
          <li>Are the finishes detailed (powder type, thickness, RAL colour)?</li>
        </ul>
        <p><strong>Classic trap:</strong> A €12 quote that becomes €14 once finishes "not initially included" are added.</p>

        <h3>Item 2 — Transport and logistics (T)</h3>
        <p><strong>What it is:</strong> From the subcontractor's workshop door to your production site.</p>
        <p><strong>Components:</strong></p>
        <ul>
          <li>Road transport (Eastern Europe: 3-7 days, €0.08-0.12/tonne/km)</li>
          <li>Transport insurance</li>
          <li>De-grouping / groupage</li>
          <li>Unloading and handling</li>
        </ul>
        <p><strong>Classic trap:</strong> The subcontractor states "transport to be assessed". You then discover that groupage does not exist for this volume, and that a dedicated truck costs 40% more.</p>
        <p><strong>Fab Sourcing advantage:</strong> Transport optimised by consolidating volumes with other French clients. Negotiated, controlled pricing from the quote stage.</p>

        <h3>Item 3 — Stock and tied-up capital (S)</h3>
        <p><strong>What it is:</strong> The cost of holding the stock needed to offset delivery uncertainties.</p>
        <p><strong>Simplified formula:</strong></p>
        <div class="tco-formula">Stock cost = (Average stock held × Unit price × Carrying rate) / Annual number of units</div>
        <p>Annual carrying rate: 15-25% (tied-up capital, warehousing, insurance, obsolescence, deterioration).</p>
        <p><strong>Concrete example:</strong></p>
        <ul>
          <li>Annual volume: 2,000 units</li>
          <li>Safety stock required: 300 units (15 days' cover against uncertain lead times)</li>
          <li>Unit price: €150</li>
          <li>Carrying rate: 20%/year</li>
        </ul>
        <div class="tco-formula">Stock cost = (300 × 150 × 0.20) / 2,000 = €4.50/unit</div>
        <p>With a reliable subcontractor and short lead times (3-7 days), safety stock drops to 50 units:</p>
        <div class="tco-formula">Stock cost = (50 × 150 × 0.20) / 2,000 = €0.75/unit</div>
        <p><strong>Saving achieved:</strong> €3.75/unit, i.e. €7,500/year, on this item alone.</p>

        <h3>Item 4 — Quality and non-conformities (Q)</h3>
        <p><strong>What it is:</strong> The cost of defective parts, returns, rework and the resulting production delays.</p>
        <p><strong>Components:</strong></p>
        <ul>
          <li>Reject rate (unrecoverable parts)</li>
          <li>Rework cost (internal or external labour)</li>
          <li>Delay cost (client penalties, overtime to catch up)</li>
          <li>Reputation cost (loss of trust from the end client)</li>
        </ul>
        <p><strong>Classic trap:</strong> A subcontractor at €145 with 5% NC vs a subcontractor at €168 with 0.5% NC.</p>
        <div class="tco-formula">NC cost Offer A = 5% × (145 + €30 delay/production) = €8.75/unit<br>NC cost Offer B = 0.5% × (168 + €5 management) = €0.87/unit</div>
        <p><strong>Difference:</strong> €7.88/unit in favour of the "more expensive" offer.</p>

        <h3>Item 5 — Coordination and project management (C)</h3>
        <p><strong>What it is:</strong> The internal time spent managing the subcontracting relationship.</p>
        <p><strong>Components:</strong></p>
        <ul>
          <li>Technical exchanges (drawings, changes, adjustments)</li>
          <li>Production follow-up and chasing</li>
          <li>Handling disputes and non-conformities</li>
          <li>Audit travel (where necessary)</li>
        </ul>
        <p>Loaded hourly cost of a buyer / quality manager: €45-65/hour.</p>
        <p><strong>Example:</strong></p>
        <ul>
          <li>Direct subcontractor: 12 hours/month of coordination</li>
          <li>Subcontractor via Fab Sourcing: 2 hours/month (a single French-speaking contact handles everything)</li>
        </ul>
        <div class="tco-formula">Coordination cost A = (12h × €50) / 167 units = €3.60/unit<br>Coordination cost B = (2h × €50) / 167 units = €0.60/unit</div>
        <p><strong>Saving:</strong> €3/unit, i.e. €6,000/year.</p>

        <h3>Item 6 — Tooling and prototyping amortisation (O)</h3>
        <p><strong>What it is:</strong> The non-recurring costs of launching a new production run.</p>
        <p><strong>Components:</strong></p>
        <ul>
          <li>CNC programming and machine set-up</li>
          <li>Prototype manufacturing and validation</li>
          <li>Specific tooling (jigs, moulds, dies)</li>
          <li>Amortisation over the first production runs</li>
        </ul>
        <p><strong>Classic trap:</strong> The quote states "programming included" but caps it at 500 units. Beyond that, a surcharge applies.</p>

        <h3>Item 7 — Risks and opportunities (R)</h3>
        <p><strong>What it is:</strong> The probabilistic costs linked to the risks of the subcontracting relationship.</p>
        <p><strong>Risks to quantify:</strong></p>
        <ul>
          <li>Supply-chain disruption: dependence on a single distant supplier</li>
          <li>Exchange-rate fluctuation: if invoiced in an uncovered local currency</li>
          <li>Regulatory change: new environmental standards, carbon border tax</li>
          <li>Loss of internal know-how: gradual de-skilling of your workforce</li>
        </ul>
        <p><strong>Opportunities to value:</strong></p>
        <ul>
          <li>Flexibility: the ability to absorb peaks with no investment</li>
          <li>Innovation: access to the subcontractor's technology with no tied-up capital</li>
          <li>Strategic focus: refocusing on your core business</li>
        </ul>

        <h2>3. The complete TCO formula</h2>
        <div class="tco-formula">TCO = MP + T + S + Q + C + O + R</div>
        <p>For a rigorous comparison, calculate the TCO over 3 years with the following assumptions:</p>
        <ul>
          <li>Stable or growing annual volume</li>
          <li>Material inflation rate: 3-5%/year</li>
          <li>Discount rate: 5%/year</li>
        </ul>
        <p><strong>Example comparison table:</strong></p>

        <div class="tco-table-wrap">
          <table class="tco-table">
            <thead>
              <tr><th>Cost item</th><th>Offer A Turkey (direct)</th><th>Offer B (Bulgaria via Fab Sourcing)</th><th>Offer C (Portugal)</th></tr>
            </thead>
            <tbody>
              <tr><td>Manufacturing price (MP)</td><td>€145</td><td>€168</td><td>€220</td></tr>
              <tr><td>Transport (T)</td><td>€18</td><td>€12</td><td>€5</td></tr>
              <tr><td>Stock (S)</td><td>€8</td><td>€2</td><td>€1</td></tr>
              <tr><td>Non-conformities (Q)</td><td>€8</td><td>€1</td><td>€0.5</td></tr>
              <tr><td>Coordination (C)</td><td>€4</td><td>€1</td><td>€0.5</td></tr>
              <tr><td>Prototyping (O)</td><td>€3</td><td>€2</td><td>€1</td></tr>
              <tr><td>Risks (R)</td><td>€5</td><td>€2</td><td>€0</td></tr>
              <tr><td>TCO year 1</td><td>€191</td><td>€188</td><td>€228</td></tr>
              <tr><td>TCO year 2</td><td>€196</td><td>€190</td><td>€234</td></tr>
              <tr><td>TCO year 3</td><td>€201</td><td>€193</td><td>€240</td></tr>
            </tbody>
          </table>
        </div>

        <p><strong>Takeaway:</strong> Offer B becomes the most competitive from the second year onwards, and the gap widens thanks to continuous process improvement and reduced safety stock.</p>

        <h2>4. The 5 questions to ask your subcontractor before signing</h2>
        <p>To avoid nasty surprises, insist on written answers to these questions:</p>
        <ol>
          <li><strong>"What is your average non-conformity rate over the last 12 months, and how do you measure it?"</strong><br>A serious subcontractor knows this figure and tracks it. Be wary if the answer is vague ("very low").</li>
          <li><strong>"What is your average actual lead time vs. the quoted lead time?"</strong><br>Ask for the on-time delivery rate (target: &gt; 95%). A subcontractor who quotes 3 weeks but delivers in 5 weeks 30% of the time generates enormous stock costs.</li>
          <li><strong>"What costs are not included in your quote?"</strong><br>Transport, specific packaging, tooling, drawing changes during production, late penalties.</li>
          <li><strong>"How do you manage raw-material traceability?"</strong><br>Mill-test, certificates of origin, batch-by-batch traceability. Essential for regulated industries (food, nuclear, aerospace).</li>
          <li><strong>"What is your continuity plan in the event of a major problem?"</strong><br>Machine breakdowns, mass departures, health crises. A sole subcontractor with no plan B represents a major supply-chain risk.</li>
        </ol>

        <h2>5. Why Fab Sourcing optimises your TCO</h2>
        <p>Our industrial intermediation model does not simply find a cheap workshop. We structure the entire relationship to reduce your hidden costs:</p>

        <div class="tco-table-wrap">
          <table class="tco-table">
            <thead>
              <tr><th>Your problem</th><th>Our solution</th><th>TCO impact</th></tr>
            </thead>
            <tbody>
              <tr><td>Expensive, unpredictable transport</td><td>Volume consolidation, negotiated rates</td><td>-20 to -30% on the transport item</td></tr>
              <tr><td>Excessive safety stock</td><td>Short lead times (3-7 days) and reliability</td><td>-70% on the stock item</td></tr>
              <tr><td>Non-conformities and delays</td><td>Prior quality audit, continuous monitoring</td><td>-80% on the quality item</td></tr>
              <tr><td>Time-consuming coordination</td><td>A single French-speaking contact</td><td>-85% on the coordination item</td></tr>
              <tr><td>Supply-chain risk</td><td>Multi-workshop network, continuity plan</td><td>Probabilistic risk reduced by 60%</td></tr>
            </tbody>
          </table>
        </div>

        <h2 id="conclusion">Conclusion</h2>
        <p>Unit price is a data point. TCO is the reality. In industrial outsourcing, choosing on price alone is like choosing an electricity supplier solely on the price per kWh without looking at grid reliability, customer-service quality and outage costs.</p>
        <p>The right question is not "How much does the part cost?" but "How much does this subcontracting relationship cost me, in total and over time?"</p>
        <p>At Fab Sourcing, we build industrial partnerships on the basis of a transparent, optimised TCO. Our quotes detail every cost item, our contracts include measurable performance indicators (KPIs), and our method guarantees continuous TCO improvement year after year.</p>

      </div>
    </div>
  </div>
</section>

{{-- Post-article CTA + next steps --}}
<section class="section" style="padding-top:0">
  <div class="container">
    <div class="article-layout">
      <div class="article-main">
        <a href="{{ route('contact', $lang) }}" class="btn btn-primary" style="font-size:16px; padding:16px 26px">
          Request a TCO analysis <span class="arrow">→</span>
        </a>
        <div class="tco-next">
          <h3 style="margin:0 0 4px; font-size:20px; font-weight:700; color:#0f1e3d">Next steps:</h3>
          <ol>
            <li>I send you an acknowledgement of receipt within 1 hour</li>
            <li>Our engineering office analyses your need within 48h</li>
            <li>You receive a detailed quote with a provisional schedule</li>
          </ol>
          <p style="margin:16px 0 0; color:#4a5568; line-height:1.6">
            To speed up the process, you can send me your drawings directly:
            <a href="mailto:tsudol.fabtec@yahoo.com">tsudol.fabtec@yahoo.com</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

@endif

@endsection
