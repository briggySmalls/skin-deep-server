{{-- Display cateogories & date --}}
<div class="entry-meta">
  @php
  $categories = $post->categories();
  @endphp
  {{-- Display categories --}}
  @if ($categories)
    <span class="entry-categories">
    @foreach ($categories as $category)
      <a href="{{ get_term_link($category) }}">{{ $category->name }}</a>
    @endforeach
    </span>
  @endif
  {{-- Display time --}}
  <time class="updated" datetime="{{ get_post_time('c', true) }}">{{ get_the_date("d.m.y") }}</time>
  {{-- Display title --}}
  <h1 class="entry-title">{!! $post->title() !!}</h1>
</div>
