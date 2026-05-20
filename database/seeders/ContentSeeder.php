<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPages();
        $this->seedBlogTranslations();
        $this->seedProducts();
        $this->seedSettings();
        $this->createPlaceholderImage();
    }

    // ─────────────────────────────────────────────
    // PAGES
    // ─────────────────────────────────────────────

    private function seedPages(): void
    {
        $pages = [
            // home (id=9)
            9 => [
                'title'            => ['fr' => 'Accueil', 'en' => 'Home'],
                'meta_title'       => [
                    'fr' => 'Outsourcing industriel en Bulgarie & Roumanie | Métallerie & fabrication acier',
                    'en' => 'Industrial Outsourcing in Bulgaria & Romania | Metalwork & Steel Fabrication',
                ],
                'meta_description' => [
                    'fr' => 'Externalisez votre production industrielle en Europe de l\'Est. Métallerie, structures acier, escaliers, garde-corps. Qualité UE, coûts réduits.',
                    'en' => 'Outsource your industrial production to Eastern Europe. Metalwork, steel structures, stairs, railings. EU-certified quality, reduced costs.',
                ],
                'published'        => true,
            ],
            // services (id=2)
            2 => [
                'title'            => ['fr' => 'Services', 'en' => 'Services'],
                'meta_title'       => [
                    'fr' => 'Nos services de sous-traitance industrielle | Fab Sourcing',
                    'en' => 'Our Industrial Subcontracting Services | Fab Sourcing',
                ],
                'meta_description' => [
                    'fr' => 'Fab Sourcing vous accompagne à chaque étape : sourcing, qualité, logistique. Externalisation en Bulgarie clé en main.',
                    'en' => 'Fab Sourcing guides you through every step: sourcing, quality control, logistics. Turnkey outsourcing in Bulgaria.',
                ],
                'published'        => true,
            ],
            // why-eastern-europe (id=3)
            3 => [
                'title'            => ['fr' => "Pourquoi l'Europe de l'Est", 'en' => 'Why Eastern Europe'],
                'meta_title'       => [
                    'fr' => "Pourquoi l'Europe de l'Est pour votre production ? | Fab Sourcing",
                    'en' => 'Why Source from Eastern Europe? | Fab Sourcing',
                ],
                'meta_description' => [
                    'fr' => 'Bulgarie et Roumanie : qualité européenne certifiée, coûts réduits de 40 à 60 %. Découvrez les avantages de la sous-traitance en Europe de l\'Est.',
                    'en' => 'Bulgaria and Romania: certified European quality, 40–60% cost reduction. Discover the benefits of Eastern European industrial subcontracting.',
                ],
                'published'        => true,
            ],
            // methodology (id=4)
            4 => [
                'title'            => ['fr' => 'Méthodologie', 'en' => 'Methodology'],
                'meta_title'       => [
                    'fr' => 'Notre méthodologie en 7 étapes | Fab Sourcing',
                    'en' => 'Our 7-Step Methodology | Fab Sourcing',
                ],
                'meta_description' => [
                    'fr' => 'De l\'analyse de votre besoin à la livraison : découvrez notre processus en 7 étapes pour une externalisation industrielle sans risque.',
                    'en' => 'From needs analysis to delivery: discover our 7-step process for risk-free industrial outsourcing.',
                ],
                'published'        => true,
            ],
            // about (id=5)
            5 => [
                'title'            => ['fr' => 'À propos', 'en' => 'About'],
                'meta_title'       => [
                    'fr' => 'À propos de Fab Sourcing — Thierry Sudol',
                    'en' => 'About Fab Sourcing — Thierry Sudol',
                ],
                'meta_description' => [
                    'fr' => 'Fab Sourcing, spécialiste de la sous-traitance industrielle en Bulgarie depuis 2010. Fondé par Thierry Sudol, expert terrain en fabrication métallique.',
                    'en' => 'Fab Sourcing, specialist in industrial subcontracting in Bulgaria since 2010. Founded by Thierry Sudol, on-the-ground expert in metalwork manufacturing.',
                ],
                'published'        => true,
            ],
            // contact (id=6)
            6 => [
                'title'            => ['fr' => 'Contact', 'en' => 'Contact'],
                'meta_title'       => [
                    'fr' => 'Contactez Fab Sourcing — Réponse sous 48 h',
                    'en' => 'Contact Fab Sourcing — Reply within 48 Hours',
                ],
                'meta_description' => [
                    'fr' => 'Décrivez votre projet de sous-traitance industrielle. Thierry Sudol vous répond personnellement sous 48 heures.',
                    'en' => 'Describe your industrial outsourcing project. Thierry Sudol will reply personally within 48 hours.',
                ],
                'published'        => true,
            ],
            // blog (id=10)
            10 => [
                'title'            => ['fr' => 'Blog', 'en' => 'Blog'],
                'meta_title'       => [
                    'fr' => 'Blog industriel — Fab Sourcing',
                    'en' => 'Industrial Blog — Fab Sourcing',
                ],
                'meta_description' => [
                    'fr' => "Conseils, études de cas et actualités sur la sous-traitance industrielle en Europe de l'Est.",
                    'en' => 'Advice, case studies and news on industrial subcontracting in Eastern Europe.',
                ],
                'published'        => true,
            ],
            // mentions-legales (id=7) — add EN translation of existing FR content
            7 => [
                'title'            => ['fr' => 'Mentions légales', 'en' => 'Legal Notice'],
                'meta_title'       => [
                    'fr' => 'Mentions légales | Fab Sourcing',
                    'en' => 'Legal Notice | Fab Sourcing',
                ],
                'meta_description' => [
                    'fr' => 'Mentions légales du site fab-sourcing.fr — Thierry Sudol, éditeur.',
                    'en' => 'Legal notice for fab-sourcing.fr — Thierry Sudol, publisher.',
                ],
                'content' => [
                    'fr' => '<h2>Éditeur du site</h2><p>Fab Sourcing — Thierry Sudol<br>1, route Neuve<br>24150 Saint-Capraise-de-Lalinde<br>France</p><p>Email : tsudol.fabtec@yahoo.com<br>Téléphone : +33 (0)7 82 08 51 17</p><h2>Hébergement</h2><p>Ce site est hébergé par un prestataire établi au sein de l\'Union Européenne.</p><h2>Propriété intellectuelle</h2><p>L\'ensemble des contenus présents sur ce site (textes, images, graphiques) sont la propriété exclusive de Fab Sourcing, sauf mention contraire. Toute reproduction, diffusion ou utilisation sans autorisation préalable est interdite.</p><h2>Responsabilité</h2><p>Fab Sourcing s\'efforce de fournir des informations exactes et à jour. Toutefois, la société ne saurait être tenue responsable des erreurs ou omissions, ni de tout préjudice découlant de l\'utilisation des informations publiées.</p>',
                    'en' => '<h2>Site Publisher</h2><p>Fab Sourcing — Thierry Sudol<br>1, route Neuve<br>24150 Saint-Capraise-de-Lalinde<br>France</p><p>Email: tsudol.fabtec@yahoo.com<br>Phone: +33 (0)7 82 08 51 17</p><h2>Hosting</h2><p>This website is hosted by a provider established within the European Union.</p><h2>Intellectual Property</h2><p>All content on this site (text, images, graphics) is the exclusive property of Fab Sourcing unless otherwise stated. Any reproduction, distribution or use without prior authorisation is prohibited.</p><h2>Liability</h2><p>Fab Sourcing endeavours to provide accurate and up-to-date information. However, the company cannot be held liable for errors or omissions, or for any loss arising from the use of published information.</p>',
                ],
                'published'        => true,
            ],
            // politique-de-confidentialite (id=8) — add EN translation
            8 => [
                'title'            => ['fr' => 'Politique de confidentialité', 'en' => 'Privacy Policy'],
                'meta_title'       => [
                    'fr' => 'Politique de confidentialité | Fab Sourcing',
                    'en' => 'Privacy Policy | Fab Sourcing',
                ],
                'meta_description' => [
                    'fr' => 'Politique de confidentialité et gestion des données personnelles — fab-sourcing.fr.',
                    'en' => 'Privacy policy and personal data management — fab-sourcing.fr.',
                ],
                'content' => [
                    'fr' => '<h2>Données collectées</h2><p>Lorsque vous soumettez le formulaire de contact, nous collectons les informations suivantes : nom, entreprise (optionnel), adresse email, numéro de téléphone (optionnel), et votre message. Ces données sont utilisées uniquement pour répondre à votre demande.</p><h2>Conservation des données</h2><p>Vos données sont conservées pendant une durée maximale de 3 ans à compter de votre dernière interaction avec Fab Sourcing. Vous pouvez demander leur suppression à tout moment.</p><h2>Partage des données</h2><p>Fab Sourcing ne vend, ne loue ni ne partage vos données personnelles avec des tiers, sauf obligation légale.</p><h2>Vos droits</h2><p>Conformément au RGPD, vous disposez d\'un droit d\'accès, de rectification, de suppression et d\'opposition concernant vos données personnelles. Pour exercer ces droits, contactez-nous à : tsudol.fabtec@yahoo.com</p><h2>Cookies</h2><p>Ce site n\'utilise pas de cookies publicitaires. Des cookies techniques strictement nécessaires au fonctionnement du site peuvent être utilisés.</p>',
                    'en' => '<h2>Data Collected</h2><p>When you submit the contact form, we collect the following information: name, company (optional), email address, phone number (optional), and your message. This data is used solely to respond to your enquiry.</p><h2>Data Retention</h2><p>Your data is retained for a maximum of 3 years from your last interaction with Fab Sourcing. You may request deletion at any time.</p><h2>Data Sharing</h2><p>Fab Sourcing does not sell, rent or share your personal data with third parties, except where required by law.</p><h2>Your Rights</h2><p>In accordance with the GDPR, you have the right to access, rectify, delete and object to the processing of your personal data. To exercise these rights, contact us at: tsudol.fabtec@yahoo.com</p><h2>Cookies</h2><p>This site does not use advertising cookies. Strictly necessary technical cookies required for site operation may be used.</p>',
                ],
                'published'        => true,
            ],
        ];

        foreach ($pages as $id => $data) {
            $page = Page::find($id);
            if (! $page) {
                continue;
            }
            $page->update(array_merge(['published' => true], $data));
        }

        $this->command->info('Pages seeded.');
    }

    // ─────────────────────────────────────────────
    // BLOG POSTS — English translations
    // ─────────────────────────────────────────────

    private function seedBlogTranslations(): void
    {
        $translations = [
            1 => [
                'title' => 'Why Outsource Manufacturing to Eastern Europe?',
                'excerpt' => 'As production costs rise across Western Europe, Eastern Europe has emerged as a premier industrial outsourcing destination. Here is why more and more French manufacturers are making the move.',
                'body' => '<h2>A Structural Response to Cost Pressure</h2><p>For several years, French manufacturers have faced a difficult equation: maintaining production quality while keeping a lid on relentlessly rising costs. Labour, social charges, energy, raw materials — every line item is squeezing margins further. Outsourcing to Eastern Europe has emerged as a structural answer to this pressure.</p><h2>Real Savings Without Quality Compromise</h2><p>In Bulgaria and Romania, skilled metalwork labour costs are 40–60% lower than in France or Germany. This differential translates directly into lower unit costs for fabricated parts and steel assemblies. Yet these savings do not come at the expense of quality: partner workshops operate to the same European requirements (CE compliance, traceability, quality standards) as their Western counterparts.</p><h2>Geographic Proximity — A Decisive Advantage</h2><p>Unlike Far East subcontracting, Eastern Europe offers invaluable proximity. Lead times are measured in days, not weeks. Delivery to France takes 3–4 days by road. This accessibility facilitates workshop visits, on-site quality audits and rapid adjustments during production.</p><h2>A Shared Regulatory Framework</h2><p>EU member states share the same legal and regulatory framework: CE compliance, intellectual property protection, harmonised labour law. This alignment reduces contractual risk and simplifies the administrative management of international orders.</p><h2>Local Expertise Makes the Difference</h2><p>The success of any outsourcing relationship hinges largely on the quality of the intermediary. Fab Sourcing selects and qualifies partner workshops against strict criteria: production capacity, standards compliance, financial stability, and client references. Our engineers oversee every order from technical consultation through to delivery.</p>',
                'tags' => ['subcontracting', 'Eastern Europe', 'outsourcing', 'metalwork'],
                'meta_title' => 'Why Outsource Manufacturing to Eastern Europe? | Fab Sourcing',
                'meta_description' => 'Real cost savings, EU-standard quality, and a shared regulatory framework — the case for Eastern European industrial subcontracting.',
                'reading_time_minutes' => 4,
            ],
            2 => [
                'title' => 'Bulgaria vs. Romania: Which Country for Industrial Subcontracting?',
                'excerpt' => 'Both Eastern European countries are frequently compared as outsourcing destinations. Costs, expertise, logistics, stability — we break down the differences point by point to help you choose.',
                'body' => '<h2>Two Complementary Destinations</h2><p>Bulgaria and Romania are regularly cited side by side as preferred destinations for industrial subcontracting in Europe. Both EU members, they share common strengths — competitive costs, skilled labour, European standards — yet present distinct profiles depending on sector and requirements.</p><h2>Production Costs</h2><p>Bulgaria has the lowest labour costs in the EU. Its minimum wage remains below Romania\'s, translating into slightly more competitive unit costs for labour-intensive production. Romania, whose economy has grown more rapidly, has seen costs edge progressively closer to the European average — while still remaining far below French or German levels.</p><h2>Industrial Ecosystem and Expertise</h2><p>Romania has a denser industrial base, a legacy of its extensive Soviet-era manufacturing capacity that has since been modernised. It is particularly recognised for automotive, heavy mechanics and industrial equipment. Bulgaria, for its part, excels in precision metalwork, boilermaking, steel structures and high-value-added finished products.</p><h2>Logistics and Accessibility</h2><p>Both capitals are well connected to European hubs. Romania benefits from Black Sea access via the port of Constanța, useful for certain freight flows. Bulgaria also has Mediterranean access via the ports of Varna and Burgas.</p><h2>Our Recommendation</h2><p>For metalwork production — steel structures, railings, glazed partitions or facades — Bulgaria is our primary recommendation. This is why Fab Sourcing has built its industrial partner network exclusively in Bulgaria, backed by extensive on-the-ground experience.</p>',
                'tags' => ['Bulgaria', 'Romania', 'comparison', 'industrial subcontracting'],
                'meta_title' => 'Bulgaria vs. Romania for Industrial Subcontracting | Fab Sourcing',
                'meta_description' => 'A point-by-point comparison of Bulgaria and Romania as outsourcing destinations: costs, expertise, logistics and our recommendation.',
                'reading_time_minutes' => 4,
            ],
            3 => [
                'title' => 'How to Reduce Manufacturing Costs in Metalwork',
                'excerpt' => 'Labour is a major cost driver in metalwork — from cutting and welding to finishing and assembly. Here are the concrete levers to reduce your costs without compromising on quality.',
                'body' => '<h2>Why Metalwork is Particularly Exposed</h2><p>Metalwork — the fabrication of steel structures, stairs, railings, joinery and cladding — is a sector where labour accounts for a significant share of unit costs. Cutting, welding, finishing, assembly: every stage requires skilled personnel. Against a backdrop of tight labour markets across Western Europe, these costs have risen sharply.</p><h2>Lever 1: Partial or Full Outsourcing</h2><p>Outsourcing all or part of production to an Eastern European partner delivers a meaningful cost differential without leaving the EU regulatory zone. Parts and assemblies are manufactured to your drawings and specifications in certified workshops, then delivered ready to install.</p><h2>Lever 2: Standardising Drawings</h2><p>Well-documented drawings with clearly defined tolerances reduce technical back-and-forth and non-conformities. A complete technical file submitted to the subcontractor from the outset optimises production lead times and minimises costly corrections.</p><h2>Lever 3: Order Consolidation</h2><p>Fixed setup costs — machine programming, tooling, logistics — are spread over volume. Consolidating several projects into a single order, or anticipating recurring needs to batch-produce, can significantly reduce the unit cost.</p><h2>Lever 4: Choosing the Right Partner</h2><p>A reliable subcontractor with the right machinery and proven experience in your type of production will deliver fewer rejects, meet deadlines more consistently and require less corrective oversight. Rigorous partner selection is itself a long-term cost-saving lever.</p><p>Fab Sourcing supports its clients in optimising their production chain from Bulgaria, combining cost competitiveness with uncompromising quality standards.</p>',
                'tags' => ['cost reduction', 'metalwork', 'manufacturing', 'optimisation'],
                'meta_title' => 'How to Reduce Metalwork Manufacturing Costs | Fab Sourcing',
                'meta_description' => 'Four concrete levers to cut production costs in metalwork without sacrificing quality — from outsourcing to order consolidation.',
                'reading_time_minutes' => 4,
            ],
            4 => [
                'title' => 'How to Choose a Reliable Industrial Subcontractor',
                'excerpt' => 'Selecting an industrial subcontractor is about much more than comparing quotes. Here are the key criteria to identify a trustworthy partner capable of meeting your requirements over the long term.',
                'body' => '<h2>Price is Not the Only Criterion</h2><p>When a company looks for an industrial subcontractor, the natural reflex is to compare quotes. That\'s necessary, but far from sufficient. A cheap subcontractor who delivers late, produces non-conformities or lacks responsiveness will ultimately cost far more than a slightly pricier but serious partner.</p><h2>Criterion 1: Certifications and Accreditations</h2><p>Certifications (ISO 9001, EN 1090 for steel structures, EN 3834 for welding) attest to the implementation of formalised quality processes. They do not guarantee excellence, but they are a non-negotiable baseline for any serious partnership. Always ask for up-to-date certificates.</p><h2>Criterion 2: Technical Capability and Machinery</h2><p>Your subcontractor must have the equipment suited to your parts: laser or plasma cutting, CNC press brake, MIG/TIG welding stations, paint booth. Even a virtual workshop visit quickly reveals whether their actual capabilities match your requirements.</p><h2>Criterion 3: References and Sector Experience</h2><p>Ask for references in your sector. A subcontractor experienced in producing railings for construction sites may not be suited to complex industrial structures, and vice versa. Sector-specific experience shortens the learning curve and reduces the risk of deviation.</p><h2>Criterion 4: Communication and Responsiveness</h2><p>The quality of the commercial and technical relationship is critical. A good subcontractor responds promptly to quote requests, asks pertinent questions about specifications, and communicates proactively when issues arise. Language barriers can be an obstacle: ensure you have a French- or English-speaking technical contact.</p><h2>Criterion 5: Financial Stability</h2><p>A subcontractor in financial difficulty can halt your production without notice. Before placing a first significant order, check the company\'s financial solidity: longevity, headcount, key clients. Fab Sourcing carries out these checks systematically for each of its partner workshops.</p>',
                'tags' => ['subcontractor', 'selection criteria', 'quality', 'industrial partnership'],
                'meta_title' => 'How to Choose a Reliable Industrial Subcontractor | Fab Sourcing',
                'meta_description' => 'Five key criteria for identifying a trustworthy industrial subcontractor: certifications, capability, references, communication and financial stability.',
                'reading_time_minutes' => 5,
            ],
        ];

        foreach ($translations as $id => $data) {
            $post = BlogPost::find($id);
            if (! $post) {
                continue;
            }

            $post->setTranslation('title', 'en', $data['title'])
                ->setTranslation('excerpt', 'en', $data['excerpt'])
                ->setTranslation('body', 'en', $data['body'])
                ->setTranslation('tags', 'en', $data['tags'])
                ->setTranslation('meta_title', 'en', $data['meta_title'])
                ->setTranslation('meta_description', 'en', $data['meta_description']);

            // Also set FR meta_title and meta_description if missing
            if (! $post->getTranslation('meta_title', 'fr', false)) {
                $post->setTranslation('meta_title', 'fr', $data['meta_title']);
                $post->setTranslation('meta_description', 'fr', $data['meta_description']);
            }

            if (isset($data['reading_time_minutes'])) {
                $post->reading_time_minutes = $data['reading_time_minutes'];
            }

            $post->save();
        }

        $this->command->info('Blog post EN translations seeded.');
    }

    // ─────────────────────────────────────────────
    // PRODUCTS — 3 per category = 27 total
    // ─────────────────────────────────────────────

    private function seedProducts(): void
    {
        // Skip if products already exist
        if (Product::count() > 0) {
            $this->command->info('Products already seeded — skipping.');
            return;
        }

        $products = [

            // ── Cat 1: Structures métalliques (id=1) ──
            [
                'product_category_id' => 1,
                'slug'     => 'charpente-metallique-industrielle',
                'slug_en'  => 'industrial-steel-frame',
                'name'     => ['fr' => 'Charpente métallique industrielle', 'en' => 'Industrial Steel Frame'],
                'short_description' => [
                    'fr' => 'Structure portante acier pour entrepôts, ateliers et bâtiments industriels, fabriquée selon plans et normes européennes en vigueur.',
                    'en' => 'Load-bearing steel frame for warehouses, workshops and industrial buildings, manufactured to drawings and applicable European standards.',
                ],
                'full_description' => [
                    'fr' => '<p>Nos charpentes métalliques industrielles sont conçues pour répondre aux exigences structurelles les plus sévères. Fabriquées en acier S235 ou S355 selon les charges calculées, elles intègrent poteaux, poutres IPE/HEA, contreventements et platines d\'ancrage. Chaque ensemble est livré sablé et imprimé, prêt pour l\'application de la couche de finition sur chantier. Nos ateliers partenaires en Bulgarie assurent la conformité aux normes européennes et la traçabilité complète des matériaux.</p>',
                    'en' => '<p>Our industrial steel frames are engineered to meet the most demanding structural requirements. Fabricated in S235 or S355 grade steel to calculated load specifications, they incorporate columns, IPE/HEA beams, bracing and base plates. Each assembly is delivered shot-blasted and primed, ready for topcoat application on site. Our partner workshops in Bulgaria ensure compliance with European standards and full material traceability.</p>',
                ],
                'features' => [
                    'fr' => '<ul><li>Acier S235 / S355 selon calcul</li><li>Conformité normes européennes</li><li>Traçabilité matière complète</li><li>Livraison sablée + primaire anticorrosion</li><li>Platines, goujons et boulonnerie inclus</li></ul>',
                    'en' => '<ul><li>S235 / S355 steel to structural calculations</li><li>European standard compliance</li><li>Full material traceability</li><li>Delivered shot-blasted + anti-corrosion primer</li><li>Base plates, anchor bolts and fixings included</li></ul>',
                ],
                'materials' => [
                    'fr' => 'Acier de construction S235JR / S355JR, boulonnerie 8.8, platines A42.',
                    'en' => 'Structural steel S235JR / S355JR, grade 8.8 bolts, A42 base plates.',
                ],
                'specifications' => [
                    'fr' => 'Portées jusqu\'à 30 m. Hauteur sous faîtage jusqu\'à 12 m. Tolérances serrées. Finition : grenaillage SA 2½ + primaire époxy 60 µm.',
                    'en' => 'Spans up to 30 m. Ridge height up to 12 m. Tight fabrication tolerances. Finish: shot-blast SA 2½ + 60 µm epoxy primer.',
                ],
                'sort_order' => 1, 'published' => true, 'featured' => true,
            ],
            [
                'product_category_id' => 1,
                'slug'     => 'ossature-acier-batiment',
                'slug_en'  => 'steel-building-structure',
                'name'     => ['fr' => 'Ossature acier pour bâtiment', 'en' => 'Steel Building Structure'],
                'short_description' => [
                    'fr' => 'Ossature acier secondaire pour bardage, toiture ou plancher — profilés à froid, rails et montants sur mesure.',
                    'en' => 'Secondary steel framework for cladding, roofing or floor systems — cold-formed sections, rails and studs to specification.',
                ],
                'full_description' => [
                    'fr' => '<p>Complément indispensable de la charpente principale, l\'ossature secondaire en acier assure le support des éléments de façade, de toiture et de plancher. Profilés Z, C, oméga et rails en acier galvanisé sont fabriqués sur mesure selon les plans d\'architecte. Nos ateliers assurent la découpe, le perçage et le pliage avec précision, pour une pose rapide sur chantier.</p>',
                    'en' => '<p>The essential complement to the primary frame, secondary steel structure provides the support for façade, roofing and floor elements. Z, C, omega sections and galvanised steel rails are custom-fabricated to architect\'s drawings. Our workshops handle cutting, drilling and bending to tight tolerances for rapid site installation.</p>',
                ],
                'features' => [
                    'fr' => '<ul><li>Profilés Z, C, oméga galvanisés</li><li>Découpe et perçage CNC</li><li>Assemblage par boulonnerie A2/A4</li><li>Plans d\'atelier fournis</li></ul>',
                    'en' => '<ul><li>Galvanised Z, C, omega sections</li><li>CNC cutting and drilling</li><li>A2/A4 bolted assembly</li><li>Workshop drawings supplied</li></ul>',
                ],
                'materials' => ['fr' => 'Acier galvanisé Z275 ou Z350.', 'en' => 'Z275 or Z350 galvanised steel.'],
                'specifications' => [
                    'fr' => 'Épaisseurs 1,5 à 4 mm. Longueurs jusqu\'à 12 m. Galvanisation à chaud ou prélaqué.',
                    'en' => 'Thicknesses 1.5 to 4 mm. Lengths up to 12 m. Hot-dip galvanised or pre-painted.',
                ],
                'sort_order' => 2, 'published' => true, 'featured' => false,
            ],
            [
                'product_category_id' => 1,
                'slug'     => 'mezzanine-industrielle-acier',
                'slug_en'  => 'industrial-steel-mezzanine',
                'name'     => ['fr' => 'Mezzanine industrielle acier', 'en' => 'Industrial Steel Mezzanine'],
                'short_description' => [
                    'fr' => 'Mezzanine acier autoportante pour entrepôt ou atelier — plancher caillebotis ou tôle larmée, garde-corps intégré.',
                    'en' => 'Self-supporting steel mezzanine for warehouse or workshop — open-grid or chequerplate floor, integrated railing.',
                ],
                'full_description' => [
                    'fr' => '<p>Nos mezzanines industrielles permettent de doubler la surface utile d\'un entrepôt sans extension du bâtiment. La structure autoportante en acier (poteaux HEB, poutres IPE, solives) est dimensionnée pour des charges d\'exploitation de 200 à 750 kg/m². Le plancher est en caillebotis galvanisé ou tôle larmée selon l\'usage. Le système comprend un escalier d\'accès et des garde-corps réglementaires.</p>',
                    'en' => '<p>Our industrial mezzanines double the usable floor area of a warehouse without extending the building. The self-supporting steel structure (HEB columns, IPE beams, joists) is sized for live loads from 200 to 750 kg/m². The floor deck is open-grid galvanised grating or chequerplate, depending on use. The system includes an access stair and compliant safety railings.</p>',
                ],
                'features' => [
                    'fr' => '<ul><li>Charge utile 200–750 kg/m²</li><li>Plancher caillebotis ou tôle larmée</li><li>Escalier et garde-corps inclus</li><li>Ancrage au sol par platines boulonnées</li><li>Calcul de structure fourni</li></ul>',
                    'en' => '<ul><li>Live load 200–750 kg/m²</li><li>Open-grid or chequerplate deck</li><li>Stair and safety railings included</li><li>Bolted base plate floor anchorage</li><li>Structural calculation provided</li></ul>',
                ],
                'materials' => ['fr' => 'Acier S235 / S355, caillebotis galvanisé 30×100.', 'en' => 'S235 / S355 steel, 30×100 galvanised open-grid grating.'],
                'specifications' => [
                    'fr' => 'Hauteur libre sous mezzanine 2,50 m minimum. Surface jusqu\'à 1 000 m² par niveau.',
                    'en' => 'Minimum 2.50 m clear height below mezzanine. Floor area up to 1,000 m² per level.',
                ],
                'sort_order' => 3, 'published' => true, 'featured' => false,
            ],

            // ── Cat 2: Escaliers métalliques (id=2) ──
            [
                'product_category_id' => 2,
                'slug'     => 'escalier-helicoidal-acier',
                'slug_en'  => 'helical-steel-staircase',
                'name'     => ['fr' => 'Escalier hélicoïdal acier', 'en' => 'Helical Steel Staircase'],
                'short_description' => [
                    'fr' => 'Escalier hélicoïdal en acier sur mesure — structure centrale ou limons courbes, marches en tôle larmée ou bois.',
                    'en' => 'Custom helical steel staircase — central column or curved strings, chequerplate or timber treads.',
                ],
                'full_description' => [
                    'fr' => '<p>L\'escalier hélicoïdal acier allie esthétique contemporaine et compacité. La structure en acier brut, laqué ou galvanisé est fabriquée sur mesure selon le diamètre souhaité et la hauteur à franchir. Les marches peuvent être en tôle larmée, caillebotis, verre ou bois massif. Chaque escalier est livré en kit prémonté pour faciliter l\'installation sur chantier. Conformité NF P 01-013.</p>',
                    'en' => '<p>The helical steel staircase combines contemporary aesthetics with a compact footprint. The structure — in raw, powder-coated or galvanised steel — is custom-fabricated to the required diameter and floor-to-floor height. Treads are available in chequerplate, open-grid grating, glass or solid timber. Each staircase is delivered as a pre-assembled kit for straightforward on-site installation. Compliant with NF P 01-013.</p>',
                ],
                'features' => [
                    'fr' => '<ul><li>Diamètre 120 à 200 cm</li><li>Marches tôle, caillebotis, bois ou verre</li><li>Finition laquée RAL ou galvanisée</li><li>Kit prémonté en atelier</li><li>Conformité NF P 01-013</li></ul>',
                    'en' => '<ul><li>Diameter 120 to 200 cm</li><li>Chequerplate, open-grid, timber or glass treads</li><li>RAL powder coat or galvanised finish</li><li>Pre-assembled kit delivery</li><li>NF P 01-013 compliant</li></ul>',
                ],
                'materials' => ['fr' => 'Acier S235, option inox 304/316.', 'en' => 'S235 steel, option 304/316 stainless steel.'],
                'specifications' => [
                    'fr' => 'Hauteur à franchir jusqu\'à 5 m. Charge par marche 200 kg. Nez de marche antidérapant.',
                    'en' => 'Floor-to-floor height up to 5 m. Per-tread load 200 kg. Anti-slip nosing.',
                ],
                'sort_order' => 1, 'published' => true, 'featured' => true,
            ],
            [
                'product_category_id' => 2,
                'slug'     => 'escalier-droit-industriel',
                'slug_en'  => 'straight-industrial-staircase',
                'name'     => ['fr' => 'Escalier droit industriel', 'en' => 'Straight Industrial Staircase'],
                'short_description' => [
                    'fr' => 'Escalier droit acier pour usage industriel — limons UPN, marches caillebotis, conformité ERP.',
                    'en' => 'Straight steel staircase for industrial use — UPN stringers, open-grid treads, ERP compliant.',
                ],
                'full_description' => [
                    'fr' => '<p>Conçu pour les environnements industriels exigeants, l\'escalier droit en acier offre robustesse et durabilité. Les limons sont en profilé UPN ou plat soudé, les marches en caillebotis galvanisé 30×100 avec nez antidérapant. L\'ensemble est livré grenaillé et primé, ou galvanisé à chaud selon l\'environnement d\'utilisation. Garde-corps intégré conforme au code du travail.</p>',
                    'en' => '<p>Designed for demanding industrial environments, the straight steel staircase delivers robustness and longevity. Stringers are UPN section or welded flat plate; treads are 30×100 galvanised open-grid grating with anti-slip nosing. The assembly is delivered shot-blasted and primed, or hot-dip galvanised depending on the service environment. Integrated handrail compliant with labour code requirements.</p>',
                ],
                'features' => [
                    'fr' => '<ul><li>Limons UPN ou plat soudé</li><li>Marches caillebotis galvanisé</li><li>Garde-corps code du travail</li><li>Galvanisation à chaud disponible</li><li>Paliers intermédiaires sur demande</li></ul>',
                    'en' => '<ul><li>UPN or welded flat-plate stringers</li><li>Galvanised open-grid treads</li><li>Labour-code-compliant handrail</li><li>Hot-dip galvanising available</li><li>Intermediate landings on request</li></ul>',
                ],
                'materials' => ['fr' => 'Acier S235, galvanisation Z275 ou thermolaquage.', 'en' => 'S235 steel, Z275 galvanising or powder coating.'],
                'specifications' => ['fr' => 'Largeur 80 à 120 cm. Inclinaison 30° à 45°. Charge 400 kg/m².', 'en' => 'Width 80 to 120 cm. Pitch 30° to 45°. Live load 400 kg/m².'],
                'sort_order' => 2, 'published' => true, 'featured' => false,
            ],
            [
                'product_category_id' => 2,
                'slug'     => 'escalier-cremaillere-design',
                'slug_en'  => 'design-sawtooth-staircase',
                'name'     => ['fr' => 'Escalier crémaillère design', 'en' => 'Design Sawtooth Staircase'],
                'short_description' => [
                    'fr' => 'Escalier crémaillère acier à limon central ou latéral — marches bois ou métal, esthétique épurée.',
                    'en' => 'Steel sawtooth staircase with central or side stringer — timber or metal treads, clean minimalist aesthetic.',
                ],
                'full_description' => [
                    'fr' => '<p>L\'escalier crémaillère, ou escalier à limon central, est la référence des intérieurs contemporains et des lofts industriels. Le limon en acier plat ou tube carré est découpé et soudé avec soin pour créer une silhouette aérienne. Les marches en chêne massif, béton reconstitué ou acier laqué viennent compléter le design. Chaque pièce est fabriquée sur mesure et livrée prête à poser.</p>',
                    'en' => '<p>The sawtooth staircase — with its central or side spine stringer — is the defining feature of contemporary interiors and industrial lofts. The flat-plate or square-tube steel stringer is precision-cut and welded to create an airy silhouette. Treads in solid oak, reconstituted concrete or powder-coated steel complete the design. Each piece is custom-fabricated and delivered ready to install.</p>',
                ],
                'features' => [
                    'fr' => '<ul><li>Limon plat ou tube carré acier</li><li>Marches chêne, béton ou acier laqué</li><li>Finition brute, laquée RAL ou patinée</li><li>Garde-corps verre ou câbles disponible</li><li>Plans 3D fournis avant fabrication</li></ul>',
                    'en' => '<ul><li>Flat-plate or square-tube steel stringer</li><li>Oak, concrete or powder-coated treads</li><li>Raw, RAL powder coat or patinated finish</li><li>Glass or cable railing available</li><li>3D drawings provided before fabrication</li></ul>',
                ],
                'materials' => ['fr' => 'Acier S235 laqué ou brut + marches chêne/béton.', 'en' => 'S235 steel powder-coated or raw + oak / concrete treads.'],
                'specifications' => ['fr' => 'Contre-marches ouvertes ou fermées. Largeur 80–120 cm. Nez de marche anti-glisse intégré.', 'en' => 'Open or closed risers. Width 80–120 cm. Integrated anti-slip tread nosing.'],
                'sort_order' => 3, 'published' => true, 'featured' => false,
            ],

            // ── Cat 3: Garde-corps & rampes (id=3) ──
            [
                'product_category_id' => 3,
                'slug'     => 'garde-corps-inox-304',
                'slug_en'  => 'stainless-steel-railing-304',
                'name'     => ['fr' => 'Garde-corps inox 304', 'en' => 'Stainless Steel Railing 304'],
                'short_description' => [
                    'fr' => 'Garde-corps en inox 304 brossé — remplissage câbles, vitrage ou barreaux, intérieur et extérieur.',
                    'en' => 'Brushed 304 stainless steel railing — cable, glass or bar infill, for indoor and outdoor use.',
                ],
                'full_description' => [
                    'fr' => '<p>Le garde-corps inox 304 est la référence pour les environnements exposés à l\'humidité et les espaces intérieurs design. La main courante tube rond Ø42,4 et les poteaux Ø48,3 sont assemblés par colliers ou soudures TIG meulées. Le remplissage câble inox Ø8 ou vitrage feuilleté sécurit 10,76 mm offre légèreté visuelle et conformité NF P 01-012. Livré en kits ou posé, selon la configuration du chantier.</p>',
                    'en' => '<p>The 304 stainless steel railing is the benchmark for moisture-exposed environments and design interiors. The Ø42.4 round-tube handrail and Ø48.3 posts are assembled with collars or ground-down TIG welds. Ø8 stainless cable infill or 10.76 mm laminated safety glass provides a light, open aesthetic while meeting NF P 01-012 requirements. Supplied as a kit or installed, depending on site configuration.</p>',
                ],
                'features' => [
                    'fr' => '<ul><li>Inox 304 brossé ou poli miroir</li><li>Remplissage câble, verre ou barreaux</li><li>Conforme NF P 01-012</li><li>Fixation platine, pince verre ou poteau encastré</li><li>Hauteur 1,00 m ou 1,10 m selon usage</li></ul>',
                    'en' => '<ul><li>Brushed or mirror-polished 304 stainless</li><li>Cable, glass or bar infill</li><li>NF P 01-012 compliant</li><li>Base plate, glass clamp or core-drilled fixing</li><li>Height 1.00 m or 1.10 m to specification</li></ul>',
                ],
                'materials' => ['fr' => 'Inox AISI 304 ou 316 pour environnement marin.', 'en' => 'AISI 304 stainless steel or 316 for marine environments.'],
                'specifications' => ['fr' => 'Hauteur 1,00–1,10 m. Résistance 1 kN/m. Câbles pretendus Ø8 mm, espacement max 120 mm.', 'en' => 'Height 1.00–1.10 m. Resistance 1 kN/m. Ø8 mm pre-tensioned cables, max 120 mm spacing.'],
                'sort_order' => 1, 'published' => true, 'featured' => true,
            ],
            [
                'product_category_id' => 3,
                'slug'     => 'main-courante-acier-laque',
                'slug_en'  => 'powder-coated-steel-handrail',
                'name'     => ['fr' => 'Main courante acier laqué', 'en' => 'Powder-Coated Steel Handrail'],
                'short_description' => [
                    'fr' => 'Main courante et garde-corps acier thermolaqué RAL — design industriel, finition durable, montage rapide.',
                    'en' => 'Powder-coated steel handrail and railing in RAL finish — industrial design, durable coating, rapid assembly.',
                ],
                'full_description' => [
                    'fr' => '<p>Le garde-corps acier thermolaqué s\'impose dans les projets à budget maîtrisé sans sacrifier l\'esthétique. Poteaux ronds ou carrés, main courante plate ou profilée, remplissage lisse ou ajouré : les combinaisons sont infinies. Le thermolaquage appliqué par nos ateliers résiste aux UV, aux chocs et aux variations de température. Disponible dans toute la gamme RAL ou teinte spéciale.</p>',
                    'en' => '<p>The powder-coated steel railing is the natural choice for budget-conscious projects that refuse to compromise on aesthetics. Round or square posts, flat or profiled handrail, solid or perforated infill — the combinations are endless. The powder coating applied in our workshops is UV, impact and temperature-change resistant. Available across the full RAL range or to a custom colour.</p>',
                ],
                'features' => [
                    'fr' => '<ul><li>Poteaux ronds ou carrés acier</li><li>Thermolaquage RAL ou teinte spéciale</li><li>Remplissage barreaux, tôle perforée ou lisse</li><li>Soudure MIG de qualité</li><li>Marquage CE disponible</li></ul>',
                    'en' => '<ul><li>Round or square steel posts</li><li>RAL or custom colour powder coating</li><li>Bar, perforated or solid panel infill</li><li>Quality MIG welding</li><li>CE marking available</li></ul>',
                ],
                'materials' => ['fr' => 'Acier S235 thermolaqué polyester. Épaisseur revêtement 60–80 µm.', 'en' => 'S235 steel polyester powder coat. Coating thickness 60–80 µm.'],
                'specifications' => ['fr' => 'Hauteur 1,00–1,20 m. Entraxe poteaux max 1,50 m. Résistance 0,5–1 kN/m.', 'en' => 'Height 1.00–1.20 m. Post centres max 1.50 m. Resistance 0.5–1 kN/m.'],
                'sort_order' => 2, 'published' => true, 'featured' => false,
            ],
            [
                'product_category_id' => 3,
                'slug'     => 'garde-corps-verre-metal',
                'slug_en'  => 'glass-metal-railing',
                'name'     => ['fr' => 'Garde-corps verre et métal', 'en' => 'Glass and Metal Railing'],
                'short_description' => [
                    'fr' => 'Garde-corps à remplissage vitrage feuilleté sécurit — structure acier ou inox, vue dégagée garantie.',
                    'en' => 'Laminated safety glass infill railing — steel or stainless structure, unobstructed sightlines.',
                ],
                'full_description' => [
                    'fr' => '<p>Le garde-corps verre et métal est la solution la plus élégante pour préserver les vues tout en assurant la sécurité. La structure peut être en acier laqué noir ou en inox brossé. Le vitrage est en verre feuilleté sécurit 10,76 mm ou 12,76 mm selon la hauteur et la charge. La fixation s\'effectue par pinces inox ou profilés aluminium encastrés. Idéal pour les terrasses, mezzanines et séparations intérieures haut de gamme.</p>',
                    'en' => '<p>The glass and metal railing is the most elegant solution for preserving views while ensuring safety. The structure can be in black powder-coated steel or brushed stainless. Glass is 10.76 mm or 12.76 mm laminated safety glazing, selected to height and load requirements. Fixings are stainless clamps or recessed aluminium profiles. Ideal for terraces, mezzanines and high-specification interior partitions.</p>',
                ],
                'features' => [
                    'fr' => '<ul><li>Verre feuilleté sécurit 10,76 ou 12,76 mm</li><li>Structure acier laqué ou inox 304/316</li><li>Pinces verre ou profilé encastré</li><li>Main courante plate ou ronde</li><li>Conforme NF P 01-012</li></ul>',
                    'en' => '<ul><li>10.76 or 12.76 mm laminated safety glass</li><li>Powder-coated steel or 304/316 stainless structure</li><li>Glass clamps or recessed profile</li><li>Flat or round-tube handrail</li><li>NF P 01-012 compliant</li></ul>',
                ],
                'materials' => ['fr' => 'Verre feuilleté VSG. Structure acier S235 ou inox 304.', 'en' => 'VSG laminated glass. S235 steel or 304 stainless structure.'],
                'specifications' => ['fr' => 'Hauteur 1,00–1,10 m. Résistance horizontale 1 kN/m. Panneaux verre max 1,20×1,00 m.', 'en' => 'Height 1.00–1.10 m. Horizontal resistance 1 kN/m. Max glass panel 1.20×1.00 m.'],
                'sort_order' => 3, 'published' => true, 'featured' => false,
            ],

            // ── Cat 4: Menuiseries métalliques (id=4) ──
            [
                'product_category_id' => 4,
                'slug'     => 'porte-entree-acier-blindee',
                'slug_en'  => 'security-steel-entrance-door',
                'name'     => ['fr' => "Porte d'entrée acier blindée", 'en' => 'Security Steel Entrance Door'],
                'short_description' => [
                    'fr' => "Porte d'entrée acier blindée sur mesure — résistance effraction BP2/BP3, isolation thermique et acoustique.",
                    'en' => 'Custom security steel entrance door — BP2/BP3 burglary resistance, thermal and acoustic insulation.',
                ],
                'full_description' => [
                    'fr' => "<p>Nos portes d'entrée acier blindées combinent sécurité, isolation et design. Le vantail acier 2 mm est renforcé par une structure intérieure en profilés à froid avec remplissage laine de roche. La serrurerie multipoints et la quincaillerie de haute sécurité sont intégrées en usine. Finition thermolaquage RAL ou aspect bois. Conforme aux exigences BP2 et BP3 selon configuration.</p>",
                    'en' => "<p>Our security steel entrance doors combine safety, insulation and design. The 2 mm steel leaf is reinforced by an internal cold-formed section frame with mineral wool infill. Multi-point locking and high-security hardware are factory-fitted. Finish in RAL powder coat or wood-effect. BP2 and BP3 burglary resistance compliant depending on configuration.</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Acier 2 mm + armature interne</li><li>Résistance BP2 / BP3</li><li>Serrure multipoints certifiée A2P</li><li>Isolation Rw ≥ 38 dB</li><li>Seuil à rupture de pont thermique</li></ul>',
                    'en' => '<ul><li>2 mm steel + internal armature</li><li>BP2 / BP3 resistance rating</li><li>A2P-certified multi-point lock</li><li>Sound insulation Rw ≥ 38 dB</li><li>Thermal-break threshold</li></ul>',
                ],
                'materials' => ['fr' => 'Acier galvanisé 2 mm, laine de roche 40 mm, joints EPDM.', 'en' => '2 mm galvanised steel, 40 mm mineral wool, EPDM seals.'],
                'specifications' => ['fr' => "Dimensions standard 90×215 cm ou sur mesure. Poids 80–120 kg. Classement AEV sur demande.", 'en' => 'Standard 90×215 cm or custom size. Weight 80–120 kg. AEV rating on request.'],
                'sort_order' => 1, 'published' => true, 'featured' => false,
            ],
            [
                'product_category_id' => 4,
                'slug'     => 'baie-vitree-acier-rupture-pont-thermique',
                'slug_en'  => 'steel-glazed-door-thermal-break',
                'name'     => ['fr' => 'Baie vitrée acier à rupture de pont thermique', 'en' => 'Steel Glazed Opening with Thermal Break'],
                'short_description' => [
                    'fr' => 'Baie vitrée coulissante ou fixe en profilé acier RPT — double vitrage argon, design industriel, performance RE2020.',
                    'en' => 'Sliding or fixed steel glazed opening with thermal-break profiles — argon double glazing, industrial design, RE2020 performance.',
                ],
                'full_description' => [
                    'fr' => "<p>La baie vitrée en profilé acier à rupture de pont thermique répond aux exigences de la réglementation RE2020 tout en conservant l'esthétique industrielle des profils acier fins. Disponible en version fixe, coulissante ou à galandage, elle accepte des vitrages jusqu'à 44 mm (triple vitrage). Idéale pour les extensions de maison, les verrières de toit ou les fronts de boutique design.</p>",
                    'en' => "<p>The thermal-break steel profile glazed opening meets RE2020 regulatory requirements while preserving the industrial aesthetic of slim steel sections. Available as fixed, sliding or pocket-door, it accepts glazing units up to 44 mm (triple glazing). Ideal for house extensions, overhead glazing or design shopfronts.</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Profilé acier RPT ≥ 10 mm</li><li>Double ou triple vitrage jusqu\'à 44 mm</li><li>Uw ≤ 1,4 W/m².K</li><li>Coulissant, fixe ou galandage</li><li>Certifié NF Fenêtre / CSTB</li></ul>',
                    'en' => '<ul><li>Steel RPT profile ≥ 10 mm</li><li>Double or triple glazing up to 44 mm</li><li>Uw ≤ 1.4 W/m².K</li><li>Sliding, fixed or pocket door</li><li>NF Window / CSTB certified</li></ul>',
                ],
                'materials' => ['fr' => 'Profilé acier RPT, vitrage double argon 4/16/4 Low-E.', 'en' => 'Thermal-break steel profile, 4/16/4 argon Low-E double glazing.'],
                'specifications' => ['fr' => 'Largeur jusqu\'à 5 m, hauteur jusqu\'à 3 m. Couleurs RAL standard.', 'en' => 'Width up to 5 m, height up to 3 m. Standard RAL colours.'],
                'sort_order' => 2, 'published' => true, 'featured' => true,
            ],
            [
                'product_category_id' => 4,
                'slug'     => 'pergola-bioclimatique-acier',
                'slug_en'  => 'bioclimatic-steel-pergola',
                'name'     => ['fr' => 'Pergola bioclimatique acier', 'en' => 'Bioclimatic Steel Pergola'],
                'short_description' => [
                    'fr' => 'Pergola bioclimatique en acier galvanisé — lames orientables aluminium, capteur de pluie, moteur silencieux.',
                    'en' => 'Bioclimatic steel pergola with aluminium louvre blades — rain sensor, silent motor, full outdoor comfort.',
                ],
                'full_description' => [
                    'fr' => "<p>La pergola bioclimatique acier est la solution idéale pour aménager une terrasse couverte à l'année. La structure porteuse acier galvanisé Ø80×80 mm est habillée de lames aluminium orientables à 0°/120° motorisées. Un capteur de pluie ferme automatiquement les lames en cas d'intempérie. L'éclairage LED intégré et les options de fermetures latérales complètent le confort de l'espace.</p>",
                    'en' => "<p>The bioclimatic steel pergola is the ideal solution for a year-round covered terrace. The Ø80×80 mm galvanised steel load-bearing structure is fitted with motorised aluminium louvre blades, adjustable from 0° to 120°. A rain sensor automatically closes the louvres in wet weather. Integrated LED lighting and optional side closures complete the comfort of the space.</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Structure acier galvanisé Ø80×80</li><li>Lames aluminium motorisées 0°–120°</li><li>Capteur pluie intégré</li><li>LED intégré option</li><li>Fermetures latérales verre ou toile option</li></ul>',
                    'en' => '<ul><li>Ø80×80 galvanised steel structure</li><li>Motorised aluminium louvres 0°–120°</li><li>Integrated rain sensor</li><li>Integrated LED option</li><li>Glass or fabric side closures option</li></ul>',
                ],
                'materials' => ['fr' => 'Structure acier S235 galvanisé, lames aluminium 6060 T5.', 'en' => 'Galvanised S235 steel structure, 6060 T5 aluminium louvres.'],
                'specifications' => ['fr' => 'Portée jusqu\'à 6 m. Profondeur jusqu\'à 4 m. Charge neige 75 kg/m².', 'en' => 'Span up to 6 m. Depth up to 4 m. Snow load 75 kg/m².'],
                'sort_order' => 3, 'published' => true, 'featured' => false,
            ],

            // ── Cat 5: Bardages & façades (id=5) ──
            [
                'product_category_id' => 5,
                'slug'     => 'bardage-tole-perforee',
                'slug_en'  => 'perforated-metal-cladding',
                'name'     => ['fr' => 'Bardage tôle perforée', 'en' => 'Perforated Sheet Cladding'],
                'short_description' => [
                    'fr' => 'Bardage façade en tôle perforée acier ou aluminium — design sur mesure, perforation paramétrique, ventilation naturelle.',
                    'en' => 'Perforated steel or aluminium façade cladding — custom design, parametric perforation pattern, natural ventilation.',
                ],
                'full_description' => [
                    'fr' => "<p>Le bardage tôle perforée offre un habillage de façade à la fois fonctionnel et esthétique. La perforation est paramétrable (ronde, carrée, oblongue, sur motif) pour créer des effets de lumière et filtrer les vues. La tôle acier galvanisé ou aluminium est pliée et fixée sur une ossature secondaire. Idéal pour les parkings couverts, entrepôts, équipements publics et immeubles tertiaires.</p>",
                    'en' => "<p>Perforated sheet cladding provides a façade finish that is both functional and visually striking. The perforation pattern (round, square, oblong or bespoke motif) is fully customisable to create light effects and filtered views. Galvanised steel or aluminium sheet is folded and fixed to a secondary framework. Ideal for car parks, warehouses, public facilities and commercial buildings.</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Perforation ronde, carrée ou sur motif</li><li>Tôle acier galvanisé ou aluminium</li><li>Thermolaquage RAL ou anodisation</li><li>Ossature secondaire acier galvanisé</li><li>Ventilation naturelle intégrée</li></ul>',
                    'en' => '<ul><li>Round, square or bespoke perforation</li><li>Galvanised steel or aluminium sheet</li><li>RAL powder coat or anodised finish</li><li>Galvanised secondary framework</li><li>Integrated natural ventilation</li></ul>',
                ],
                'materials' => ['fr' => 'Tôle acier galvanisé 1,5–3 mm ou aluminium 2–4 mm.', 'en' => '1.5–3 mm galvanised steel or 2–4 mm aluminium sheet.'],
                'specifications' => ['fr' => 'Panneaux jusqu\'à 3×1,5 m. Taux de perforation 20–60 %. Finition RAL ou anodisation 20 µm.', 'en' => 'Panels up to 3×1.5 m. Perforation rate 20–60 %. RAL or 20 µm anodising finish.'],
                'sort_order' => 1, 'published' => true, 'featured' => false,
            ],
            [
                'product_category_id' => 5,
                'slug'     => 'facade-corten',
                'slug_en'  => 'corten-steel-facade',
                'name'     => ['fr' => 'Façade acier Corten', 'en' => 'Corten Steel Façade'],
                'short_description' => [
                    'fr' => 'Bardage et façade en acier Corten autopatinable — esthétique rouille naturelle, résistance sans entretien.',
                    'en' => 'Self-weathering Corten steel cladding and façade — natural rust aesthetic, maintenance-free durability.',
                ],
                'full_description' => [
                    'fr' => "<p>L'acier Corten (ou acier patinable) développe naturellement une couche d'oxyde protectrice qui stoppe la corrosion et lui confère son aspect rouille caractéristique. Utilisé en bardage de façade, il nécessite zéro peinture et zéro entretien. Les panneaux sont découpés au jet d'eau ou au laser pour créer des effets graphiques. Très utilisé en architecture contemporaine, musées et monuments.</p>",
                    'en' => "<p>Corten (weathering steel) develops a self-protecting oxide layer that halts corrosion and produces its characteristic rust-red appearance. Used as façade cladding, it requires zero paint and zero maintenance. Panels are waterjet- or laser-cut to create graphic effects. Widely used in contemporary architecture, museums and landmark buildings.</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Acier patinable S355J2W+N</li><li>Découpe laser ou jet d\'eau</li><li>Zéro peinture, zéro entretien</li><li>Durabilité 50+ ans en environnement non marin</li><li>Panneaux sur mesure</li></ul>',
                    'en' => '<ul><li>S355J2W+N weathering steel</li><li>Laser or waterjet cut</li><li>Zero paint, zero maintenance</li><li>50+ year durability in non-marine environments</li><li>Custom panel sizes</li></ul>',
                ],
                'materials' => ['fr' => 'Acier patinable S355J2W (Corten A/B), épaisseur 3–8 mm.', 'en' => 'Weathering steel S355J2W (Corten A/B), 3–8 mm thickness.'],
                'specifications' => ['fr' => 'Panneaux jusqu\'à 3 m. Fixation invisible ou visible. Rejets de condensation à prévoir sur toiture.', 'en' => 'Panels up to 3 m. Concealed or exposed fixing. Condensation run-off to be designed on roof applications.'],
                'sort_order' => 2, 'published' => true, 'featured' => true,
            ],
            [
                'product_category_id' => 5,
                'slug'     => 'bardage-zinc',
                'slug_en'  => 'zinc-wall-cladding',
                'name'     => ['fr' => 'Bardage zinc', 'en' => 'Zinc Wall Cladding'],
                'short_description' => [
                    'fr' => 'Bardage façade zinc naturel ou prépatiné — tasseaux, bacs à joint debout ou écailles, durabilité 80 ans.',
                    'en' => 'Natural or pre-patinated zinc wall cladding — batten, standing seam or fish-scale pattern, 80-year durability.',
                ],
                'full_description' => [
                    'fr' => "<p>Le zinc naturel ou prépatiné s'impose comme un matériau de façade premium, alliant légèreté, élégance et longévité. Nos ateliers produisent des bacs à joint debout, des bardages à tasseaux, ou des écailles sur mesure dans les teintes naturelles (anthracite, ardoise, bleu-gris). La durée de vie garantie dépasse 80 ans en milieu non agressif.</p>",
                    'en' => "<p>Natural or pre-patinated zinc is a premium façade material combining lightness, elegance and longevity. Our workshops produce standing-seam panels, batten-fixed cladding or custom fish-scale tiles in natural finishes (anthracite, slate, blue-grey). Guaranteed service life exceeds 80 years in non-aggressive environments.</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Zinc naturel ou prépatiné (anthracite, ardoise)</li><li>Joint debout, tasseaux ou écailles</li><li>Durabilité 80+ ans</li><li>Entretien nul</li><li>Recyclable à 100 %</li></ul>',
                    'en' => '<ul><li>Natural or pre-patinated zinc (anthracite, slate)</li><li>Standing seam, batten or fish-scale</li><li>80+ year durability</li><li>Maintenance-free</li><li>100% recyclable</li></ul>',
                ],
                'materials' => ['fr' => 'Zinc VMzinc® ou Rheinzink® 0,7–1,0 mm.', 'en' => 'VMzinc® or Rheinzink® 0.7–1.0 mm.'],
                'specifications' => ['fr' => 'Lés jusqu\'à 6 m de long. Teintes : naturel, quartz, anthracite, ardoise. Pose en couverture ou façade.', 'en' => 'Strips up to 6 m. Finishes: natural, quartz, anthracite, slate. Roof or wall application.'],
                'sort_order' => 3, 'published' => true, 'featured' => false,
            ],

            // ── Cat 6: Verrières & cloisons (id=6) ──
            [
                'product_category_id' => 6,
                'slug'     => 'verriere-atelier-interieure',
                'slug_en'  => 'indoor-workshop-glazed-roof',
                'name'     => ['fr' => 'Verrière atelier intérieure', 'en' => 'Indoor Workshop Glazed Partition'],
                'short_description' => [
                    'fr' => 'Verrière atelier intérieure en profilé acier fin — style industriel authentique, vitrage simple ou feuilleté.',
                    'en' => 'Indoor workshop-style glazed partition in slim steel profiles — authentic industrial look, single or laminated glass.',
                ],
                'full_description' => [
                    'fr' => "<p>La verrière atelier intérieure est l'élément signature du style industriel et des lofts contemporains. Nos profilés acier tube carré 20×20 ou 40×40 soudés créent le quadrillage caractéristique, habillé de vitrage simple, feuilleté ou verre cathédrale. Disponible en cloison de séparation, pignon ou porte atelier. Finition brute, peinture ardoise ou laquage RAL.</p>",
                    'en' => "<p>The indoor workshop partition is the defining element of industrial style and contemporary loft interiors. Our 20×20 or 40×40 square-tube steel profiles, welded to create the characteristic grid, are glazed with clear, laminated or cathedral glass. Available as room divider, gable end or hinged door. Raw, slate paint or RAL powder-coat finish.</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Profilés tube carré 20×20 ou 40×40 mm</li><li>Soudure TIG pleine pénétration</li><li>Vitrage simple 4 mm ou feuilleté 6,38 mm</li><li>Porte atelier intégrée possible</li><li>Finition brute, ardoise ou RAL</li></ul>',
                    'en' => '<ul><li>20×20 or 40×40 mm square tube profiles</li><li>Full-penetration TIG welding</li><li>4 mm clear or 6.38 mm laminated glass</li><li>Integrated hinged door option</li><li>Raw, slate or RAL finish</li></ul>',
                ],
                'materials' => ['fr' => 'Acier S235 tube carré soudé. Vitrage clair ou feuilleté.', 'en' => 'Welded S235 square steel tube. Clear or laminated glass.'],
                'specifications' => ['fr' => 'Hauteur max 3,5 m. Largeur max 6 m. Épaisseur cloison 60–80 mm.', 'en' => 'Max height 3.5 m. Max width 6 m. Partition thickness 60–80 mm.'],
                'sort_order' => 1, 'published' => true, 'featured' => true,
            ],
            [
                'product_category_id' => 6,
                'slug'     => 'cloison-verre-style-industriel',
                'slug_en'  => 'industrial-glass-partition-wall',
                'name'     => ['fr' => 'Cloison verre style industriel', 'en' => 'Industrial-Style Glass Partition Wall'],
                'short_description' => [
                    'fr' => 'Cloison amovible ou fixe en verre et métal — séparation bureau ou espace de vie, acoustique Rw ≥ 38 dB.',
                    'en' => 'Fixed or demountable glass and metal partition — office or living space divider, acoustic rating Rw ≥ 38 dB.',
                ],
                'full_description' => [
                    'fr' => "<p>La cloison verre et métal style industriel sépare les espaces sans les fermer. La structure aluminium ou acier thermolaqué reçoit des panneaux de verre double feuilleté 10,76 mm pour une isolation acoustique Rw ≥ 38 dB. La version amovible s'adapte à l'évolution des besoins. Disponible avec ou sans porte, en hauteur sol plafond ou partielle.</p>",
                    'en' => "<p>The industrial-style glass and metal partition divides spaces without enclosing them. The powder-coated aluminium or steel frame accepts 10.76 mm double laminated glass panels for Rw ≥ 38 dB acoustic performance. The demountable version adapts as needs evolve. Available with or without door, full-height or partial.</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Verre double feuilleté 10,76 mm</li><li>Isolation acoustique Rw ≥ 38 dB</li><li>Version fixe ou amovible</li><li>Hauteur sol-plafond ou partielle</li><li>Porte intégrée option</li></ul>',
                    'en' => '<ul><li>10.76 mm double laminated glass</li><li>Acoustic rating Rw ≥ 38 dB</li><li>Fixed or demountable version</li><li>Full-height or partial height</li><li>Integrated door option</li></ul>',
                ],
                'materials' => ['fr' => 'Cadre acier ou aluminium thermolaqué. Verre feuilleté PVB 10,76 mm.', 'en' => 'Powder-coated steel or aluminium frame. 10.76 mm PVB laminated glass.'],
                'specifications' => ['fr' => 'Hauteur jusqu\'à 3,5 m. Largeur modules 60–120 cm. Joints EPDM silence phonique.', 'en' => 'Height up to 3.5 m. Module width 60–120 cm. EPDM acoustic seals.'],
                'sort_order' => 2, 'published' => true, 'featured' => false,
            ],
            [
                'product_category_id' => 6,
                'slug'     => 'verriere-toit-terrasse-verre',
                'slug_en'  => 'glass-rooftop-skylight-structure',
                'name'     => ['fr' => 'Verrière de toit / toiture vitrée', 'en' => 'Glass Rooftop Skylight Structure'],
                'short_description' => [
                    'fr' => 'Structure métallique vitrée pour toiture-terrasse ou extension — lumière zénithale maximale, étanchéité garantie.',
                    'en' => 'Metal-framed glazed roof structure for flat roofs or extensions — maximum overhead light with guaranteed weatherproofing.',
                ],
                'full_description' => [
                    'fr' => "<p>La verrière de toit transforme un espace intérieur en apportant la lumière naturelle par le dessus. La structure acier thermolaqué ou aluminium laqué est conçue pour résister aux charges de neige et de vent selon les normes NF EN 1991. Le vitrage feuilleté de sécurité VSG 33.2 ou 44.4 mm garantit la protection en cas de bris. Étanchéité assurée par joints EPDM haute performance. Idéale pour extensions, atriums, vérandas et toitures de patio.</p>",
                    'en' => "<p>The glass rooftop structure floods an interior with natural overhead light. The powder-coated steel or lacquered aluminium frame is engineered to withstand snow and wind loads to NF EN 1991 standards. VSG 33.2 or 44.4 mm safety laminated glass ensures protection in the event of breakage. EPDM high-performance seals guarantee weatherproofing. Ideal for extensions, atriums, verandas and patio roofs.</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Lumière zénithale naturelle maximale</li><li>Vitrage feuilleté de sécurité VSG</li><li>Étanchéité joints EPDM</li><li>Résistance neige et vent NF EN 1991</li><li>Finition thermolaquage RAL ou aluminium laqué</li></ul>',
                    'en' => '<ul><li>Maximum natural overhead daylight</li><li>VSG safety laminated glass</li><li>EPDM weatherproof seals</li><li>Snow and wind resistance to NF EN 1991</li><li>RAL powder coat or lacquered aluminium finish</li></ul>',
                ],
                'materials' => ['fr' => 'Profilés acier ou aluminium thermolaqué. Vitrage feuilleté VSG 33.2–44.4 mm.', 'en' => 'Powder-coated steel or aluminium profiles. VSG 33.2–44.4 mm laminated glass.'],
                'specifications' => ['fr' => 'Surface max 40 m². Pente min 3°. Charge neige jusqu\'à 100 kg/m². Classement acoustique RW ≥ 35 dB.', 'en' => 'Max area 40 m². Min pitch 3°. Snow load up to 100 kg/m². Acoustic rating RW ≥ 35 dB.'],
                'sort_order' => 3, 'published' => true, 'featured' => false,
            ],

            // ── Cat 7: Portails & clôtures (id=7) ──
            [
                'product_category_id' => 7,
                'slug'     => 'portail-coulissant-motorise',
                'slug_en'  => 'motorised-sliding-gate',
                'name'     => ['fr' => 'Portail coulissant motorisé', 'en' => 'Motorised Sliding Gate'],
                'short_description' => [
                    'fr' => 'Portail coulissant acier motorisé — autoportant ou sur rail, télécommande, interphone et badge option.',
                    'en' => 'Motorised steel sliding gate — self-supporting or rail-guided, remote control, intercom and badge access option.',
                ],
                'full_description' => [
                    'fr' => "<p>Le portail coulissant motorisé est la solution reine pour les accès privés et industriels. La structure acier galvanisé est remplie de barreaux ronds, de tôle pleine ou de lattis selon le niveau de sécurité souhaité. Le moteur brushless intégré permet une ouverture silencieuse et rapide. Compatible avec tous les systèmes de contrôle d'accès (interphone, badge, télécommande).</p>",
                    'en' => "<p>The motorised sliding gate is the prime solution for private and industrial access points. The galvanised steel structure is filled with round bars, solid plate or lattice depending on the required security level. The integrated brushless motor delivers silent, rapid opening. Compatible with all access control systems (intercom, badge reader, remote control).</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Structure acier galvanisé à chaud</li><li>Moteur brushless silencieux</li><li>Autoportant ou sur rail bas</li><li>Remplissage barreaux, tôle ou lattis</li><li>Compatible contrôle d\'accès</li></ul>',
                    'en' => '<ul><li>Hot-dip galvanised steel structure</li><li>Silent brushless motor</li><li>Self-supporting or bottom-rail guided</li><li>Bar, solid plate or lattice infill</li><li>Access control compatible</li></ul>',
                ],
                'materials' => ['fr' => 'Acier S235 galvanisé à chaud + thermolaquage RAL.', 'en' => 'Hot-dip galvanised S235 steel + RAL powder coat.'],
                'specifications' => ['fr' => 'Largeur jusqu\'à 8 m. Poids jusqu\'à 600 kg. Vitesse ouverture 0,2–0,4 m/s. IP44.', 'en' => 'Width up to 8 m. Weight up to 600 kg. Opening speed 0.2–0.4 m/s. IP44.'],
                'sort_order' => 1, 'published' => true, 'featured' => false,
            ],
            [
                'product_category_id' => 7,
                'slug'     => 'cloture-panneaux-rigides',
                'slug_en'  => 'rigid-panel-fence',
                'name'     => ['fr' => 'Clôture panneaux rigides galvanisés', 'en' => 'Galvanised Rigid Panel Fence'],
                'short_description' => [
                    'fr' => 'Clôture panneaux rigides acier galvanisé et plastifiés — résistance anti-effraction, durabilité 25 ans.',
                    'en' => 'Galvanised and plastic-coated rigid steel panel fence — anti-climb, 25-year durability.',
                ],
                'full_description' => [
                    'fr' => "<p>La clôture à panneaux rigides galvanisés est la solution de sécurité périmétrique la plus répandue pour les sites industriels, commerciaux et collectifs. Les panneaux en fil soudé Ø5 mm sont galvanisés à chaud et plastifiés vert ou anthracite. Les poteaux H60 ou H80 acier sont scellés ou bridés selon le type de sol. Disponible en hauteur 1,20 à 2,50 m.</p>",
                    'en' => "<p>The galvanised rigid panel fence is the most widely used perimeter security solution for industrial, commercial and public sites. Ø5 mm welded wire panels are hot-dip galvanised and PVC-coated in green or anthracite. H60 or H80 steel posts are grouted or bolted depending on the substrate. Available in heights from 1.20 to 2.50 m.</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Fil soudé Ø5 mm galvanisé + plastifié</li><li>Panneaux 2,50 m × 1,03 à 2,53 m</li><li>Poteaux H60/H80 scellés ou bridés</li><li>Couleur vert 6005 ou anthracite 7016</li><li>Certifié NF EN 13200</li></ul>',
                    'en' => '<ul><li>Ø5 mm galvanised + PVC-coated welded wire</li><li>Panels 2.50 m × 1.03 to 2.53 m</li><li>H60/H80 posts, grouted or bolted</li><li>Green 6005 or anthracite 7016 colour</li><li>NF EN 13200 certified</li></ul>',
                ],
                'materials' => ['fr' => 'Fil acier Z275 galvanisé, plastifié PVC 0,5 mm.', 'en' => 'Z275 galvanised steel wire, 0.5 mm PVC coating.'],
                'specifications' => ['fr' => 'Hauteur 1,20–2,50 m. Maille 200×50 mm. Résistance 1 kN/m.', 'en' => 'Height 1.20–2.50 m. Mesh 200×50 mm. Resistance 1 kN/m.'],
                'sort_order' => 2, 'published' => true, 'featured' => false,
            ],
            [
                'product_category_id' => 7,
                'slug'     => 'portail-battant-fer-forge',
                'slug_en'  => 'wrought-iron-swing-gate',
                'name'     => ['fr' => 'Portail battant fer forgé', 'en' => 'Wrought Iron Swing Gate'],
                'short_description' => [
                    'fr' => 'Portail battant en fer forgé sur mesure — volutes, pointes de flèche, finition époxy qualité bâtiment haut de gamme.',
                    'en' => 'Custom wrought iron swing gate — scrolls, spear tops, high-quality building-grade epoxy finish.',
                ],
                'full_description' => [
                    'fr' => "<p>Le portail en fer forgé reste le choix de référence pour les propriétés de prestige et les bâtiments patrimoniaux. Chaque portail est forgé à la main par nos artisans bulgares, avec volutes, barreaux torsadés et pointes de flèche selon le dessin convenu. La finition époxy qualité architecturale résiste aux UV et aux intempéries pendant 15+ années. Livraison avec ferrures de pose et motorisation possible.</p>",
                    'en' => "<p>The wrought iron gate remains the reference choice for prestige properties and heritage buildings. Each gate is hand-forged by our Bulgarian craftsmen, with scrolls, twisted bars and spear tops to the agreed design. The architectural-grade epoxy finish resists UV and weathering for 15+ years. Delivered with installation hardware; motorisation available.</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Fer forgé travaillé à la main</li><li>Motifs sur mesure : volutes, torsades, flèches</li><li>Finition époxy antiouille UV + noir forge</li><li>Pentures et gonds galvanisés inclus</li><li>Motorisation disponible</li></ul>',
                    'en' => '<ul><li>Hand-forged wrought iron</li><li>Custom patterns: scrolls, twists, spear tops</li><li>UV anti-rust epoxy + forge black finish</li><li>Galvanised hinges and posts included</li><li>Motorisation available</li></ul>',
                ],
                'materials' => ['fr' => 'Fer forgé rond et plat, époxy qualité architecturale 80 µm.', 'en' => 'Round and flat wrought iron, 80 µm architectural-grade epoxy.'],
                'specifications' => ['fr' => 'Largeur 2×0,90 m à 2×2,00 m. Hauteur 1,20–2,50 m. Poids indicatif 80–200 kg.', 'en' => 'Width 2×0.90 m to 2×2.00 m. Height 1.20–2.50 m. Indicative weight 80–200 kg.'],
                'sort_order' => 3, 'published' => true, 'featured' => false,
            ],

            // ── Cat 8: Terrasses & balcons (id=8) ──
            [
                'product_category_id' => 8,
                'slug'     => 'terrasse-suspendue-acier-bois',
                'slug_en'  => 'suspended-steel-timber-deck',
                'name'     => ['fr' => 'Terrasse suspendue acier et bois', 'en' => 'Suspended Steel and Timber Deck'],
                'short_description' => [
                    'fr' => 'Terrasse suspendue sur structure acier galvanisé — lames bois ou composite, garde-corps intégré, charge 350 kg/m².',
                    'en' => 'Suspended deck on galvanised steel structure — timber or composite decking, integrated railing, 350 kg/m² load.',
                ],
                'full_description' => [
                    'fr' => "<p>La terrasse suspendue acier-bois allie la robustesse de l'acier à l'esthétique chaleureuse du bois. La structure porteuse est en profilés HEA/IPE galvanisés à chaud, fixée sur la façade par des pattes d'ancrage chimique. Les solives secondaires en acier galvanisé reçoivent les lames en bois exotique (cumaru, ipé) ou en composite sans entretien. Le garde-corps inox câbles est livré en kit.</p>",
                    'en' => "<p>The suspended steel-timber deck combines the structural performance of steel with the warmth of timber. The primary frame is hot-dip galvanised HEA/IPE sections, anchored to the façade with chemical fixings. Secondary galvanised joists support hardwood (cumaru, ipé) or maintenance-free composite deck boards. A stainless cable railing is supplied as a kit.</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Structure HEA/IPE galvanisé à chaud</li><li>Ancrage chimique façade ou poteaux fondation</li><li>Lames bois exotique ou composite</li><li>Garde-corps câbles inox inclus</li><li>Charge utile 350 kg/m²</li></ul>',
                    'en' => '<ul><li>Hot-dip galvanised HEA/IPE structure</li><li>Chemical anchor or post foundation fixing</li><li>Hardwood or composite deck boards</li><li>Stainless cable railing included</li><li>Live load 350 kg/m²</li></ul>',
                ],
                'materials' => ['fr' => 'Acier S235 galvanisé. Lames cumaru FSC ou composite Trex/Fiberon.', 'en' => 'Galvanised S235 steel. FSC cumaru or Trex/Fiberon composite boards.'],
                'specifications' => ['fr' => 'Surface jusqu\'à 60 m². Porte-à-faux max 3 m. Inclinaison drainage 1–2 %.', 'en' => 'Area up to 60 m². Max cantilever 3 m. Drainage slope 1–2 %.'],
                'sort_order' => 1, 'published' => true, 'featured' => true,
            ],
            [
                'product_category_id' => 8,
                'slug'     => 'balcon-rapporte-sur-consoles',
                'slug_en'  => 'cantilevered-balcony-on-brackets',
                'name'     => ['fr' => 'Balcon rapporté sur consoles acier', 'en' => 'Cantilevered Balcony on Steel Brackets'],
                'short_description' => [
                    'fr' => 'Balcon rapporté sur consoles acier soudées — béton coulé ou plancher acier, garde-corps verre ou inox.',
                    'en' => 'Add-on balcony on welded steel brackets — in-situ concrete or steel floor, glass or stainless railing.',
                ],
                'full_description' => [
                    'fr' => "<p>Le balcon rapporté sur consoles acier est la solution idéale pour ajouter un espace extérieur sur une façade existante sans démolition. Les consoles HEB ou UPN sont ancrées chimiquement dans le mur porteur et dimensionnées au calcul. Le plancher peut être en tôle larmée, caillebotis ou dalle béton. Le garde-corps verre feuilleté ou inox câbles est conforme NF P 01-012.</p>",
                    'en' => "<p>The add-on balcony on steel brackets is the ideal solution for adding outdoor living space to an existing façade without demolition. HEB or UPN brackets are chemically anchored to the load-bearing wall and structurally calculated. The floor plate can be chequerplate, open-grid grating or a concrete slab. The laminated glass or stainless cable railing meets NF P 01-012.</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Consoles HEB ou UPN calculées</li><li>Ancrage chimique mur porteur</li><li>Plancher tôle, caillebotis ou béton</li><li>Garde-corps verre ou câbles</li><li>Note de calcul structure fournie</li></ul>',
                    'en' => '<ul><li>Structurally calculated HEB or UPN brackets</li><li>Chemical anchor into load-bearing wall</li><li>Chequerplate, open-grid or concrete floor</li><li>Glass or cable railing</li><li>Structural calculation note supplied</li></ul>',
                ],
                'materials' => ['fr' => 'Acier S355 galvanisé. Ancrage chimique Hilti ou Fischer.', 'en' => 'Galvanised S355 steel. Hilti or Fischer chemical anchors.'],
                'specifications' => ['fr' => 'Porte-à-faux 1–2,5 m. Surface 2–20 m². Charge 350 kg/m².', 'en' => 'Cantilever 1–2.5 m. Area 2–20 m². Load 350 kg/m².'],
                'sort_order' => 2, 'published' => true, 'featured' => false,
            ],
            [
                'product_category_id' => 8,
                'slug'     => 'passerelle-inter-batiments',
                'slug_en'  => 'inter-building-walkway',
                'name'     => ['fr' => 'Passerelle inter-bâtiments', 'en' => 'Inter-Building Walkway Bridge'],
                'short_description' => [
                    'fr' => 'Passerelle acier entre deux bâtiments — structure HEA/IPE, plancher caillebotis, garde-corps réglementaire.',
                    'en' => 'Steel walkway bridge between two buildings — HEA/IPE structure, open-grid floor, code-compliant railing.',
                ],
                'full_description' => [
                    'fr' => "<p>La passerelle inter-bâtiments permet de relier deux bâtiments ou deux niveaux d'un site industriel sans emprunter le sol. La structure autoportante en acier est calculée pour une charge d'exploitation de 350 à 500 kg/m². Le plancher caillebotis galvanisé assure l'écoulement des eaux pluviales. Les garde-corps conformes au code du travail assurent la sécurité des utilisateurs.</p>",
                    'en' => "<p>The inter-building walkway connects two buildings or two levels of an industrial site without routing via ground level. The self-supporting steel structure is calculated for 350 to 500 kg/m² live load. The galvanised open-grid floor deck allows rainwater drainage. Labour-code-compliant railings ensure user safety throughout.</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Structure HEA/IPE autoportante</li><li>Charge utile 350–500 kg/m²</li><li>Plancher caillebotis galvanisé</li><li>Garde-corps code du travail</li><li>Joints de dilatation si portée > 12 m</li></ul>',
                    'en' => '<ul><li>Self-supporting HEA/IPE structure</li><li>Live load 350–500 kg/m²</li><li>Galvanised open-grid deck</li><li>Labour-code-compliant railing</li><li>Expansion joints for spans > 12 m</li></ul>',
                ],
                'materials' => ['fr' => 'Acier S235/S355 galvanisé. Caillebotis 30×100 galvanisé.', 'en' => 'Galvanised S235/S355 steel. 30×100 galvanised open-grid grating.'],
                'specifications' => ['fr' => 'Portée 3–15 m. Largeur utile 1,00–1,50 m. Fabrication aux normes européennes.', 'en' => 'Span 3–15 m. Clear width 1.00–1.50 m. Manufactured to European standards.'],
                'sort_order' => 3, 'published' => true, 'featured' => false,
            ],

            // ── Cat 9: Racks & shelters (id=9) ──
            [
                'product_category_id' => 9,
                'slug'     => 'rack-stockage-industriel',
                'slug_en'  => 'industrial-storage-rack',
                'name'     => ['fr' => 'Rack de stockage industriel', 'en' => 'Industrial Storage Rack'],
                'short_description' => [
                    'fr' => 'Rayonnage à palettes acier — charge jusqu\'à 3 000 kg/niveau, certifié RACKING (EN 15512), assemblage sans soudure.',
                    'en' => 'Steel pallet rack — up to 3,000 kg per level, RACKING (EN 15512) certified, weld-free assembly.',
                ],
                'full_description' => [
                    'fr' => "<p>Nos racks de stockage industriel sont conçus pour les entrepôts logistiques et les ateliers de production. Les montants profilés en acier haute résistance S320GD permettent des charges par niveau de 1 000 à 3 000 kg. L'assemblage bolté sans soudure facilite l'installation et l'évolution du système. Certifié selon EN 15512 et FEM 10.2.02. Livré peint RAL 5015 bleu ou RAL 2004 orange selon standard entrepôt.</p>",
                    'en' => "<p>Our industrial storage racks are designed for logistics warehouses and production workshops. S320GD high-strength steel uprights support per-level loads of 1,000 to 3,000 kg. Bolted weld-free assembly simplifies installation and system reconfiguration. Certified to EN 15512 and FEM 10.2.02. Delivered painted RAL 5015 blue or RAL 2004 orange to warehouse standard.</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Montants acier S320GD</li><li>Charge par niveau 1 000–3 000 kg</li><li>Assemblage bolté sans soudure</li><li>Certifié EN 15512 / FEM 10.2.02</li><li>Plinthe anti-choc option</li></ul>',
                    'en' => '<ul><li>S320GD steel uprights</li><li>Per-level load 1,000–3,000 kg</li><li>Bolted weld-free assembly</li><li>EN 15512 / FEM 10.2.02 certified</li><li>Anti-crash barrier option</li></ul>',
                ],
                'materials' => ['fr' => 'Acier S320GD thermolaqué. Lisses LNP ou LPS.', 'en' => 'Powder-coated S320GD steel. LNP or LPS beams.'],
                'specifications' => ['fr' => 'Hauteur jusqu\'à 12 m. Profondeur 0,80–1,10 m. Travée 2,70–3,60 m. Allée 2,70–3,60 m.', 'en' => 'Height up to 12 m. Depth 0.80–1.10 m. Bay width 2.70–3.60 m. Aisle 2.70–3.60 m.'],
                'sort_order' => 1, 'published' => true, 'featured' => false,
            ],
            [
                'product_category_id' => 9,
                'slug'     => 'bac-retention-acier-ce',
                'slug_en'  => 'steel-spill-containment-bund',
                'name'     => ['fr' => 'Bac de rétention acier CE', 'en' => 'CE-Marked Steel Spill Containment Bund'],
                'short_description' => [
                    'fr' => 'Bac de rétention acier soudé marquage CE — capacité 100 à 10 000 L, grille caillebotis galvanisé, conforme ICPE.',
                    'en' => 'CE-marked welded steel spill containment bund — 100 to 10,000 L capacity, galvanised grid, ICPE compliant.',
                ],
                'full_description' => [
                    'fr' => "<p>Nos bacs de rétention acier sont fabriqués en tôle acier soudée avec fond plat renforcé. Ils répondent aux exigences réglementaires ICPE pour le stockage de liquides dangereux (hydrocarbures, solvants, produits chimiques). La capacité de rétention est égale à 100 % du plus grand contenant ou 50 % du volume total stocké. Marquage CE selon NF EN 1825. Grille de caillebotis galvanisé fournie.</p>",
                    'en' => "<p>Our steel containment bunds are fabricated from welded steel plate with a reinforced flat base. They meet ICPE regulatory requirements for the storage of hazardous liquids (hydrocarbons, solvents, chemicals). Retention capacity equals 100% of the largest container or 50% of total stored volume. CE marked to NF EN 1825. Galvanised open-grid grating supplied.</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Tôle acier soudée fond plat</li><li>Marquage CE NF EN 1825</li><li>Capacité 100–10 000 L</li><li>Grille caillebotis galvanisé</li><li>Conforme ICPE / ATEX option</li></ul>',
                    'en' => '<ul><li>Welded steel plate flat base</li><li>CE marked NF EN 1825</li><li>Capacity 100–10,000 L</li><li>Galvanised open-grid grating</li><li>ICPE compliant / ATEX option</li></ul>',
                ],
                'materials' => ['fr' => 'Tôle acier S235 5 mm + revêtement époxy bicomposant résistant aux hydrocarbures.', 'en' => '5 mm S235 steel plate + two-component epoxy hydrocarbon-resistant coating.'],
                'specifications' => ['fr' => 'Dimensions sur mesure. Épaisseur paroi 5 mm. Revêtement époxy bicouche. Test étanchéité 24 h fourni.', 'en' => 'Custom dimensions. 5 mm wall thickness. Dual-layer epoxy coating. 24 h leak test certificate supplied.'],
                'sort_order' => 2, 'published' => true, 'featured' => false,
            ],
            [
                'product_category_id' => 9,
                'slug'     => 'shelter-technique-acier',
                'slug_en'  => 'technical-steel-shelter',
                'name'     => ['fr' => 'Shelter technique acier', 'en' => 'Technical Steel Shelter'],
                'short_description' => [
                    'fr' => 'Abri technique modulaire acier galvanisé — accueil groupes électrogènes, transformateurs, TGBT, locaux techniques.',
                    'en' => 'Modular galvanised steel technical shelter — housing for generators, transformers, LV switchgear and plant rooms.',
                ],
                'full_description' => [
                    'fr' => "<p>Le shelter technique acier est un bâtiment modulaire préfabriqué destiné à protéger et abriter des équipements techniques sensibles. La structure en acier galvanisé à chaud reçoit une enveloppe de bardage acier ou sandwich PIR selon les exigences thermiques. Les ouvertures (portes, ventilations, passes-câbles) sont positionnées sur plan. Livraison clé en main sur chantier en une seule pièce.</p>",
                    'en' => "<p>The technical steel shelter is a prefabricated modular building designed to house and protect sensitive technical equipment. The hot-dip galvanised steel frame is clad in steel or PIR sandwich panels to meet thermal requirements. Openings (doors, ventilation louvres, cable entries) are positioned to plan. Turnkey delivery to site in a single piece.</p>",
                ],
                'features' => [
                    'fr' => '<ul><li>Structure acier galvanisé à chaud</li><li>Bardage acier ou sandwich PIR</li><li>Porte double sécurité avec serrure spécialisée</li><li>Ventilation naturelle ou forcée</li><li>Câblage et éclairage option</li></ul>',
                    'en' => '<ul><li>Hot-dip galvanised steel structure</li><li>Steel or PIR sandwich panel cladding</li><li>Double-security door with specialist lock</li><li>Natural or forced ventilation</li><li>Wiring and lighting option</li></ul>',
                ],
                'materials' => ['fr' => 'Structure acier S235 Z350. Bardage acier prélaqué ou sandwich PIR 40 mm.', 'en' => 'S235 Z350 steel structure. Pre-painted steel cladding or 40 mm PIR sandwich panel.'],
                'specifications' => ['fr' => 'Modules standard 3×3 m, 3×6 m, 6×6 m. Hauteur utile 2,50 m. Sur mesure possible.', 'en' => 'Standard modules 3×3 m, 3×6 m, 6×6 m. Clear internal height 2.50 m. Custom sizes available.'],
                'sort_order' => 3, 'published' => true, 'featured' => false,
            ],
        ];

        foreach ($products as $data) {
            Product::create($data);
        }

        $this->command->info('27 products seeded across 9 categories.');
    }

    // ─────────────────────────────────────────────
    // SITE SETTINGS
    // ─────────────────────────────────────────────

    private function seedSettings(): void
    {
        $settings = [
            'phone'          => '+33782085117',
            'phone_display'  => '+33 (0)7 82 08 51 17',
            'email'          => 'tsudol.fabtec@yahoo.com',
            'address'        => '1, route Neuve — 24150 St-Capraise-de-Lalinde, France',
            'company_name'   => 'fab-sourcing.fr',
            'owner_name'     => 'Thierry Sudol',
            'owner_role'     => 'Responsable commercial & marketing',
            'linkedin_url'   => '',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $this->command->info('Site settings seeded.');
    }

    // ─────────────────────────────────────────────
    // PLACEHOLDER IMAGE
    // ─────────────────────────────────────────────

    private function createPlaceholderImage(): void
    {
        $dir = public_path('images/placeholders');
        $path = $dir . '/product-placeholder.jpg';

        if (! File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        if (File::exists($path)) {
            $this->command->info('Placeholder image already exists — skipping.');
            return;
        }

        if (! function_exists('imagecreatetruecolor')) {
            // GD not available — create a minimal valid JPEG byte sequence
            $jpeg = "\xff\xd8\xff\xe0\x00\x10JFIF\x00\x01\x01\x00\x00\x01\x00\x01\x00\x00"
                  . "\xff\xdb\x00C\x00\x08\x06\x06\x07\x06\x05\x08\x07\x07\x07\t\t"
                  . "\x08\n\x0c\x14\r\x0c\x0b\x0b\x0c\x19\x12\x13\x0f\x14\x1d\x1a"
                  . "\x1f\x1e\x1d\x1a\x1c\x1c $.' \",#\x1c\x1c(7),01444\x1f'9=82<.342\x1e"
                  . "\xff\xc0\x00\x0b\x08\x00\x01\x00\x01\x01\x01\x11\x00"
                  . "\xff\xc4\x00\x1f\x00\x00\x01\x05\x01\x01\x01\x01\x01\x01\x00\x00"
                  . "\x00\x00\x00\x00\x00\x00\x01\x02\x03\x04\x05\x06\x07\x08\t\n\x0b"
                  . "\xff\xc4\x00\xb5\x10\x00\x02\x01\x03\x03\x02\x04\x03\x05\x05\x04"
                  . "\x04\x00\x00\x01}\x01\x02\x03\x00\x04\x11\x05\x12!1A\x06\x13Qa"
                  . "\xff\xda\x00\x08\x01\x01\x00\x00?\x00\xfb\xd4P\x00\x00\x00\x1f\xff\xd9";
            File::put($path, $jpeg);
        } else {
            $img = imagecreatetruecolor(800, 600);
            $bg  = imagecolorallocate($img, 241, 245, 249);   // slate-100
            $fg  = imagecolorallocate($img, 148, 163, 184);   // slate-400
            imagefill($img, 0, 0, $bg);
            // simple diagonal lines to indicate placeholder
            for ($i = -600; $i < 800; $i += 40) {
                imageline($img, $i, 0, $i + 600, 600, $fg);
            }
            $text = imagecolorallocate($img, 100, 116, 139);  // slate-500
            imagestring($img, 5, 310, 280, 'Photo à venir', $text);
            imagestring($img, 5, 320, 300, 'Image coming soon', $text);
            imagejpeg($img, $path, 80);
            imagedestroy($img);
        }

        $this->command->info('Placeholder image created at public/images/placeholders/product-placeholder.jpg');
    }
}
