{{--
  Widget output template
  --}}
@php
// Get widget settings
$column_count = $context->get_acf_field( 'sd_widget_preview_columns' );
@endphp
{{-- Display the category title --}}
<h2 class="title">
  <a href={{ $context->url }}>
    {{ $context->get_acf_field( 'sd_widget_preview_title' ) }}
  </a>
</h2>
{{-- Create grid of posts --}}
<div class="container-fluid">
  <div class="row">
    @foreach ($context->posts as $article)
      <div class="col-md-{{ 12 / $column_count }}">
        {{-- Wrap the entire card in a link --}}
        <a href={{ get_permalink($article) }}>
          <div class="card">
            {{-- Display featured image/video --}}
            @include(
              'partials.featured-media',
              ['image_size' => 'post-thumbnail', 'image_classes' => 'card-img-top'])
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
<div class="see-more"><a href="{{ $context->url }}">See more articles</a></div>
