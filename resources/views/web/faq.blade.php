@extends('layouts.web')

@section('title', $lang === 'fr'
    ? 'FAQ externalisation industrielle — Fab Sourcing'
    : 'Industrial outsourcing FAQ — Fab Sourcing')

@section('description', $lang === 'fr'
    ? "Toutes les réponses sur l'externalisation industrielle en Bulgarie : qualité, coûts, délais, logistique, marquage CE et accompagnement Fab Sourcing."
    : 'All the answers on industrial outsourcing to Bulgaria: quality, costs, lead times, logistics, CE marking and Fab Sourcing support.')

@push('seo')
<x-seo
  :title="$lang === 'fr' ? 'Questions fréquentes — Fab Sourcing' : 'Frequently asked questions — Fab Sourcing'"
  :description="$lang === 'fr'
    ? 'Externalisation industrielle en Europe de l\'Est : généralités, technique, finances, logistique, relation et stratégie. Toutes vos questions, nos réponses.'
    : 'Industrial outsourcing in Eastern Europe: basics, technical, financial, logistics, relationship and strategy. All your questions answered.'"
  :canonical="request()->url()"
  :lang="$lang"
  :hreflang-fr="$langSwitcherUrls['fr']"
  :hreflang-en="$langSwitcherUrls['en'] ?? null"
  og-type="website"
  :og-image="asset('images/og-default.jpg')"
/>
@endpush

