{{-- Display featured image/video --}}
@include(
  'articles::partials.featured-media',
  ['image_sizes' => \SkinDeep\Articles\PostsPreview::sizes($column_count)])
<div class="card-body">
  @php
  $categories = $post->categories();
  @endphp
  @if ($categories)
    {{-- Display category --}}
    <p class="category-link">
      @foreach ($categories as $category)
        <a href="{{ get_category_link($category) }}">{{ $category->name }}</a>
        @if (!$loop->last)
          {{-- Add a space --}}
          <span>&#32;</span>
        @endif
      @endforeach
    </p>
  @endif
  {{-- Display title --}}
  <h3 class="card-title">{{ $post->post_title }}</h3>
</div>
