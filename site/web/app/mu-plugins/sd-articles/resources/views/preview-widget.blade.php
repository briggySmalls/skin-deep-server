{{--
  Widget output template
  --}}
@php
// Get widget settings
$column_count = $context->getAcfField( 'sd_widget_preview_columns' );
@endphp
{{-- Display the category title --}}
<h2 class="title">
  <a href={{ $context->url }}>
    {{ $context->getAcfField( 'sd_widget_preview_title' ) }}
  </a>
</h2>
{{-- Create grid of posts --}}
@include("articles::partials.grid", ['posts' => $context->posts])
{{-- Display see more link --}}
<div class="see-more"><a href="{{ $context->url }}">See more articles</a></div>
