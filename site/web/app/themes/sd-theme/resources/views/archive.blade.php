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
  <div class="container-fluid">
    <div class="row">
      @php $i = 0; @endphp
      @while (have_posts()) @php the_post() @endphp
        @php $i++; @endphp
        <div class="col-md-4">
          {{-- Wrap the entire card in a link --}}
          <a href={{ get_permalink() }}>
            @include('partials.content-archive')
          </a>
        </div>
        {{-- Break row on final item --}}
        @if (($i + 1) % 4 == 0)
          <div class="w-100"></div>
        @endif
      @endwhile
    </div>
  </div>

  {!! get_the_posts_navigation() !!}
@endsection
