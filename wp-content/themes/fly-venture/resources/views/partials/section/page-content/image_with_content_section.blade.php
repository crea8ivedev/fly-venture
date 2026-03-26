@php
  // Build section classes
  $section_classes = 'adventure-section py-100 general-padding';

  if ( ! empty( $content->hide_section ) && $content->hide_section !== 'no' ) {
      $section_classes .= ' hidden';
  }

  if ( ! empty( $content->class ) && is_scalar( $content->class ) ) {
      $section_classes .= ' ' . $content->class;
  }

  // Resolve section ID
  $section_id = ( ! empty( $content->id ) && is_scalar( $content->id ) ) ? $content->id : '';

  // Section data
  $position    = $content->iwc_image_position ?? 'left';
  $bgColor     = $content->iwc_background_color ?? '';
  $image       = $content->iwc_image ?? [];
  $icon        = $content->iwc_icon ?? [];
  $title       = $content->iwc_title ?? '';
  $description = $content->iwc_description ?? '';
  $button      = $content->iwc_button_text ?? [];
  $isLeft      = $position === 'left';
@endphp

  <!-- adventure section start -->
<section
  class="{{ $section_classes }}"
  @if ( ! empty( $section_id ) ) id="{{ esc_attr( $section_id ) }}" @endif
  @if ( ! empty( $bgColor ) ) style="background-color: {{ esc_attr( $bgColor ) }};" @endif
>
  <div class="container-fluid">
    <div class="adventure-inner fadeText">

      @if ( $isLeft && ! empty( $image ) )
        <div class="adventure-media">
          <img
            src="{{ esc_url( $image['url'] ) }}"
            width="{{ absint( $image['width'] ) }}"
            height="{{ absint( $image['height'] ) }}"
            alt="{{ esc_attr( $image['alt'] ?: $image['title'] ) }}"
          >
        </div>
      @endif

      @if ( ! empty( $title ) || ! empty( $description ) || ! empty( $button ) )
        <div class="adventure-content">

          @if ( ! empty( $icon ) )
            <div class="adventure-icon" aria-hidden="true">
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

          <div>
            @if ( ! empty( $description ) )
              <div class="content content-black">
                {!! wp_kses_post( $description ) !!}
              </div>
            @endif

            @if ( ! empty( $button['url'] ) && ! empty( $button['title'] ) )
              <div class="adventure-cta">

             <a  href="{{ esc_url( $button['url'] ) }}"
                class="btn btn-orange"
                role="link"
                target="{{ esc_attr( $button['target'] ?: '_self' ) }}"
                aria-label="{{ esc_attr( $button['title'] ) }}"
                >
                {{ $button['title'] }}
                </a>
              </div>
            @endif
          </div>

        </div>
      @endif

      @if ( ! $isLeft && ! empty( $image ) )
        <div class="adventure-media">
          <img
            src="{{ esc_url( $image['url'] ) }}"
            width="{{ absint( $image['width'] ) }}"
            height="{{ absint( $image['height'] ) }}"
            alt="{{ esc_attr( $image['alt'] ?: $image['title'] ) }}"
          >
        </div>
      @endif

    </div>
  </div>
</section>
<!-- adventure-section-end -->
