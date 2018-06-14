{{--
  Template Name: Widget Page
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    @include('partials.page-header')
    @include('partials.content-page')
  @endwhile
  @php dynamic_sidebar('sidebar-focus') @endphp
@endsection
