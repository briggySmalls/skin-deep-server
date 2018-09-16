{{-- Display cateogories & date --}}
<div class="">
  @foreach (get_the_category() as $category)
    @if (SkinDeep\Articles\Articles::is_default_category($category))
      @continue
    @endif
    <a href="{{ get_term_link($category) }}">{{ $category->name }}</a>
    @if ($loop->last)
      |
    @endif
  @endforeach
  <time class="updated" datetime="{{ get_post_time('c', true) }}">{{ get_the_date("d.m.y") }}</time>
</div>
