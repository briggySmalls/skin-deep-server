@php
assert(is_a($post, 'SkinDeep\Events\Event'));
@endphp
<header>
  <h1 class="entry-title">{!! $post->title() !!}</h1>
  @include('partials.components.event-details', [$show_facebook = true])
</header>
<div class="entry-content">
  {{ the_content() }}
</div>
@php comments_template('/partials/comments.blade.php') @endphp
