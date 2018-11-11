@extends('layouts.app')

@section('content')
  @include('partials.page-header')
  @include('partials.no-posts')

  @while (have_posts()) @php the_post() @endphp
    @php
    $post = new SkinDeep\Events\Event(get_post());
    @endphp
    @include('partials.content-archive-sd-event')
  @endwhile

  {!! get_the_posts_navigation() !!}
@endsection
