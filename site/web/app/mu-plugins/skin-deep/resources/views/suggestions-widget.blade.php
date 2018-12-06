{{--
  Widget output template
  --}}
@if (is_single())
  <aside class="jumbotron">
    <h3>Want to read more?</h3>
    <p class="lead">
      More articles written by
      @foreach ($authors as $author)
        <a href="{{ get_term_link($author) }}">{{ $author->name }}</a>
      @endforeach
    </p>
    <p class="lead">
      @if ($categories)
        More articles in categories:
        @foreach ($categories as $category)
            <a href="{{ get_category_link($category) }}">
              {{ $category->name }}
            </a>
        @endforeach
      @endif
    </p>
  </aside>
@endif
