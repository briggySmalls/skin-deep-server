@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  @if (!have_posts())
    <div class="alert alert-warning">
      {{ __('Sorry, no results were found.', 'sage') }}
    </div>
    {!! get_search_form(false) !!}
  @endif

  {{-- Display posts in a grid --}}
  @include('articles::partials.loop-grid')

  {{-- Display navigation --}}
  {!! get_the_posts_navigation() !!}
@endsection
