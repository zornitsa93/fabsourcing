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
                'content_fr' => '<h2>Éditeur du site</h2><p>Fab Sourcing — Thierry Sudol<br>1, route Neuve<br>24150 Saint-Capraise-de-Lalinde<br>France</p><p>Email : tsudol.fabtec@yahoo.com<br>Téléphone : +33 (0)7 82 08 51 17</p><h2>Hébergement</h2><p>Ce site est hébergé par un prestataire établi au sein de l\'Union Européenne.</p><h2>Propriété intellectuelle</h2><p>L\'ensemble des contenus présents sur ce site (textes, images, graphiques) sont la propriété exclusive de Fab Sourcing, sauf mention contraire. Toute reproduction, diffusion ou utilisation sans autorisation préalable est interdite.</p><h2>Responsabilité</h2><p>Fab Sourcing s\'efforce de fournir des informations exactes et à jour. Toutefois, la société ne saurait être tenue responsable des erreurs ou omissions, ni de tout préjudice découlant de l\'utilisation des informations publiées.</p>',
                'content_en' => '<h2>Publisher</h2><p>Fab Sourcing — Thierry Sudol<br>1, route Neuve<br>24150 Saint-Capraise-de-Lalinde<br>France</p><p>Email: tsudol.fabtec@yahoo.com<br>Phone: +33 (0)7 82 08 51 17</p><h2>Hosting</h2><p>This website is hosted by a service provider established within the European Union.</p><h2>Intellectual Property</h2><p>All content on this site (texts, images, graphics) is the exclusive property of Fab Sourcing unless otherwise stated. Any reproduction, distribution or use without prior authorisation is prohibited.</p><h2>Liability</h2><p>Fab Sourcing strives to provide accurate and up-to-date information. However, the company cannot be held liable for errors or omissions, or for any damage resulting from the use of published information.</p>',
            ],
            [
                'slug'       => 'politique-de-confidentialite',
                'title_fr'   => 'Politique de confidentialité',
                'title_en'   => 'Privacy Policy',
                'content_fr' => '<h2>Données collectées</h2><p>Lorsque vous soumettez le formulaire de contact, nous collectons les informations suivantes : nom, entreprise (optionnel), adresse email, numéro de téléphone (optionnel), et votre message. Ces données sont utilisées uniquement pour répondre à votre demande.</p><h2>Conservation des données</h2><p>Vos données sont conservées pendant une durée maximale de 3 ans à compter de votre dernière interaction avec Fab Sourcing. Vous pouvez demander leur suppression à tout moment.</p><h2>Partage des données</h2><p>Fab Sourcing ne vend, ne loue ni ne partage vos données personnelles avec des tiers, sauf obligation légale.</p><h2>Vos droits</h2><p>Conformément au RGPD, vous disposez d\'un droit d\'accès, de rectification, de suppression et d\'opposition concernant vos données personnelles. Pour exercer ces droits, contactez-nous à : tsudol.fabtec@yahoo.com</p><h2>Cookies</h2><p>Ce site n\'utilise pas de cookies publicitaires. Des cookies techniques strictement nécessaires au fonctionnement du site peuvent être utilisés.</p>',
                'content_en' => '<h2>Data Collected</h2><p>When you submit the contact form, we collect the following information: name, company (optional), email address, phone number (optional), and your message. This data is used solely to respond to your request.</p><h2>Data Retention</h2><p>Your data is retained for a maximum of 3 years from your last interaction with Fab Sourcing. You may request deletion at any time.</p><h2>Data Sharing</h2><p>Fab Sourcing does not sell, rent or share your personal data with third parties, except as required by law.</p><h2>Your Rights</h2><p>In accordance with GDPR, you have the right of access, rectification, deletion and objection regarding your personal data. To exercise these rights, contact us at: tsudol.fabtec@yahoo.com</p><h2>Cookies</h2><p>This site does not use advertising cookies. Strictly necessary technical cookies for site operation may be used.</p>',
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
