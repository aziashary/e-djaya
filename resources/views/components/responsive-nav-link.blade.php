@props(['active'])

@php
$classes = ($active ?? false) ? 'ui-nav-link-active w-full' : 'ui-nav-link w-full';
@endphp

<a {{ $attributes->merge(['class' => $classes, 'aria-current' => ($active ?? false) ? 'page' : null]) }}>
  {{ $slot }}
</a>
