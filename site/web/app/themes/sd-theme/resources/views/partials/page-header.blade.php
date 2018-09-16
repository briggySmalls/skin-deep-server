<div class="page-header">
  {{-- Image --}}
  @php $image = App::image('large'); @endphp
  @if ($image)
    <figure>
      {!! $image !!}
    </figure>
  @endif
  {{-- Title --}}
  <h1>{!! App::title() !!}</h1>
  {{-- Description --}}
  @php $description = App::description(); @endphp
  @if ($description)
    <figure>
      {!! $description !!}
    </figure>
  @endif
</div>
