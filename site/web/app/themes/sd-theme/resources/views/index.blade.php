@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  @component('components.try-posts')
    @while (have_posts()) @php the_post() @endphp
      @include('partials.content-'.get_post_type())
    @endwhile
  @endcomponent

  {!! get_the_posts_navigation() !!}
@endsection
