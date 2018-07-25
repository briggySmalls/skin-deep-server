<div class="page-header">
  @if (is_category())
    <figure>
      {!! Category::image( 'large' ) !!}
    </figure>
  @endif
  <h1>{!! App::title() !!}</h1>
  @if (is_category())
    {!! Category::description() !!}
  @endif
</div>
