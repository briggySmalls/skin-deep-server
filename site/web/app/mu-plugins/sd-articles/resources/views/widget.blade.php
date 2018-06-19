{{--
  Widget output template
  --}}

<section>
  {{-- Display the category title --}}
  <h3>{{ get_field( 'sd_widget_preview_title', 'widget_' . $context->args['widget_id'] ) }}</h3>
  {{-- Create grid of posts --}}
  <div class="container-fluid">
    <div class="row">
      @foreach ($context->posts as $article)
        <div class="col-md-4">
          <a href={{ get_permalink($article) }}>
            <div class="card">
              {{-- Feature video takes precedence --}}
              @if (get_field( 'sd_featured_video', $article->ID ))
                <div class="embed-responsive embed-responsive-16by9">
                  {!! get_field('sd_featured_video', $article->ID) !!}
                </div>
              {{-- Otherwise display the featured image --}}
              @elseif (has_post_thumbnail( $article->ID ) )
                {!! get_the_post_thumbnail(
                  $article->ID,
                  'post-thumbnail',
                  ['class' => 'card-img-top img-fluid']) !!}
              @endif
              <div class="card-body">
                <h4 class="card-title">{{ $article->post_title }}</h4>
              </div>
            </div>
          </a>
        </div>
        @if (($loop->index + 1) % $context::POSTS_PER_ROW == 0)
          {{-- Break row on final item --}}
          <div class="w-100"></div>
        @endif
      @endforeach
    </div>
  </div>
</section>
