@extends('layouts.app')

@section('content')
  @include('partials.page-header')
  @include('partials.no-posts')

  {{-- Display posts in a grid --}}
  @include('articles::partials.loop-grid')

  {{-- Display navigation --}}
  {!! get_the_posts_navigation() !!}
@endsection
