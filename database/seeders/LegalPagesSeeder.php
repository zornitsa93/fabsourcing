<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class LegalPagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug'       => 'mentions-legales',
                'title_fr'   => 'Mentions légales',
                'title_en'   => 'Legal Notice',
                'content_fr' => <<<'HTML'
<h2>Éditeur du site</h2>
<p>Fab Sourcing — Thierry Sudol<br>1, route Neuve<br>24150 Saint-Capraise-de-Lalinde<br>France</p>
<p>Email : thierry.sudol@fab-sourcing.fr<br>Téléphone : +33 (0)7 84 05 73 75</p>
<h2>Hébergeur</h2>
<p>[À compléter avec votre hébergeur]<br>Adresse : [À compléter]</p>
<h2>Propriété intellectuelle</h2>
<p>L'ensemble des éléments constituant le site fab-sourcing.fr (textes, graphismes, logiciels, photographies, images, vidéos, sons, plans, logos, marques, etc.) est la propriété exclusive de Fab Sourcing ou de ses partenaires. Ces éléments sont protégés par les lois françaises et internationales relatives à la propriété intellectuelle. Toute reproduction, représentation, modification, publication, adaptation de tout ou partie des éléments du site, quel que soit le moyen ou le procédé utilisé, est interdite, sauf autorisation écrite préalable de Fab Sourcing.</p>
<p>Toute exploitation non autorisée du site ou de l'un quelconque des éléments qu'il contient sera considérée comme constitutive d'une contrefaçon et poursuivie conformément aux dispositions des articles L.335-2 et suivants du Code de la propriété intellectuelle.</p>
<h2>Données personnelles</h2>
<p>Conformément au Règlement Général sur la Protection des Données (RGPD) et à la loi Informatique et Libertés du 6 janvier 1978 modifiée, vous disposez d'un droit d'accès, de rectification, de suppression et de portabilité des données vous concernant, ainsi que d'un droit d'opposition et de limitation du traitement.</p>
<p>Les données collectées via le formulaire de contact sont utilisées exclusivement pour traiter votre demande et établir une relation commerciale. Elles ne sont ni cédées ni vendues à des tiers.</p>
<p>Responsable du traitement : Thierry Sudol, Fab Sourcing<br>Droit d'accès : Pour exercer vos droits, contactez Thierry Sudol par email ou par courrier à l'adresse du siège social.</p>
<h2>Cookies</h2>
<p>Le site fab-sourcing.fr utilise des cookies techniques nécessaires à son fonctionnement et des cookies de mesure d'audience (Google Analytics). Vous pouvez configurer votre navigateur pour refuser les cookies. Cela peut toutefois affecter certaines fonctionnalités du site.</p>
<h2>Limitation de responsabilité</h2>
<p>Fab Sourcing s'efforce d'assurer l'exactitude et la mise à jour des informations diffusées sur son site. Toutefois, Fab Sourcing ne peut garantir l'exactitude, la précision ou l'exhaustivité des informations mises à disposition sur le site.</p>
<p>En conséquence, Fab Sourcing décline toute responsabilité :</p>
<ul>
<li>Pour toute imprécision, inexactitude ou omission portant sur des informations disponibles sur le site</li>
<li>Pour tous dommages résultant d'une intrusion frauduleuse d'un tiers ayant entraîné une modification des informations mises à disposition sur le site</li>
<li>Et plus généralement, pour tous dommages directs ou indirects, quelles qu'en soient les causes, origines, natures ou conséquences</li>
</ul>
<h2>Droit applicable et juridiction compétente</h2>
<p>Les présentes mentions légales sont régies par le droit français. En cas de litige, les tribunaux français seront seuls compétents.</p>
<h2>Crédits photographiques</h2>
<p>Photographies industrielles : © Fab Sourcing / Partenaires<br>Icônes et éléments graphiques : [À compléter]</p>
HTML,
                'content_en' => <<<'HTML'
