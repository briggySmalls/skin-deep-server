{{--
  Widget output template
  --}}
@if ($context->article)
  <div class="jumbotron">
    <h3>Want to read more?</h3>
    <p class="lead">
      More articles written by
      @foreach ($context->authors() as $author)
        <a href="{{ get_term_link($author) }}">{{ $author->name }}</a>
      @endforeach
    </p>
    <p class="lead">
      @if ($context->categories())
        More articles in categories:
        @foreach ($context->categories() as $category)
            <a href="{{ get_category_link($category) }}">
              {{ $category->name }}
            </a>
        @endforeach
      @endif
    </p>
  </div>
@endif
