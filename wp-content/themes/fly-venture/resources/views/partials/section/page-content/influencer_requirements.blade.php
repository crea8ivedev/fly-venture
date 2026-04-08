@if($content->hide_section !== 'yes')
<section
  id="{{ $content->id ?? '' }}"
  class="requirement-grid-wrap py-100 max-1199:py-60 max-575:py-40 {{ $content->class ?? '' }}"
  @if(!empty($content->ip_background_color)) style="background-color: {{ esc_attr($content->ip_background_color) }};" @endif
>
  <div class="container-fluid">

    {{-- Header --}}
    <div class="title-container flex flex-col max-w-900 mx-auto text-center items-center gap-20 mb-66 max-1023:mb-40 max-575:mb-30">

      @if(!empty($content->ip_icon))
        <div class="title-icon">
          <img
            data-src="{{ esc_url($content->ip_icon['url']) }}"
            data-srcset="{{ wp_get_attachment_image_srcset($content->ip_icon['ID'] ?? 0) }}"
            data-sizes="auto"
            width="{{ $content->ip_icon['width'] ?? '' }}"
            height="{{ $content->ip_icon['height'] ?? '' }}"
            alt="{{ esc_attr($content->ip_icon['alt'] ?? '') }}"
            class="w-full h-full object-cover max-w-100 max-h-100 max-1023:max-w-50 max-1023:max-h-50 lazyload"
          >
        </div>
      @endif

      @if(!empty($content->ip_title))
        <div class="title title-blue">
          <h2>{{ $content->ip_title }}</h2>
        </div>
      @endif

      @if(!empty($content->ip_description))
        <div class="content">
          {!! $content->ip_description !!}
        </div>
      @endif

    </div>

    {{-- Requirements boxes --}}
    @if(!empty($content->requirments))
      <div class="flex flex-wrap justify-center items-center gap-66 max-1441:gap-40 max-1023:gap-30">
        @foreach($content->requirments as $box)
          <div class="box">

            @if(!empty($box['media_icon']))
              <div class="icon">
                <img
                  data-src="{{ esc_url($box['media_icon']['url']) }}"
                  data-srcset="{{ wp_get_attachment_image_srcset($box['media_icon']['ID'] ?? 0) }}"
                  data-sizes="auto"
                  height="66"
                  width="auto"
                  alt="{{ esc_attr($box['media_icon']['alt'] ?? '') }}"
                  class="lazyload"
                >
              </div>
            @endif

            @if(!empty($box['sm_title']))
              <div class="title title-blue">
                <h2>{{ $box['sm_title'] }}</h2>
              </div>
            @endif

            @if(!empty($box['requirement']))
              <div class="content global-list">
                <ul>
                  @foreach($box['requirement'] as $item)
                    @if(!empty($item['sm_requirements']))
                      <li>{{ $item['sm_requirements'] }}</li>
                    @endif
                  @endforeach
                </ul>
              </div>
            @endif

          </div>
        @endforeach
      </div>
    @endif

  </div>
</section>
@endif
