{{-- Display post categories --}}
@php
$categories = $post->categories();
@endphp
@if ($categories)
  {{-- Display category --}}
  <span class="categories">
    @foreach ($categories as $category)
      <a href="{{ get_category_link($category) }}">
        {{ $category->name }}</a>@if (!$loop->last)<span>,&#32;</span>@endif
    @endforeach
  </span>
@endif
