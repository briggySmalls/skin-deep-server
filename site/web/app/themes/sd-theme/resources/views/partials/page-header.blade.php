<div class="page-header">
  {{-- Display a category image if this is a category archive --}}
  @if (is_category())
    <figure>
      {!! Category::image( 'large' ) !!}
    </figure>
  @endif
  <h1>{!! App::title() !!}</h1>
  {{-- Display a category description if this is a category archive --}}
  @if (is_category())
    {!! Category::description() !!}
  @endif
</div>
