@php
  // Build section classes
  $section_classes = 'full-img-content-wrap py-100 max-1023:pt-40 max-1023:pb-80';

  if ( ! empty( $content->hide_section ) && $content->hide_section !== 'no' ) {
      $section_classes .= ' hidden';
  }

  if ( ! empty( $content->class ) && is_scalar( $content->class ) ) {
      $section_classes .= ' ' . $content->class;
  }

  // Resolve section ID
  $section_id = ( ! empty( $content->id ) && is_scalar( $content->id ) ) ? $content->id : '';
@endphp

  <!-- full-img-content-start -->
<section
  class="{{ $section_classes }}"
  @if ( ! empty( $section_id ) ) id="{{ esc_attr( $section_id ) }}" @endif
>
  <div class="container-fluid">
    <div class="full-img-conatent">

      {{-- Media Image --}}
      @if ( ! empty( $content->cta_iwc_bg_image ) )
        @php( $_img = $content->cta_iwc_bg_image )
        <div class="full-img-conatent-media">
          @if ( is_array( $_img ) && ! empty( $_img['url'] ) )
            <img
              src="{{ esc_url( $_img['url'] ) }}"
              height="{{ ! empty( $_img['height'] ) ? absint( $_img['height'] ) : '438' }}"
              width="{{ ! empty( $_img['width'] ) ? absint( $_img['width'] ) : '977' }}"
              alt="{{ esc_attr( $_img['alt'] ?? '' ) }}"
              loading="lazy"
            >
          @elseif ( is_string( $_img ) && $_img !== '' )
            <img
              src="{{ esc_url( $_img ) }}"
              height="438"
              width="977"
              alt=""
              loading="lazy"
            >
          @endif
        </div>
      @endif

      {{-- Content --}}
      <div class="full-img-conatent-content fadeText">

        {{-- Title --}}
        @if ( ! empty( $content->cta_iwc_title ) )
          <div class="title title-blue">
            <h2>{{ is_scalar( $content->cta_iwc_title ) ? $content->cta_iwc_title : '' }}</h2>
          </div>
        @endif

        {{-- Description --}}
        @if ( ! empty( $content->cta_iwc_short_desciption ) )
            {!! wp_kses_post( $content->cta_iwc_short_desciption ) !!}
        @endif

        {{-- CTA Button --}}
        @if ( ! empty( $content->cta_iwc_btn ) )
          @php( $_link = $content->cta_iwc_btn )
          <div class="btn-custom mt-25">
            @if ( is_array( $_link ) && ! empty( $_link['url'] ) )
              <a
                href="{{ esc_url( $_link['url'] ) }}"
                class="btn btn-orange"
                aria-label="{{ esc_attr( $_link['title'] ?? 'Book your flight' ) }}"
                role="link"
                @if ( ! empty( $_link['target'] ) ) target="{{ esc_attr( $_link['target'] ) }}" @endif
              >{{ $_link['title'] ?? '' }}</a>
            @endif
          </div>
        @endif

      </div>


      {{-- Icon --}}
      @if ( ! empty( $content->icon_image ) )
        @php( $_img = $content->icon_image )
        <div class="full-img-conatent-icon" aria-hidden="true">
          @if ( is_array( $_img ) && ! empty( $_img['url'] ) )
            <img
              src="{{ esc_url( $_img['url'] ) }}"
              height="{{ ! empty( $_img['height'] ) ? absint( $_img['height'] ) : '169' }}"
              width="{{ ! empty( $_img['width'] ) ? absint( $_img['width'] ) : '180' }}"
              alt="{{ esc_attr( $_img['alt'] ?? '' ) }}"
              loading="lazy"
            >
          @endif
        </div>
      @endif

    </div>
  </div>

</section>
