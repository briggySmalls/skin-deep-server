@php
$sizes = [
  'xs' => 'sm',
  'sm' => 'md',
  'md' => 'lg',
  'lg' => 'xl',
  'xl' => null,
];
@endphp
@if (($loop->index + 1) % $count == 0)
  <div class="w-100 d-none d-{{ $size }}-block @if($sizes[$size]) d-{{ $sizes[$size] }}-none @endif"></div>
@endif
