@if ($content->hide_section !== 'yes')
  <section id="{{ $content->id ?? '' }}" class="general-content-section py-100 max-1023:py-60 max-575:py-40 {{ $content->class ?? '' }}">
    <div class="container-fluid">
      @if (!empty($content->description))
        <div class="general-content-description">
          {!! $content->description !!}
        </div>
      @endif
    </div>
  </section>
@endif
