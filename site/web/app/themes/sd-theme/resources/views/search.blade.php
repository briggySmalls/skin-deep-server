@extends('layouts.app')

@section('content')
  @include('partials.page-header')
  @include('partials.no-posts')

  @while (have_posts()) @php the_post() @endphp
    @include('partials.content-search')
  @endwhile

  {!! get_the_posts_navigation() !!}
@endsection
