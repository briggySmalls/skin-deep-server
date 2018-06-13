<time class="updated" datetime="{{ get_post_time('c', true) }}">{{ get_the_date() }}</time>
@php $terms = wp_get_post_terms($post->ID, 'sd-author'); @endphp
@if (count($terms))
  <p class="byline author vcard">
    {{ __('By', 'sage') }}
    @foreach ($terms as $term)
      <a href="{{ get_term_link($term) }}" rel="author">{{ $term->name }}</a>{{ !$loop->last ? ", " : "" }}
    @endforeach
  </p>
@endif
