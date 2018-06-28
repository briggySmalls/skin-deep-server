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
        @if (($loop->index + 1) % $context::POSTS_PER_ROW == 0)
          {{-- Break row on final item --}}
          <div class="w-100"></div>
        @endif
      @endforeach
    </div>
  </div>
</section>
