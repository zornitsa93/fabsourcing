@props([
    'title',
    'description',
    'canonical',
    'lang',
    'hreflangFr',
    'hreflangEn' => null,
    'ogType'     => 'website',
    'ogImage'    => null,
])

@php
    $ogLocale = $lang === 'en' ? 'en_US' : 'fr_FR';
    $siteName = 'Fab Sourcing';
@endphp

<link rel="canonical" href="{{ $canonical }}">
<link rel="alternate" hreflang="fr"        href="{{ $hreflangFr }}">
@if($hreflangEn)
<link rel="alternate" hreflang="en"        href="{{ $hreflangEn }}">
@endif
<link rel="alternate" hreflang="x-default" href="{{ $hreflangFr }}">

<meta property="og:site_name"   content="{{ $siteName }}">
<meta property="og:type"        content="{{ $ogType }}">
<meta property="og:title"       content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url"         content="{{ $canonical }}">
<meta property="og:locale"      content="{{ $ogLocale }}">
@if($ogImage)
<meta property="og:image"        content="{{ $ogImage }}">
<meta property="og:image:width"  content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt"    content="{{ $title }}">
@endif

@if($ogImage)
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:image"       content="{{ $ogImage }}">
@else
<meta name="twitter:card"        content="summary">
@endif
<meta name="twitter:title"       content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
