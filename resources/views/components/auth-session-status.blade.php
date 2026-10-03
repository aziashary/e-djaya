@props(['status'])

@if ($status)
  <div {{ $attributes->merge(['class' => 'ui-status']) }} role="status">
    {{ $status }}
  </div>
@endif
