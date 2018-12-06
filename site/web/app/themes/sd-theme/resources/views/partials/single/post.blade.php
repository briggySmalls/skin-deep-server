@extends('layouts.content-single')
@php
assert(is_a($post, 'SkinDeep\Articles\Article'));
@endphp
{{-- Header content --}}
@section('single-header')
  {{-- Include meta (categories/title) --}}
  @include('partials/entry-meta')
  {{-- Display author(s) --}}
  @if (count($post->authors()))
    <p class="byline author vcard">
      {{ __('Written by', 'sage') }}
      @foreach ($post->authors() as $author)
        <a href="{{ get_term_link($author) }}" rel="author">{{ $author->name }}</a>{{ !$loop->last ? ", " : "" }}
      @endforeach
    </p>
  @endif
@endsection
@section('single-content')
{{-- Magazine advert --}}
@php $magazine = $post->magazine(); @endphp
@if ($magazine)
  <aside class="jumbotron">
    <div class="container">
      <div class="row">
        <div class="col-sm-8">
          <h1 class="display-4">Get the full magazine</h1>
          <p class="lead">This piece is from our print edition: {!! $magazine->title() !!}</p>
          <a class="buy-button" href="{{ $magazine->url() }}">Buy it now</a>
        </div>
        <div class="col-sm-4">
          {!! $magazine->image(
            false,
            "(max-size: " . \SkinDeep\Widgets\PostsPreview\PostsPreview::BOOTSTRAP_COLUMNS['sm'] . "px): 34vw, 100vw") !!}
        </div>
      </div>
    </div>
  </aside>
@endif
{{-- Article content --}}
<div class="entry-content">
  {{ the_content() }}
</div>
@endsection
@section('after-content')
  {{-- Footer --}}
  @php dynamic_sidebar('sidebar-post') @endphp
  <footer>
    {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
  </footer>
@endsection
