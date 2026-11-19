{{-- items: list of [name, url|null]; the last item has no url. --}}
@props(['items'])
@php
  $list = [];
  foreach (array_values($items) as $i => [$name, $url]) {
      $el = ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $name];
      if ($url) { $el['item'] = $url; }
      $list[] = $el;
  }
@endphp
<x-jsonld :data="['@type' => 'BreadcrumbList', 'itemListElement' => $list]" />
