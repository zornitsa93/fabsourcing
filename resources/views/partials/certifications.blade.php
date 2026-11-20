{{-- Certifications of the partner workshop (FABTEC Ltd.). Only what the certificates state. --}}
@php
  $fr = $lang === 'fr';
  $certs = [
    [
      'tag'   => 'EN 1090-1 · EXC3',
      'title' => $fr ? 'Contrôle de production en usine (FPC)' : 'Factory production control (FPC)',
      'desc'  => $fr
        ? 'Certificat n° 1888-CPR-078-BG. Produits de structure en acier, classe d’exécution EXC3 selon EN 1090-2, marquage CE. Soudage MAG (procédé 135), aciers du groupe 1.1.'
        : 'Certificate no. 1888-CPR-078-BG. Structural steel products, execution class EXC3 according to EN 1090-2, CE marking. MAG welding (process 135), steel group 1.1.',
      'file'  => 'fabtec-en-1090-exc3-certificate.pdf',
    ],
    [
      'tag'   => 'ISO 9001:2015',
      'title' => $fr ? 'Management de la qualité' : 'Quality management',
      'desc'  => $fr ? 'Certificat n° 21/025, délivré par NJN Ltd Bulgaria.' : 'Certificate no. 21/025, issued by NJN Ltd Bulgaria.',
      'file'  => 'fabtec-iso-9001-certificate.pdf',
    ],
    [
      'tag'   => 'ISO 14001:2015',
      'title' => $fr ? 'Management environnemental' : 'Environmental management',
      'desc'  => $fr ? 'Certificat n° 21/026, délivré par NJN Ltd Bulgaria.' : 'Certificate no. 21/026, issued by NJN Ltd Bulgaria.',
      'file'  => 'fabtec-iso-14001-certificate.pdf',
    ],
    [
      'tag'   => 'ISO 45001:2023',
      'title' => $fr ? 'Santé et sécurité au travail' : 'Occupational health and safety',
      'desc'  => $fr ? 'Certificat n° 21/027, délivré par NJN Ltd Bulgaria.' : 'Certificate no. 21/027, issued by NJN Ltd Bulgaria.',
      'file'  => 'fabtec-iso-45001-certificate.pdf',
    ],
  ];
@endphp

<section class="section-tight" style="background:#f4f6f9">
  <div class="container">
    <div class="section-head reveal">
      <div>
        <div class="eyebrow">{{ $fr ? 'Certifications' : 'Certifications' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($fr)
            Les certificats de notre<br><em>atelier partenaire</em>
          @else
            Certificates of our<br><em>partner workshop</em>
          @endif
        </h2>
      </div>
      <div class="section-head-right">
        <p class="body">
          {{ $fr
            ? 'Notre atelier partenaire FabTec (Priseltsi, région de Varna) est certifié EN 1090 jusqu’à la classe d’exécution EXC3, ainsi qu’ISO 9001, ISO 14001 et ISO 45001 pour la fabrication de machines, de composants de machines et de structures métalliques. Les certificats sont valables jusqu’au 11 avril 2027.'
            : 'Our partner workshop FabTec (Priseltsi, Varna region) is certified to EN 1090 up to execution class EXC3, as well as ISO 9001, ISO 14001 and ISO 45001 for the manufacture of machines, machine components and metal structures. The certificates are valid until 11 April 2027.' }}
        </p>
      </div>
    </div>

    <div class="client-type-grid reveal" style="grid-template-columns:repeat(auto-fit,minmax(250px,1fr))">
      @foreach($certs as $c)
        <div class="client-card" style="background:#fff">
          <p class="client-card-examples" style="margin-top:0; padding-top:0; border-top:0">{{ $c['tag'] }}</p>
          <h3 class="client-card-title">{{ $c['title'] }}</h3>
          <p class="client-card-desc">{{ $c['desc'] }}</p>
          <a class="btn-link" style="margin-top:auto" href="{{ asset('documents/certificats/' . $c['file']) }}" target="_blank" rel="noopener">
            {{ $fr ? 'Voir le certificat (PDF, en anglais)' : 'View the certificate (PDF)' }}
            <span class="arrow">→</span>
          </a>
        </div>
      @endforeach
    </div>

    <p class="body" style="margin-top:20px; font-size:13px; color:#6b7891">
      {{ $fr
        ? 'Le périmètre de la certification EN 1090 est celui indiqué sur le certificat (aciers du groupe 1.1, soudage MAG). Pour un autre matériau ou procédé, demandez-nous la confirmation avant commande.'
        : 'The scope of the EN 1090 certification is the one stated on the certificate (steel group 1.1, MAG welding). For another material or process, ask us for confirmation before ordering.' }}
    </p>
  </div>
</section>
