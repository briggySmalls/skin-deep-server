@extends('layouts.app')

@section('content')
  @include('partials.page-header')
  @include('partials.no-posts')

  @while (have_posts()) @php the_post() @endphp
    @php
    $event = new SkinDeep\Events\Event(get_post());
    @endphp
      {{-- Wrap the entire card in a link --}}
      <a href={{ $event->url() }}>
        @include('partials.content-archive-sd-event')
      </a>
  @endwhile

  {!! get_the_posts_navigation() !!}
@endsection
