{{--
  Widget output template
  --}}
@php
// Get widget settings
$column_count = $context->get_acf_field( 'sd_widget_preview_columns' );
@endphp
{{-- Display the category title --}}
<h3 class="widget-preview-title">
  <a href={{ $context->url }}>
    {{ $context->get_acf_field( 'sd_widget_preview_title' ) }}
  </a>
</h3>
{{-- Create grid of posts --}}
<div class="container-fluid">
  <div class="row">
    @foreach ($context->posts as $article)
      <div class="col-md-{{ 12 / $column_count }}">
        {{-- Wrap the entire card in a link --}}
        <a href={{ get_permalink($article) }}>
          <div class="card">
            {{-- Display featured image/video --}}
            @include('partials.featured-media')
            <div class="card-body">
              {{-- Insert main body --}}
              @include('partials.body')
            </div>
          </div>
        </a>
      </div>
      {{-- Break row on final item --}}
      @if (($loop->index + 1) % $column_count == 0)
        <div class="w-100"></div>
      @endif
    @endforeach
  </div>
</div>
<div class="widget-preview-more"><a href="{{ $context->url }}">See more articles</a></div>
