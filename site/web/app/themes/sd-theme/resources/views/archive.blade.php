@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  @component('components.try-posts')
    {{-- Display posts in a grid --}}
    @include('plugin::partials.loop-grid')
  @endcomponent

  {{-- Display navigation --}}
  {!! get_the_posts_navigation() !!}
@endsection
