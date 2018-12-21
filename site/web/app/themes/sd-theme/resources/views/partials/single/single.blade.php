@extends('layouts.content-single')
@php
assert(is_a($post, 'SkinDeep\Articles\Post'));
@endphp

@section('single-header')
  <h2 class="entry-title"><a href="{{ get_permalink() }}">{{ get_the_title() }}</a></h2>
  @include('partials/entry-meta')
@endsection

@section('single-content')
  @php the_excerpt() @endphp
@endsection
