@php
  // Section classes
  $section_classes = 'wys-section relative py-100 max-1199:py-60 max-767:py-40';

  if ( ! empty( $content->hide_section ) && $content->hide_section !== 'no' ) {
      $section_classes .= ' hidden';
  }

  if ( ! empty( $content->class ) && is_scalar( $content->class ) ) {
      $section_classes .= ' ' . $content->class;
  }

  // Section ID
  $section_id = ( ! empty( $content->id ) && is_scalar( $content->id ) ) ? $content->id : '';

  // Section data
  $bg_color   = $content->background_color ?? '';
  $icon       = $content->wyws_icon ?? [];
  $title      = $content->wyws_title ?? '';
  $description = $content->wyws_description ?? '';
  $locations  = $content->wyws_locations ?? [];
@endphp

  <!-- wys-section-start -->
<section
  class="{{ $section_classes }}"
  @if ( ! empty( $section_id ) ) id="{{ esc_attr( $section_id ) }}" @endif
  @if ( ! empty( $bg_color ) ) style="background-color: {{ esc_attr( $bg_color ) }};" @endif
>
  <div class="container-fluid">

    <!-- WYS Header -->
    <div class="wys-head fadeText">

      @if ( ! empty( $icon['url'] ) )
        <div class="wys-head-icon" aria-hidden="true">
          <img
            src="{{ esc_url( $icon['url'] ) }}"
            width="{{ absint( $icon['width'] ) }}"
            height="{{ absint( $icon['height'] ) }}"
            alt="{{ esc_attr( $icon['alt'] ?: $icon['title'] ) }}"
          >
        </div>
      @endif

      @if ( ! empty( $title ) )
        <div class="title title-blue">
          <h2>{{ $title }}</h2>
        </div>
      @endif

      @if ( ! empty( $description ) )
        <div class="content content-black">
          {!! wp_kses_post( $description ) !!}
        </div>
      @endif

    </div>

    <!-- WYS Grid -->
    @if ( ! empty( $locations ) && is_iterable( $locations ) )
      <div class="wys-grid">

        @foreach ( $locations as $_location )
          @php
            $_img  = $_location['wyws_r_image'] ?? [];
            $_link = $_location['location_link'] ?? [];
          @endphp

          @if ( ! empty( $_img['url'] ) )
            <div class="wys-slide">
              <div class="wys-card">
                <div class="wys-card-media">
                  <img
                    src="{{ esc_url( $_img['url'] ) }}"
                    width="{{ absint( $_img['width'] ) }}"
                    height="{{ absint( $_img['height'] ) }}"
                    alt="{{ esc_attr( $_img['alt'] ?: $_img['title'] ) }}"
                    loading="lazy"
                  >
                  <div class="wys-card-overlay" aria-hidden="true"></div>

                  @if ( ! empty( $_link['title'] ) )
                    <div class="wys-card-pill">
                      @if ( ! empty( $_link['url'] ) )

                      <a href="{{ esc_url( $_link['url'] ) }}"
                        target="{{ esc_attr( $_link['target'] ?: '_self' ) }}"
                        >
                        📍{{ $_link['title'] }}
                        </a>
                      @else
                        <span>📍{{ $_link['title'] }}</span>
                      @endif
                    </div>
                  @endif

                </div>
              </div>
            </div>
          @endif

        @endforeach

      </div>
    @endif

  </div>
</section>
<!-- wys-section-end -->