@push('head')
<style>
  .faq-group { margin-bottom: 44px; }
  .faq-group-title { margin: 0 0 8px; }
  .faq-list { border-top: 1px solid rgba(15,30,61,0.10); }
  .faq-item { border-bottom: 1px solid rgba(15,30,61,0.10); }
  .faq-item summary {
    cursor: pointer; list-style: none; padding: 20px 0;
    display: flex; justify-content: space-between; align-items: flex-start; gap: 20px;
    font-weight: 600; font-size: 17px; line-height: 1.4; color: #0f1e3d;
    transition: color 0.15s ease;
  }
  .faq-item summary::-webkit-details-marker { display: none; }
  .faq-item summary::after { content: '+'; font-size: 24px; line-height: 1; color: #2b62d9; flex: 0 0 auto; }
  .faq-item[open] summary::after { content: '\2212'; }
  .faq-item summary:hover { color: #2b62d9; }
  .faq-answer { padding: 0 0 24px; max-width: 780px; color: #4a5568; line-height: 1.7; }
</style>
@endpush

@section('content')

{{-- Page hero --}}
<div class="page-hero">
  <div class="container">
    <div class="page-hero-grid reveal">
      <div>
        <div class="breadcrumb">
          <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
          <span>/</span>
          <span>FAQ</span>
        </div>
        <h1 class="h-1">
          @if($lang === 'fr')
            Questions fréquentes —<br><em>Externalisation industrielle en Europe de l'Est</em>
          @else
            Frequently asked questions —<br><em>Industrial outsourcing in Eastern Europe</em>
          @endif
        </h1>
      </div>
      <div>
        <p class="lede">
          {{ $lang === 'fr'
            ? "Tout ce que vous devez savoir avant d'externaliser votre production métallique en Bulgarie."
            : 'Everything you need to know before outsourcing your metalwork production to Bulgaria.' }}
        </p>
      </div>
    </div>
  </div>
</div>

@php
$categories = [
  [
    'title' => $lang === 'fr' ? "Généralités sur l'outsourcing" : 'Outsourcing basics',
    'items' => [
      [
        'q' => $lang === 'fr' ? "Qu'est-ce que l'externalisation industrielle ?" : 'What is industrial outsourcing?',
        'a' => $lang === 'fr'
          ? "L'externalisation industrielle consiste à déléguer tout ou partie de votre processus de production à un prestataire spécialisé externe. Contrairement à la simple sous-traitance ponctuelle, l'outsourcing implique une relation partenariale durable, avec transfert de responsabilité sur la qualité, les délais et la conformité. Chez Fab Sourcing, nous proposons une externalisation maîtrisée vers des ateliers certifiés en Bulgarie avec un interlocuteur unique francophone."
          : 'Industrial outsourcing means delegating all or part of your production process to a specialised external provider. Unlike simple one-off subcontracting, outsourcing involves a lasting partnership, with transfer of responsibility for quality, lead times and compliance. At Fab Sourcing, we offer controlled outsourcing to certified workshops in Bulgaria with a single French-speaking point of contact.',
      ],
      [
        'q' => $lang === 'fr' ? "Quelle différence entre sous-traitance et outsourcing ?" : 'What is the difference between subcontracting and outsourcing?',
        'a' => $lang === 'fr'
          ? "La sous-traitance traditionnelle est souvent ponctuelle et transactionnelle : vous envoyez un plan, vous recevez une pièce. L'outsourcing est relationnel et stratégique : nous intégrons votre chaîne de valeur, optimisons vos gammes de fabrication, sécurisons vos approvisionnements et vous libérons des contraintes opérationnelles pour vous concentrer sur votre cœur de métier."
          : 'Traditional subcontracting is often one-off and transactional: you send a drawing, you receive a part. Outsourcing is relational and strategic: we integrate your value chain, optimise your manufacturing processes, secure your supply and free you from operational constraints so you can focus on your core business.',
      ],
      [
        'q' => $lang === 'fr' ? "Pourquoi externaliser plutôt que produire en interne ?" : 'Why outsource rather than produce in-house?',
        'a' => $lang === 'fr'
          ? "Trois raisons structurelles : (1) Accès à des compétences rares — soudeurs certifiés, opérateurs CNC qualifiés sont de plus en plus difficiles à recruter en France ; (2) Flexibilité financière — transformation de coûts fixes (investissements, salaires) en coûts variables adaptés à votre activité ; (3) Concentration stratégique — vos équipes se consacrent à l'innovation et au commercial pendant que nous gérons la fabrication."
          : 'Three structural reasons: (1) Access to scarce skills — certified welders and qualified CNC operators are increasingly hard to recruit in France; (2) Financial flexibility — turning fixed costs (investment, salaries) into variable costs matched to your activity; (3) Strategic focus — your teams concentrate on innovation and sales while we handle manufacturing.',
      ],
    ],
  ],
  [
    'title' => $lang === 'fr' ? "Questions techniques" : 'Technical questions',
    'items' => [
      [
        'q' => $lang === 'fr' ? "Quels types de pièces pouvez-vous fabriquer ?" : 'What types of parts can you manufacture?',
        'a' => $lang === 'fr'
          ? "Nous couvrons l'intégralité de la métallurgie industrielle : découpe laser/plasma/poinçonnage, pliage CNC, usinage, soudure MIG/TIG/robotisée, assemblage mécano-soudé, finitions (thermolaquage, galvanisation, anodisation). Matériaux : acier S235/S355, inox 304L/316L, aluminium, zinc. Épaisseurs : de la tôle fine (0,5 mm) aux profilés lourds (jusqu'à 25 mm en découpe laser, plus en plasma)."
          : 'We cover the whole of industrial metalwork: laser/plasma cutting/punching, CNC bending, machining, MIG/TIG/robotic welding, welded assemblies, finishes (powder coating, galvanising, anodising). Materials: S235/S355 steel, 304L/316L stainless, aluminium, zinc. Thicknesses: from thin sheet (0.5 mm) to heavy profiles (up to 25 mm in laser cutting, more in plasma).',
      ],
      [
        'q' => $lang === 'fr' ? "Quelles sont vos capacités de série ?" : 'What are your production volumes?',
        'a' => $lang === 'fr'
          ? "De la pièce unitaire (prototype, pièce de rechange) aux grandes séries (plusieurs milliers d'unités par an). Notre réseau d'ateliers partenaires nous permet d'absorber les pics d'activité et de réduire les volumes en période creuse sans pénalité pour vous. Pas de minimum de commande imposé."
          : 'From single parts (prototype, spare part) to large series (several thousand units per year). Our network of partner workshops lets us absorb activity peaks and reduce volumes in quiet periods at no penalty to you. No minimum order imposed.',
      ],
      [
        'q' => $lang === 'fr' ? "Comment garantissez-vous la qualité ?" : 'How do you guarantee quality?',
        'a' => $lang === 'fr'
          ? "Trois niveaux de contrôle : (1) Audit initial de l'atelier partenaire selon nos critères Fab Sourcing ; (2) Contrôle in-process pendant la fabrication avec reporting hebdomadaire ; (3) Contrôle final, avec dossier qualité complet et traçabilité matière. Certifications disponibles : ISO 9001, EN 1090, EN ISO 3834."
          : 'Three levels of control: (1) Initial audit of the partner workshop against our Fab Sourcing criteria; (2) In-process control during manufacturing with weekly reporting; (3) Final inspection, with a complete quality file and material traceability. Available certifications: ISO 9001, EN 1090, EN ISO 3834.',
      ],
      [
        'q' => $lang === 'fr' ? "Puis-je obtenir des pièces avec marquage CE ?" : 'Can I get parts with CE marking?',
        'a' => $lang === 'fr'
          ? "Oui. Nos ateliers partenaires certifiés EN 1090-1/2 peuvent fabriquer des structures métalliques porteuses avec marquage CE. Nous gérons l'ensemble du processus de certification et du dossier technique pour vous."
          : 'Yes. Our EN 1090-1/2 certified partner workshops can manufacture load-bearing steel structures with CE marking. We manage the entire certification process and technical file for you.',
      ],
      [
        'q' => $lang === 'fr' ? "Gérez-vous l'approvisionnement en matière première ?" : 'Do you handle raw material sourcing?',
        'a' => $lang === 'fr'
          ? "Oui, c'est inclus dans notre service d'industrialisation. Nos partenaires approvisionnent des aciéries européennes certifiées (ArcelorMittal, ThyssenKrupp) avec mill-test et traçabilité complète. Vous pouvez également nous fournir votre propre matière si vous avez des conventions d'achat spécifiques."
          : 'Yes, it is included in our industrialisation service. Our partners source from certified European steelmakers (ArcelorMittal, ThyssenKrupp) with mill-test and full traceability. You can also supply your own material if you have specific purchasing agreements.',
      ],
    ],
  ],
  [
    'title' => $lang === 'fr' ? "Questions financières" : 'Financial questions',
    'items' => [
      [
        'q' => $lang === 'fr' ? "Quel est le niveau d'économies réalisable ?" : 'How much can I save?',
        'a' => $lang === 'fr'
          ? "Nos clients constatent généralement 30 à 50 % de réduction sur le coût total de possession (matière + fabrication + transport + qualité + coordination), comparé à une production en France ou en Allemagne. Ce chiffre varie selon la complexité technique, les volumes et les finitions. Nous établissons un calcul personnalisé lors de notre première analyse."
          : 'Our clients generally see 30 to 50% reduction in total cost of ownership (material + manufacturing + transport + quality + coordination), compared with production in France or Germany. This figure varies with technical complexity, volumes and finishes. We produce a personalised calculation during our first analysis.',
      ],
      [
        'q' => $lang === 'fr' ? "Comment est calculé le prix ?" : 'How is the price calculated?',
        'a' => $lang === 'fr'
          ? "Sur devis détaillé, poste par poste : matière (poids × cours), découpe (temps machine × taux horaire), pliage (nombre de plis × complexité), soudure (temps × qualification du soudeur), finitions (surface × type de traitement), assemblage (temps × complexité), emballage et transport. Pas de coûts cachés."
          : 'On a detailed quote, item by item: material (weight × price), cutting (machine time × hourly rate), bending (number of bends × complexity), welding (time × welder qualification), finishes (surface × treatment type), assembly (time × complexity), packaging and transport. No hidden costs.',
      ],
      [
        'q' => $lang === 'fr' ? "Y a-t-il des frais de mise en route ?" : 'Are there set-up costs?',
        'a' => $lang === 'fr'
          ? "Un coût de prototypage est appliqué pour la première série, couvrant la programmation CN, les réglages machine et la validation qualité. Ce coût est amorti dès la deuxième série. Pas de frais de mise en route pour les reprises de production existante."
          : 'A prototyping cost applies to the first series, covering CNC programming, machine set-up and quality validation. This cost is amortised from the second series onwards. No set-up fees for transferring existing production.',
      ],
      [
        'q' => $lang === 'fr' ? "Quels sont les délais de paiement ?" : 'What are the payment terms?',
        'a' => $lang === 'fr'
          ? "30 % à la commande, 70 % à la livraison pour les nouveaux clients. Conditions de paiement échelonnées possibles après établissement d'une relation commerciale régulière (6 mois de collaboration)."
          : '30% on order, 70% on delivery for new clients. Staged payment terms are possible once a regular business relationship is established (6 months of collaboration).',
      ],
    ],
  ],
  [
    'title' => $lang === 'fr' ? "Questions logistiques" : 'Logistics questions',
    'items' => [
      [
        'q' => $lang === 'fr' ? "Quel est le délai de livraison vers la France ?" : 'What is the delivery time to France?',
        'a' => $lang === 'fr'
          ? "3 à 7 jours en transport routier direct depuis la Bulgarie. Pour les volumes importants, nous pouvons organiser du groupage ou des livraisons programmées hebdomadaires. Pas de délai maritime, pas de risque de blocage portuaire."
          : '3 to 7 days by direct road transport from Bulgaria. For large volumes, we can arrange groupage or scheduled weekly deliveries. No sea-freight time, no risk of port blockage.',
      ],
      [
        'q' => $lang === 'fr' ? "Livrez-vous sur site ou en entrepôt ?" : 'Do you deliver on site or to a warehouse?',
        'a' => $lang === 'fr'
          ? "Les deux. Livraison directe sur votre site de production, sur chantier, ou en entrepôt logistique si vous gérez un stock de sécurité. Nous adaptons l'emballage à chaque configuration : caisses bois pour pièces fragiles, racks métalliques pour structures lourdes, film étirable pour pièces standard."
          : 'Both. Direct delivery to your production site, to a construction site, or to a logistics warehouse if you manage safety stock. We adapt the packaging to each configuration: wooden crates for fragile parts, metal racks for heavy structures, stretch film for standard parts.',
      ],
      [
        'q' => $lang === 'fr' ? "Gérez-vous les formalités douanières ?" : 'Do you handle customs formalities?',
        'a' => $lang === 'fr'
          ? "Aucune formalité douanière nécessaire entre la Bulgarie et la France : les deux pays sont membres de l'Union Européenne. La marchandise circule librement avec document de transport CMR. Pas de dédouanement, pas de droits d'importation."
          : 'No customs formalities are needed between Bulgaria and France: both countries are members of the European Union. Goods move freely with a CMR transport document. No customs clearance, no import duties.',
      ],
      [
        'q' => $lang === 'fr' ? "Pouvez-vous stocker chez vous ?" : 'Can you store goods at your end?',
        'a' => $lang === 'fr'
          ? "Oui, nous proposons un service de stockage consigné en Bulgarie. Vous produisez en volume pour bénéficier des économies d'échelle, et nous livrons au fil de vos besoins. Cela réduit vos stocks en France et améliore votre trésorerie."
          : 'Yes, we offer a consignment storage service in Bulgaria. You produce in volume to benefit from economies of scale, and we deliver as your needs arise. This reduces your stock in France and improves your cash flow.',
      ],
    ],
  ],
  [
    'title' => $lang === 'fr' ? "Questions relationnelles" : 'Working relationship',
    'items' => [
      [
        'q' => $lang === 'fr' ? "Qui est mon interlocuteur ?" : 'Who is my point of contact?',
        'a' => $lang === 'fr'
          ? "Un seul contact francophone : Thierry Sudol, basé en France. Il pilote l'ensemble de votre projet : analyse technique, sélection atelier, suivi qualité, logistique, facturation. Vous ne dialoguez pas directement avec les ateliers bulgares — nous gérons cette interface pour vous."
          : 'A single French-speaking contact: Thierry Sudol, based in France. He manages your entire project: technical analysis, workshop selection, quality monitoring, logistics, invoicing. You do not deal directly with the Bulgarian workshops — we manage that interface for you.',
      ],
      [
        'q' => $lang === 'fr' ? "Comment se déroule le premier contact ?" : 'How does the first contact work?',
        'a' => $lang === 'fr'
          ? "(1) Vous nous envoyez vos plans (PDF, DWG, STEP) et votre cahier des charges ; (2) Nous analysons la faisabilité technique sous 48h ; (3) Nous vous proposons un devis détaillé avec planning prévisionnel ; (4) Si vous validez, nous lançons le prototypage ; (5) Après validation du prototype, nous passons en production série."
          : '(1) You send us your drawings (PDF, DWG, STEP) and your specifications; (2) We assess technical feasibility within 48 hours; (3) We propose a detailed quote with a provisional schedule; (4) If you approve, we launch prototyping; (5) After prototype validation, we move to series production.',
      ],
      [
        'q' => $lang === 'fr' ? "Puis-je visiter les ateliers ?" : 'Can I visit the workshops?',
        'a' => $lang === 'fr'
          ? "Bien sûr. Nous organisons des visites d'audit qualité sur site en Bulgarie, accompagnées par notre équipe. C'est d'ailleurs une étape recommandée pour les projets à forte valeur ajoutée ou les partenariats de long terme."
          : 'Of course. We organise quality-audit visits on site in Bulgaria, accompanied by our team. It is in fact a recommended step for high-value projects or long-term partnerships.',
      ],
      [
        'q' => $lang === 'fr' ? "Et si la qualité n'est pas conforme ?" : 'What if the quality is not compliant?',
        'a' => $lang === 'fr'
          ? "Notre contrat prévoit des clauses de conformité avec droit de retour et refabrication aux frais de Fab Sourcing. Notre taux de non-conformité est inférieur à 1 % grâce à nos contrôles in-process. En cas d'écart, nous activons immédiatement un plan d'action correctif."
          : "Our contract includes compliance clauses with the right to return and remanufacture at Fab Sourcing's expense. Our non-conformity rate is below 1% thanks to our in-process controls. In the event of a discrepancy, we immediately trigger a corrective action plan.",
      ],
    ],
  ],
  [
    'title' => $lang === 'fr' ? "Questions stratégiques" : 'Strategic questions',
    'items' => [
      [
        'q' => $lang === 'fr' ? "Faut-il tout externaliser ou peut-on commencer partiellement ?" : 'Should I outsource everything, or can I start partially?',
        'a' => $lang === 'fr'
          ? "Les deux approches sont possibles. L'externalisation partielle est idéale pour tester notre méthode : vous conservez en interne les savoir-faire stratégiques (assemblage final, R&D) et externalisez une opération technique spécifique (découpe, soudure). L'externalisation totale libère votre structure de toute la chaîne de fabrication et vous recentre sur le commercial et l'innovation."
          : 'Both approaches are possible. Partial outsourcing is ideal for testing our method: you keep strategic know-how in-house (final assembly, R&D) and outsource a specific technical operation (cutting, welding). Full outsourcing frees your organisation from the entire manufacturing chain and refocuses you on sales and innovation.',
      ],
      [
        'q' => $lang === 'fr' ? "Mes données techniques sont-elles protégées ?" : 'Is my technical data protected?',
        'a' => $lang === 'fr'
          ? "Absolument. Nos contrats incluent des clauses de confidentialité strictes (NDA). Nos ateliers partenaires sont liés par des accords de non-divulgation. La propriété intellectuelle de vos plans et procédés vous appartient exclusivement. Cadre juridique européen = protection renforcée."
          : 'Absolutely. Our contracts include strict confidentiality clauses (NDA). Our partner workshops are bound by non-disclosure agreements. The intellectual property of your drawings and processes remains exclusively yours. European legal framework = enhanced protection.',
      ],
      [
        'q' => $lang === 'fr' ? "Quelle est la durée minimale d'engagement ?" : 'What is the minimum commitment period?',
        'a' => $lang === 'fr'
          ? "Aucune. Nous fonctionnons commande par commande pour la phase de test, puis proposons des cadences de partenariat annuelles avec tarifs dégressifs pour les volumes réguliers. Vous restez libre de réajuster, d'augmenter ou de réduire sans pénalité."
          : 'None. We work order by order for the test phase, then propose annual partnership volumes with decreasing rates for regular volumes. You remain free to readjust, increase or reduce without penalty.',
      ],
    ],
  ],
];
@endphp

{{-- FAQ groups --}}
<section class="section">
  <div class="container">
    @foreach($categories as $cat)
      <div class="faq-group reveal">
        <h2 class="h-2 faq-group-title">{{ $cat['title'] }}</h2>
        <div class="faq-list">
          @foreach($cat['items'] as $item)
            <details class="faq-item">
              <summary>{{ $item['q'] }}</summary>
              <div class="faq-answer">{{ $item['a'] }}</div>
            </details>
          @endforeach
        </div>
      </div>
    @endforeach
  </div>
</section>

{{-- Still have a question? --}}
<section class="cta-section">
  <div class="container">
    <div class="cta-inner reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'Une autre question ?' : 'Another question?' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Vous ne trouvez pas <em>la réponse</em> à votre question ?
          @else
            Can't find <em>the answer</em> to your question?
          @endif
        </h2>
        <p class="lede" style="margin-top:20px">
          {{ $lang === 'fr'
            ? 'Contactez Thierry Sudol directement :'
            : 'Contact Thierry Sudol directly:' }}
        </p>
      </div>
      <div style="display:flex; flex-direction:column; gap:16px; align-items:flex-start">
        <a href="tel:+33784057375" class="btn btn-primary" style="font-size:16px; padding:18px 28px">
          +33 (0)7 84 05 73 75
        </a>
        <a href="{{ route('contact', $lang) }}" class="btn-link">
          {{ $lang === 'fr' ? 'Ou via le formulaire de contact' : 'Or via the contact form' }}
          <span class="arrow">→</span>
        </a>
      </div>
    </div>
  </div>
</section>

@endsection
