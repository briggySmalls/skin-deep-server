@extends('layouts.content-single')
@php
assert(is_a($post, 'SkinDeep\Events\Event'));
@endphp

@section('single-header')
  <h1 class="entry-title">{!! $post->title() !!}</h1>
  @include('partials.components.event-details', [$full_event_details = true])
  @include('partials.components.socials')
@endsection

@section('single-content')
  {{ the_content() }}
@endsection
