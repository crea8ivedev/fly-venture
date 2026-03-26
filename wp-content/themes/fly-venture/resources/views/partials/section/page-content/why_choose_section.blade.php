@php
  // Build section classes
  $section_classes = 'why-choose-us-wrap relative';

  if ( ! empty( $content->hide_section ) && $content->hide_section !== 'no' ) {
      $section_classes .= ' hidden';
  }

  if ( ! empty( $content->class ) && is_scalar( $content->class ) ) {
      $section_classes .= ' ' . $content->class;
  }

  // Resolve section ID
  $section_id = ( ! empty( $content->id ) && is_scalar( $content->id ) ) ? $content->id : '';

  // Sanitize background color
  $bg_color = ! empty( $content->background_color ) && is_scalar( $content->background_color )
      ? sanitize_hex_color( $content->background_color )
      : '';
@endphp

  <!-- why-choose-start -->
<section
  class="{{ $section_classes }}"
  @if ( ! empty( $section_id ) ) id="{{ esc_attr( $section_id ) }}" @endif @if ( ! empty( $bg_color ) ) style="background-color: {{ $bg_color }};" @endif
>
  <div class="container-fluid-lg">
    <div class="inner-container" @if ( ! empty( $bg_color ) ) style="background-color: {{ $bg_color }};" @endif>
      <div class="flex justify-between gap-80 max-1199:flex-col-reverse max-1600:gap-40 max-575:gap-24 items-center">

        {{-- Feature Cards --}}
        @if ( ! empty( $content->wc_features ) && is_iterable( $content->wc_features ) )
          <div class="grid-container-left max-w-824 max-1199:max-w-full fadeText">
            <div class="grid grid-cols-2 gap-24 max-767:grid-cols-1 max-1023:gap-16">

              @foreach ( $content->wc_features as $_row )
                <div class="box">

                  {{-- Icon --}}
                  @if ( ! empty( $_row['wc_features_icon'] ) )
                    @php( $_img = $_row['wc_features_icon'] )
                    <div class="icon">
                      @if ( is_array( $_img ) && ! empty( $_img['url'] ) )
                        <img
                          src="{{ esc_url( $_img['url'] ) }}"
                          height="{{ ! empty( $_img['height'] ) ? absint( $_img['height'] ) : '120' }}"
                          width="{{ ! empty( $_img['width'] ) ? absint( $_img['width'] ) : '120' }}"
                          alt="{{ esc_attr( $_img['alt'] ?? '' ) }}"
                          loading="lazy"
                        >
                      @elseif ( is_string( $_img ) && $_img !== '' )
                        <img
                          src="{{ esc_url( $_img ) }}"
                          height="120"
                          width="120"
                          alt=""
                          loading="lazy"
                        >
                      @endif
                    </div>
                  @endif

                  {{-- Title --}}
                  @if ( ! empty( $_row['wc_features_title'] ) )
                    <div class="title title-black">
                      <h4>{{ is_scalar( $_row['wc_features_title'] ) ? $_row['wc_features_title'] : '' }}</h4>
                    </div>
                  @endif

                  {{-- Description --}}
                  @if ( ! empty( $_row['wc_features_short_desciption'] ) && is_scalar( $_row['wc_features_short_desciption'] ) )
                    <div class="content">
                      <p>{!! nl2br( esc_html( $_row['wc_features_short_desciption'] ) ) !!}</p>
                    </div>
                  @endif

                  {{-- Learn More Button --}}
                  @if ( ! empty( $_row['wc_features_lm_btn'] ) )
                    @php( $_link = $_row['wc_features_lm_btn'] )
                    <div class="btn-link">
                      @if ( is_array( $_link ) && ! empty( $_link['url'] ) )
                        <a
                          href="{{ esc_url( $_link['url'] ) }}"
                          aria-label="{{ esc_attr( $_link['title'] ?? 'learn-more' ) }}"
                          role="link"
                          @if ( ! empty( $_link['target'] ) ) target="{{ esc_attr( $_link['target'] ) }}" @endif
                        >
                          <span>{{ $_link['title'] ?? 'LEARN MORE' }}</span>
                          <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.64327 7.64327L1.52843 13.7581L-2.1383e-06 12.2297L5.35061 6.87905L-2.6724e-07 1.52844L1.52844 2.6724e-07L7.64327 6.11483C7.84591 6.31754 7.95975 6.59243 7.95975 6.87905C7.95975 7.16567 7.84591 7.44057 7.64327 7.64327Z" fill="#2872A3"/>
                          </svg>
                        </a>
                      @elseif ( is_string( $_link ) && $_link !== '' )
                        <a
                          href="{{ esc_url( $_link ) }}"
                          aria-label="learn-more"
                          role="link"
                        >
                          <span>LEARN MORE</span>
                          <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.64327 7.64327L1.52843 13.7581L-2.1383e-06 12.2297L5.35061 6.87905L-2.6724e-07 1.52844L1.52844 2.6724e-07L7.64327 6.11483C7.84591 6.31754 7.95975 6.59243 7.95975 6.87905C7.95975 7.16567 7.84591 7.44057 7.64327 7.64327Z" fill="#2872A3"/>
                          </svg>
                        </a>
                      @endif
                    </div>
                  @endif

                </div>
              @endforeach

            </div>
          </div>
        @endif

        {{-- Right Content --}}
        <div class="right-content max-w-467 max-1600:max-w-350 max-1199:max-w-full fadeText">
          <div class="flex flex-col gap-20 max-1199:text-center max-1199:items-center max-1199:justify-between">

            {{-- Icon --}}
            @if ( ! empty( $content->wc_icon ) )
              @php( $_img = $content->wc_icon )
              <div class="pretitle-icon">
                @if ( is_array( $_img ) && ! empty( $_img['url'] ) )
                  <img
                    src="{{ esc_url( $_img['url'] ) }}"
                    height="{{ ! empty( $_img['height'] ) ? absint( $_img['height'] ) : '107' }}"
                    width="{{ ! empty( $_img['width'] ) ? absint( $_img['width'] ) : '100' }}"
                    class="h-full w-full max-w-100 max-1023:max-w-50 object-cover"
                    alt="{{ esc_attr( $_img['alt'] ?? '' ) }}"
                    loading="lazy"
                  >
                @elseif ( is_string( $_img ) && $_img !== '' )
                  <img
                    src="{{ esc_url( $_img ) }}"
                    height="107"
                    width="100"
                    class="h-full w-full max-w-100 max-1023:max-w-50 object-cover"
                    alt=""
                    loading="lazy"
                  >
                @endif
              </div>
            @endif

            {{-- Title --}}
            @if ( ! empty( $content->wc_title ) )
              <div class="title title-blue">
                <h2>{{ is_scalar( $content->wc_title ) ? $content->wc_title : '' }}</h2>
              </div>
            @endif

            {{-- Description --}}
            @if ( ! empty( $content->wc_desciption ) )
              <div class="content">
                {!! wp_kses_post( $content->wc_desciption ) !!}
              </div>
            @endif

          </div>
        </div>

      </div>
    </div>
  </div>

</section>
