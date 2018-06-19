{{--
  Template Name: Widget Page
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    @include('partials.page-header')
    @include('partials.content-page')
  @endwhile

  <button
    class="snipcart-add-item"
    data-item-id="2"
    data-item-name="Bacon"
    data-item-price="3.00"
    data-item-weight="20"
    data-item-url="http://myapp.com/products/bacon"
    data-item-description="Some fresh bacon">
        Buy bacon
  </button>
  
  @php dynamic_sidebar('sidebar-focus') @endphp
@endsection
