<div class="card">
  <div class="row no-gutters">
    <div class="col-md-4">
      {{-- Display featured image/video --}}
      {{-- Feature video takes precedence --}}
      @if (SinglePost::hasFeaturedVideo())
        <div class="embed-responsive embed-responsive-16by9">
          {!! SinglePost::video() !!}
        </div>
      {{-- Otherwise display the featured image --}}
      @elseif (SinglePost::hasFeaturedImage())
        {!! SinglePost::image() !!}
      @endif
    </div>
    <div class="col content">
      {{-- Display category --}}
      <h5>
        @foreach (get_the_category(get_post()) as $category)
          {{ $category->name }}
        @endforeach
      </h5>
      {{-- Display title --}}
      <h4 class="card-title">{{ get_post()->post_title }}</h4>
      {{-- Display excerpt --}}
      @php $details = $sd_events_api->getEventDetails(); @endphp
      @if ($details->start_time)
        <p class="card-text">{{ SingleSdEvent::getDisplayTime($details) }}</p>
      @endif
    </div>
  </div>
</div>
