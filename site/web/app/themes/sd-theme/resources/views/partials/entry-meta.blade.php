{{-- Display cateogories & date --}}
<div class="">
  @foreach ($article->categories() as $category)
    <a href="{{ get_term_link($category) }}">{{ $category->name }}</a>
    @if ($loop->last)
      |
    @endif
  @endforeach
  <time class="updated" datetime="{{ get_post_time('c', true) }}">{{ get_the_date("d.m.y") }}</time>
</div>
