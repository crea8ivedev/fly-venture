@php
  // Build section classes
  $section_classes = 'two-img-zigzag-wrapper relative pt-100 max-1023:pt-40';

  if ( ! empty( $content->hide_section ) && $content->hide_section !== 'no' ) {
      $section_classes .= ' hidden';
  }

  if ( ! empty( $content->class ) && is_scalar( $content->class ) ) {
      $section_classes .= ' ' . $content->class;
  }

  // Resolve section ID
  $section_id = ( ! empty( $content->id ) && is_scalar( $content->id ) ) ? $content->id : '';
@endphp

  <!-- two-img-zigzag-start -->
<section
  class="{{ $section_classes }}"
  @if ( ! empty( $section_id ) ) id="{{ esc_attr( $section_id ) }}" @endif
>
  <div class="container-fluid">
    <div class="bundle-save-inner">

      {{-- Gallery --}}
      @if ( ! empty( $content->iwc_image_1 ) || ! empty( $content->iwc_image_2 ) )
        <div class="bundle-save-gallery fadeText">

          {{-- Image 1 --}}
          @if ( ! empty( $content->iwc_image_1 ) )
            @php( $_img = $content->iwc_image_1 )
            <div class="bundle-save-image">
              @if ( is_array( $_img ) && ! empty( $_img['url'] ) )
                <img
                  src="{{ esc_url( $_img['url'] ) }}"
                  height="{{ ! empty( $_img['height'] ) ? absint( $_img['height'] ) : '722' }}"
                  width="{{ ! empty( $_img['width'] ) ? absint( $_img['width'] ) : '490' }}"
                  alt="{{ esc_attr( $_img['alt'] ?? '' ) }}"
                  loading="lazy"
                >
              @elseif ( is_string( $_img ) && $_img !== '' )
                <img
                  src="{{ esc_url( $_img ) }}"
                  height="722"
                  width="490"
                  alt=""
                  loading="lazy"
                >
              @endif
            </div>
          @endif

          {{-- Image 2 --}}
          @if ( ! empty( $content->iwc_image_2 ) )
            @php( $_img = $content->iwc_image_2 )
            <div class="bundle-save-image">
              @if ( is_array( $_img ) && ! empty( $_img['url'] ) )
                <img
                  src="{{ esc_url( $_img['url'] ) }}"
                  height="{{ ! empty( $_img['height'] ) ? absint( $_img['height'] ) : '722' }}"
                  width="{{ ! empty( $_img['width'] ) ? absint( $_img['width'] ) : '490' }}"
                  alt="{{ esc_attr( $_img['alt'] ?? '' ) }}"
                  loading="lazy"
                >
              @elseif ( is_string( $_img ) && $_img !== '' )
                <img
                  src="{{ esc_url( $_img ) }}"
                  height="722"
                  width="490"
                  alt=""
                  loading="lazy"
                >
              @endif
            </div>
          @endif

        </div>
      @endif

      {{-- Content --}}
      <div class="bundle-save-content fadeText">

        {{-- Icon --}}
        @if ( ! empty( $content->iwc_icon ) )
          @php( $_img = $content->iwc_icon )
          <div class="bundle-icon">
            @if ( is_array( $_img ) && ! empty( $_img['url'] ) )
              <img
                src="{{ esc_url( $_img['url'] ) }}"
                height="{{ ! empty( $_img['height'] ) ? absint( $_img['height'] ) : '100' }}"
                width="{{ ! empty( $_img['width'] ) ? absint( $_img['width'] ) : '100' }}"
                alt="{{ esc_attr( $_img['alt'] ?? '' ) }}"
                loading="lazy"
              >
            @elseif ( is_string( $_img ) && $_img !== '' )
              <img
                src="{{ esc_url( $_img ) }}"
                height="100"
                width="100"
                alt=""
                loading="lazy"
              >
            @endif
          </div>
        @endif

        {{-- Pretitle --}}
        @if ( ! empty( $content->iwc_title ) )
          <div class="pretitle">
            <span class="bundle-caption">{{ is_scalar( $content->iwc_title ) ? $content->iwc_title : '' }}</span>
          </div>
        @endif

        {{-- Title --}}
        @if ( ! empty( $content->iwc_sub_title ) )
          <div class="title title-blue">
            <h2>{!! wp_kses_post( $content->iwc_sub_title ) !!}</h2>
          </div>
        @endif

        {{-- Description --}}
        @if ( ! empty( $content->iwc_desciption ) )
          <div class="content mt-20">
            {!! wp_kses_post( $content->iwc_desciption ) !!}
          </div>
        @endif

        {{-- CTA Button --}}
        @if ( ! empty( $content->iwc_btn ) )
          @php( $_link = $content->iwc_btn )
          <div class="btn-custom mt-46 max-1023:mt-20">
            @if ( is_array( $_link ) && ! empty( $_link['url'] ) )
              <a
                href="{{ esc_url( $_link['url'] ) }}"
                class="btn btn-orange"
                aria-label="{{ esc_attr( $_link['title'] ?? 'Book your bundle' ) }}"
                role="link"
                @if ( ! empty( $_link['target'] ) ) target="{{ esc_attr( $_link['target'] ) }}" @endif
              >{{ $_link['title'] ?? '' }}</a>
            @elseif ( is_string( $_link ) && $_link !== '' )
              <a
                href="{{ esc_url( $_link ) }}"
                class="btn btn-orange"
                aria-label="Book your bundle"
                role="link"
              >BOOK YOUR BUNDLE</a>
            @endif
          </div>
        @endif

      </div>

    </div>
  </div>

</section>