<h2>Site Publisher</h2>
<p>Fab Sourcing — Thierry Sudol<br>1, route Neuve<br>24150 Saint-Capraise-de-Lalinde<br>France</p>
<p>Email: thierry.sudol@fab-sourcing.fr<br>Phone: +33 (0)7 84 05 73 75</p>
<h2>Hosting</h2>
<p>[À compléter avec votre hébergeur]<br>Address: [À compléter]</p>
<h2>Intellectual Property</h2>
<p>All elements making up the fab-sourcing.fr website (texts, graphics, software, photographs, images, videos, sounds, plans, logos, trademarks, etc.) are the exclusive property of Fab Sourcing or its partners. These elements are protected by French and international intellectual property law. Any reproduction, representation, modification, publication or adaptation of all or part of the elements of the site, by whatever means or process, is prohibited without the prior written authorisation of Fab Sourcing.</p>
<p>Any unauthorised use of the site or of any of the elements it contains will be deemed to constitute an infringement and prosecuted in accordance with the provisions of Articles L.335-2 et seq. of the French Intellectual Property Code.</p>
<h2>Personal Data</h2>
<p>In accordance with the General Data Protection Regulation (GDPR) and the French Data Protection Act of 6 January 1978 as amended, you have the right to access, rectify, erase and port the data concerning you, as well as the right to object to and restrict its processing.</p>
<p>The data collected via the contact form is used exclusively to handle your request and establish a business relationship. It is neither transferred nor sold to third parties.</p>
<p>Data controller: Thierry Sudol, Fab Sourcing<br>Right of access: to exercise your rights, contact Thierry Sudol by email or by post at the registered office address.</p>
<h2>Cookies</h2>
<p>The fab-sourcing.fr website uses technical cookies necessary for its operation and audience-measurement cookies (Google Analytics). You can configure your browser to refuse cookies. This may, however, affect certain features of the site.</p>
<h2>Limitation of Liability</h2>
<p>Fab Sourcing endeavours to ensure that the information published on its site is accurate and up to date. However, Fab Sourcing cannot guarantee the accuracy, precision or completeness of the information made available on the site.</p>
<p>Consequently, Fab Sourcing disclaims all liability:</p>
<ul>
<li>For any imprecision, inaccuracy or omission relating to information available on the site</li>
<li>For any damage resulting from fraudulent intrusion by a third party leading to a modification of the information made available on the site</li>
<li>And more generally, for any direct or indirect damage, whatever its causes, origins, nature or consequences</li>
</ul>
<h2>Governing Law and Jurisdiction</h2>
<p>This legal notice is governed by French law. In the event of a dispute, the French courts shall have sole jurisdiction.</p>
<h2>Photo Credits</h2>
<p>Industrial photographs: © Fab Sourcing / Partners<br>Icons and graphic elements: [À compléter]</p>
HTML,
            ],
            [
                'slug'       => 'politique-de-confidentialite',
                'title_fr'   => 'Politique de confidentialité',
                'title_en'   => 'Privacy Policy',
                'content_fr' => '<h2>Données collectées</h2><p>Lorsque vous soumettez le formulaire de contact, nous collectons les informations suivantes : nom, entreprise (optionnel), adresse email, numéro de téléphone (optionnel), et votre message. Ces données sont utilisées uniquement pour répondre à votre demande.</p><h2>Conservation des données</h2><p>Vos données sont conservées pendant une durée maximale de 3 ans à compter de votre dernière interaction avec Fab Sourcing. Vous pouvez demander leur suppression à tout moment.</p><h2>Partage des données</h2><p>Fab Sourcing ne vend, ne loue ni ne partage vos données personnelles avec des tiers, sauf obligation légale.</p><h2>Vos droits</h2><p>Conformément au RGPD, vous disposez d\'un droit d\'accès, de rectification, de suppression et d\'opposition concernant vos données personnelles. Pour exercer ces droits, contactez-nous à : thierry.sudol@fab-sourcing.fr</p><h2>Cookies</h2><p>Ce site n\'utilise pas de cookies publicitaires. Des cookies techniques strictement nécessaires au fonctionnement du site peuvent être utilisés.</p>',
                'content_en' => '<h2>Data Collected</h2><p>When you submit the contact form, we collect the following information: name, company (optional), email address, phone number (optional), and your message. This data is used solely to respond to your request.</p><h2>Data Retention</h2><p>Your data is retained for a maximum of 3 years from your last interaction with Fab Sourcing. You may request deletion at any time.</p><h2>Data Sharing</h2><p>Fab Sourcing does not sell, rent or share your personal data with third parties, except as required by law.</p><h2>Your Rights</h2><p>In accordance with GDPR, you have the right of access, rectification, deletion and objection regarding your personal data. To exercise these rights, contact us at: thierry.sudol@fab-sourcing.fr</p><h2>Cookies</h2><p>This site does not use advertising cookies. Strictly necessary technical cookies for site operation may be used.</p>',
            ],
        ];

        foreach ($pages as $data) {
            $page = Page::firstOrNew(['slug' => $data['slug']]);
            $page->setTranslation('title',   'fr', $data['title_fr']);
            $page->setTranslation('title',   'en', $data['title_en']);
            $page->setTranslation('content', 'fr', $data['content_fr']);
            $page->setTranslation('content', 'en', $data['content_en']);
            $page->published = true;
            $page->save();
        }
    }
}
