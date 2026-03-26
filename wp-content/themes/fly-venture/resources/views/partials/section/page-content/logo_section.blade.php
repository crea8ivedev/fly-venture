@php
  // Build section classes
  $section_classes = 'partner-logo-strip';

  if ( ! empty( $content->hide_section ) && $content->hide_section !== 'no' ) {
      $section_classes .= ' hidden';
  }

  if ( ! empty( $content->class ) && is_scalar( $content->class ) ) {
      $section_classes .= ' ' . $content->class;
  }

  // Resolve section ID
  $section_id = ( ! empty( $content->id ) && is_scalar( $content->id ) ) ? $content->id : '';
@endphp

<section
  class="{{ $section_classes }}"
  aria-label="Featured partners"
  @if ( ! empty( $section_id ) ) id="{{ esc_attr( $section_id ) }}" @endif
>

  @if ( ! empty( $content->logos ) && is_iterable( $content->logos ) )
    <div class="partner-logo-slider swiper-container" id="partner-logo-slider">
      <div class="swiper-wrapper">

        {{-- Slides --}}
        @foreach ( $content->logos as $_row_logos )
          @php( $_img = $_row_logos['logo_image'] ?? null )
          @php( $_c_img = $_row_logos['colorful_logo_image'] ?? null )
          @if ( ! empty( $_img ) || ! empty( $_c_img ) )
            <div class="swiper-slide partner-logo-slide">
              @if ( ( is_array( $_img ) && ! empty( $_img['url'] ) ) || ( is_array( $_c_img ) && ! empty( $_c_img['url'] ) ) )
                <span class="partner-logo-pair">
                  @if ( is_array( $_c_img ) && ! empty( $_c_img['url'] ) )
                    <img
                      class="partner-logo blue"
                      src="{{ esc_url( $_c_img['url'] ) }}"
                      alt="{{ esc_attr( $_c_img['alt'] ?? '' ) }}"
                      @if ( ! empty( $_c_img['width'] ) )  width="{{ absint( $_c_img['width'] ) }}"  @endif
                      @if ( ! empty( $_c_img['height'] ) ) height="{{ absint( $_c_img['height'] ) }}" @endif
                      loading="lazy"
                    >
                  @endif

                  @if ( is_array( $_img ) && ! empty( $_img['url'] ) )
                    <img
                      class="partner-logo color"
                      src="{{ esc_url( $_img['url'] ) }}"
                      alt="{{ esc_attr( $_img['alt'] ?? '' ) }}"
                      @if ( ! empty( $_img['width'] ) )  width="{{ absint( $_img['width'] ) }}"  @endif
                      @if ( ! empty( $_img['height'] ) ) height="{{ absint( $_img['height'] ) }}" @endif
                      loading="lazy"
                    >
                  @endif
                </span>
              @endif
            </div>
          @endif
        @endforeach

      </div>
    </div>
  @endif

</section>
