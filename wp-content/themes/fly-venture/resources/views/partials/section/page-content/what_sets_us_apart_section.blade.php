@php
  // Section classes
  $section_classes = 'what-sets-us-apart general-padding';

  if ( ! empty( $content->hide_section ) && $content->hide_section !== 'no' ) {
      $section_classes .= ' hidden';
  }

  if ( ! empty( $content->class ) && is_scalar( $content->class ) ) {
      $section_classes .= ' ' . $content->class;
  }

  // Section ID
  $section_id = ( ! empty( $content->id ) && is_scalar( $content->id ) ) ? $content->id : '';

  // Section data
  $bg_color    = $content->wsua_background_color ?? '';
  $icon        = $content->wsua_icon ?? [];
  $title       = $content->wsua_title ?? '';
  $description = $content->wsua_description ?? '';
  $steps       = $content->wyws_steps ?? [];
@endphp

<!-- what-sets-us-apart-start -->
<section
  class="{{ $section_classes }}"
  @if ( ! empty( $section_id ) ) id="{{ esc_attr( $section_id ) }}" @endif
  @if ( ! empty( $bg_color ) ) style="background-color:{{ esc_attr( $bg_color ) }};" @endif
>
  <div class="container-fluid-100">
    <div class="what-sets-us-apart-inner fadeText">

      <div class="what-sets-us-apart-header">

        @if ( ! empty( $icon['url'] ) )
          <div class="section-badge-icon" aria-hidden="true">
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

      @if ( ! empty( $steps ) && is_iterable( $steps ) )
        <div class="apart-cards-grid">

          @foreach ( $steps as $_step )
            @php
              $_s_icon  = $_step['wsua_s_icon'] ?? [];
              $_s_title = $_step['wsua_s_title'] ?? '';
              $_s_desc  = $_step['wsua_s_description'] ?? '';
            @endphp

            <div class="apart-card">

              @if ( ! empty( $_s_icon['url'] ) )
                <div class="apart-card-circle">
                  <img
                    src="{{ esc_url( $_s_icon['url'] ) }}"
                    width="{{ absint( $_s_icon['width'] ) }}"
                    height="{{ absint( $_s_icon['height'] ) }}"
                    alt="{{ esc_attr( $_s_icon['alt'] ?: $_s_icon['title'] ) }}"
                    loading="lazy"
                  >
                </div>
              @endif

              <div class="apart-card-body">

                @if ( ! empty( $_s_title ) )
                  <div class="title title-blue">
                    <h4>{{ is_scalar( $_s_title ) ? $_s_title : '' }}</h4>
                  </div>
                @endif

                @if ( ! empty( $_s_desc ) )
                  <div class="content content-black">
                    <p>{{ is_scalar( $_s_desc ) ? $_s_desc : '' }}</p>
                  </div>
                @endif

              </div>
            </div>

          @endforeach

        </div>
      @endif

    </div>
  </div>
</section>
<!-- what-sets-us-apart-end -->
