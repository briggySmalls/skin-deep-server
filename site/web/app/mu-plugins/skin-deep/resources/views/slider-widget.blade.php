{{--
  Widget output template
  --}}
<div id="{{ $id }}" class="posts-slider carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    @foreach ($posts as $post)
      <div class="carousel-item @if ($loop->first) active @endif">
        {{-- Display featured image/video --}}
        <a href="{{ $post->url() }}">
        @include(
          'partials.components.featured-media',
          ['image_classes' => 'd-block w-100'])
        </a>
        <div class="carousel-caption d-none d-md-block">
          <h3>{!! $post->title() !!}</h3>
        </div>
      </div>
    @endforeach
  </div>
  <ol class="carousel-indicators">
    @for ($i = 0; $i < count( $posts ); $i++)
    <li data-target="#{{ $id }}"
        data-slide-to="{{ $i }}"
        @if ($i == 0) class="active" @endif>
    </li>
    @endfor
  </ol>
  <a class="carousel-control-prev" href="#{{ $id }}" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#{{ $id }}" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
