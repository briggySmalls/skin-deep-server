@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  @if (!have_posts())
    <div class="alert alert-warning">
      {{ __('Sorry, no results were found.', 'sage') }}
    </div>
    {!! get_search_form(false) !!}
  @endif

  @while (have_posts()) @php the_post() @endphp
      {{-- Wrap the entire card in a link --}}
      <a href={{ get_permalink() }}>
        @include('partials.content-archive-sd-event')
      </a>
  @endwhile

  {!! get_the_posts_navigation() !!}
@endsection
