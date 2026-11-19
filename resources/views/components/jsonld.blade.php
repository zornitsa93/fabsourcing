@props(['data'])
<script type="application/ld+json">{!! json_encode(array_merge(['@context' => 'https://schema.org'], $data), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_PRETTY_PRINT) !!}</script>
