{{-- Display cateogories & date --}}
<div class="entry-meta">
  @include('partials.components.categories')
  {{-- Display time --}}
  <time class="updated" datetime="{{ get_post_time('c', true) }}">{{ get_the_date("d.m.y") }}</time>
  {{-- Display title --}}
  <h1 class="entry-title">{!! $post->title() !!}</h1>
</div>
