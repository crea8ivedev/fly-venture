@php
  // Build section classes
  $section_classes = 'faq-wrapper relative py-100 max-1023:py-40 bg-lightblue/30';

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

  <!-- faq-start -->
<section
  class="{{ $section_classes }}"
  @if ( ! empty( $section_id ) ) id="{{ esc_attr( $section_id ) }}" @endif
  @if ( ! empty( $bg_color ) ) style="background-color: {{ $bg_color }};" @endif
>
  <div class="container-fluid">
    <div class="faq-layout">

      {{-- FAQ Intro --}}
      <div class="faq-intro fadeText">

        {{-- Icon --}}
        @if ( ! empty( $content->fs_icon ) )
          @php( $_img = $content->fs_icon )
          <span class="faq-intro-icon mb-20 block" aria-hidden="true">
                        @if ( is_array( $_img ) && ! empty( $_img['url'] ) )
              <img
                src="{{ esc_url( $_img['url'] ) }}"
                height="{{ ! empty( $_img['height'] ) ? absint( $_img['height'] ) : '100' }}"
                width="{{ ! empty( $_img['width'] ) ? absint( $_img['width'] ) : '100' }}"
                alt="{{ esc_attr( $_img['alt'] ?? '' ) }}"
                class="w-full h-full object-cover max-w-100 max-h-100 max-1199:max-w-50 max-1199:max-h-50"
                loading="lazy"
              >
            @elseif ( is_string( $_img ) && $_img !== '' )
              <img
                src="{{ esc_url( $_img ) }}"
                height="100"
                width="100"
                alt=""
                class="w-full h-full object-cover max-w-100 max-h-100 max-1199:max-w-50 max-1199:max-h-50"
                loading="lazy"
              >
            @endif
                    </span>
        @endif

        {{-- Title --}}
        @if ( ! empty( $content->fs_title ) )
          <div class="title title-blue max-w-395 max-1023:max-w-full">
            <h2>{{ is_scalar( $content->fs_title ) ? $content->fs_title : '' }}</h2>
          </div>
        @endif

        {{-- Description --}}
        @if ( ! empty( $content->fs_desciption ) )
          <div class="content">
            {!! wp_kses_post( $content->fs_desciption ) !!}
          </div>
        @endif

      </div>

      {{-- FAQ List --}}
      @if ( ! empty( $content->faqs ) && is_iterable( $content->faqs ) )
        <div class="faq-list" aria-label="Frequently Asked Questions">

          @foreach ( $content->faqs as $_row )
            <div class="faq-item">

              <button
                type="button"
                class="faq-question"
                aria-expanded="false"
                aria-controls="faq-answer-{{ $loop->index }}"
              >
                @if ( ! empty( $_row['faqs_question'] ) )
                  <span>{{ is_scalar( $_row['faqs_question'] ) ? $_row['faqs_question'] : '' }}</span>
                @endif

                <span class="faq-icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.3049 9.88853H9.88853V17.3049H7.41639V9.88853H0V7.41639H7.41639V0H9.88853V7.41639H17.3049V9.88853Z" fill="#2872A3"/>
                                    </svg>
                                </span>
              </button>

              @if ( ! empty( $_row['faqs_answer'] ) )
                <div
                  id="faq-answer-{{ $loop->index }}"
                  class="faq-answer{{ $loop->first ? ' global-list' : '' }}"
                  @if ( ! $loop->first ) hidden @endif
                >
                  {!! wp_kses_post( $_row['faqs_answer'] ) !!}
                </div>
              @endif

            </div>
          @endforeach

        </div>
      @endif

    </div>
  </div>

</section>
