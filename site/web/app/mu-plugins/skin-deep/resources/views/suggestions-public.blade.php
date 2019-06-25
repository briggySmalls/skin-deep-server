{{--
  Widget output template
  --}}
@if (is_single())
  <aside class="suggestions jumbotron">
    <h3>Want to read more?</h3>
    @if ($authors)
      <p class="lead">
        More articles written by
        @foreach ($authors as $author)
          <a href="{{ get_term_link($author) }}">
            {{ $author->name }}</a>@if (!$loop->last)<span>,&#32;</span>@endif
        @endforeach
      </p>
    @endif
    @if ($categories)
      <p class="lead">
        More articles in categories:
        @foreach ($categories as $category)
            <a href="{{ get_category_link($category) }}">
              {{ $category->name }}</a>@if (!$loop->last)<span>,&#32;</span>@endif
        @endforeach
      </p>
    @endif
  </aside>
@endif
