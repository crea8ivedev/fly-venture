@php
  // Build section classes
  $section_classes = 'custom-experience-section relative py-100 max-1199:py-60 max-767:py-40';

  if ( ! empty( $content->hide_section ) && $content->hide_section !== 'no' ) {
      $section_classes .= ' hidden';
  }

  if ( ! empty( $content->class ) && is_scalar( $content->class ) ) {
      $section_classes .= ' ' . $content->class;
  }

  // Resolve section ID
  $section_id = ( ! empty( $content->id ) && is_scalar( $content->id ) ) ? $content->id : '';

    // Sanitize background color
  $bg_color = ! empty( $content->layout_bg_color ) && is_scalar( $content->layout_bg_color )
      ? sanitize_hex_color( $content->layout_bg_color )
      : '';
@endphp

  <!-- custom-expernice-start -->
<section
  class="{{ $section_classes }}"
  @if ( ! empty( $section_id ) ) id="{{ esc_attr( $section_id ) }}" @endif @if ( ! empty( $bg_color ) ) style="background-color: {{ $bg_color }};" @endif
>
  <div class="container-fluid">
    <div class="custom-experience-head flex flex-col justify-center items-center max-w-900 mx-auto text-center max-1023:mb-24 gap-20 mb-66 fadeText">

      {{-- Icon --}}
      @if ( ! empty( $content->icon ) )
        @php( $_img = $content->icon )
        <div class="icon">
          @if ( is_array( $_img ) && ! empty( $_img['url'] ) )
            <img
              src="{{ esc_url( $_img['url'] ) }}"
              height="{{ ! empty( $_img['height'] ) ? absint( $_img['height'] ) : '100' }}"
              width="{{ ! empty( $_img['width'] ) ? absint( $_img['width'] ) : '100' }}"
              class="w-full h-full object-cover max-w-100 max-h-100 max-1023:max-w-50 max-1023:max-h-50"
              alt="{{ esc_attr( $_img['alt'] ?? '' ) }}"
              loading="lazy"
            >
          @elseif ( is_string( $_img ) && $_img !== '' )
            <img
              src="{{ esc_url( $_img ) }}"
              height="100"
              width="100"
              class="w-full h-full object-cover max-w-100 max-h-100 max-1023:max-w-50 max-1023:max-h-50"
              alt=""
              loading="lazy"
            >
          @endif
        </div>
      @endif

      {{-- Title --}}
      @if ( ! empty( $content->cye_title ) )
        <div class="title title-blue">
          <h2>{{ is_scalar( $content->cye_title ) ? $content->cye_title : '' }}</h2>
        </div>
      @endif

      {{-- Short Description --}}
        @if ( ! empty( $content->cye_short_desciption ) )
        <div class="content">
          {!! wp_kses_post( $content->cye_short_desciption ) !!}
        </div>
      @endif

    </div>

    {{-- Experience Cards Slider --}}
    @if ( ! empty( $content->cye_experience ) && is_iterable( $content->cye_experience ) )
      <div class="custom-experience-slider swiper-container" id="custom-experience-slider">
        <div class="swiper-wrapper">

          @foreach ( $content->cye_experience as $_row )
            <div class="swiper-slide">
              <div class="experience-card">

                {{-- Card Image --}}
                @if ( ! empty( $_row['cye_experience_card_image'] ) )
                  @php( $_img = $_row['cye_experience_card_image'] )
                  <div class="card-img">
                    @if ( is_array( $_img ) && ! empty( $_img['url'] ) )
                      <img
                        src="{{ esc_url( $_img['url'] ) }}"
                        height="{{ ! empty( $_img['height'] ) ? absint( $_img['height'] ) : '510' }}"
                        width="{{ ! empty( $_img['width'] ) ? absint( $_img['width'] ) : '420' }}"
                        alt="{{ esc_attr( $_img['alt'] ?? '' ) }}"
                        loading="lazy"
                      >
                    @elseif ( is_string( $_img ) && $_img !== '' )
                      <img
                        src="{{ esc_url( $_img ) }}"
                        height="510"
                        width="420"
                        alt=""
                        loading="lazy"
                      >
                    @endif
                  </div>
                @endif

                <div class="experience-card-content">

                  {{-- Card Icon --}}
                  @if ( ! empty( $_row['cye_experience_icon'] ) )
                    @php( $_img = $_row['cye_experience_icon'] )
                    @if ( is_array( $_img ) && ! empty( $_img['url'] ) )
                      <img
                        src="{{ esc_url( $_img['url'] ) }}"
                        height="{{ ! empty( $_img['height'] ) ? absint( $_img['height'] ) : '60' }}"
                        width="{{ ! empty( $_img['width'] ) ? absint( $_img['width'] ) : '60' }}"
                        alt="{{ esc_attr( $_img['alt'] ?? '' ) }}"
                        loading="lazy"
                      >
                    @elseif ( is_string( $_img ) && $_img !== '' )
                      <img
                        src="{{ esc_url( $_img ) }}"
                        height="60"
                        width="60"
                        alt=""
                        loading="lazy"
                      >
                    @endif
                  @endif

                  {{-- Card Title --}}
                  @if ( ! empty( $_row['cye_experience_title'] ) )
                    <div class="title title-white">
                      <h4>{{ is_scalar( $_row['cye_experience_title'] ) ? $_row['cye_experience_title'] : '' }}</h4>
                    </div>
                  @endif

                  {{-- Card Description --}}
                  @if ( ! empty( $_row['cye_experience_short_desciption'] ) && is_scalar( $_row['cye_experience_short_desciption'] ) )
                    <div class="content">
                      <p>{!! nl2br( esc_html( $_row['cye_experience_short_desciption'] ) ) !!}</p>
                    </div>
                  @endif

                </div>

              </div>
            </div>
          @endforeach

        </div>

        {{-- Mobile progress bar --}}
        <div class="custom-experience-progress 1300:hidden! block" id="custom-experience-progress"></div>

      </div>
    @endif

  </div>

</section>
