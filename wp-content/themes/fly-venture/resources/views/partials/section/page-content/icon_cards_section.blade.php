@if($content->hide_section !== 'yes')
<section
  id="{{ $content->id ?? '' }}"
  class="ctm-why-gift py-100 max-1199:py-60 max-767:py-40 {{ $content->class ?? '' }}"
  @if(!empty($content->background_color)) style="background-color: {{ esc_attr($content->background_color) }};" @endif
>
  <div class="container-fluid">
    <div class="flex flex-col gap-66 items-center">

      {{-- Header --}}
      <div class="what-sets-us-apart-header">

        @if(!empty($content->icon))
          <div class="section-badge-icon" aria-hidden="true">
            <img
              src="{{ esc_url($content->icon['url']) }}"
              width="{{ $content->icon['width'] ?? 100 }}"
              height="{{ $content->icon['height'] ?? 98 }}"
              alt="{{ esc_attr($content->icon['alt'] ?? '') }}"
            >
          </div>
        @endif

        @if(!empty($content->heading))
          <div class="title title-blue">
            <h2>{{ $content->heading }}</h2>
          </div>
        @endif

        @if(!empty($content->description))
          <div class="content content-black">
            {!! $content->description !!}
          </div>
        @endif

      </div>

      {{-- Icon Cards Grid --}}
      @if(!empty($content->icon_cards))
        <div class="grid grid-cols-5 gap-24 max-1440:grid-cols-3 max-992:gap-16 max-767:grid-cols-2 max-575:grid-cols-1">
          @foreach($content->icon_cards as $card)
            <article class="group cursor-pointer h-full flex min-h-140 flex-col justify-center items-center shadow-[0px_0px_16px_rgba(40,114,163,0.16)] rounded-[10px] border border-blue bg-white p-24 text-center transition-all duration-300 hover:bg-blue">

              @if(!empty($card['card_icon']))
                <div class="mb-20 flex h-100 w-100 items-center justify-center rounded-full bg-[#F5F9FC] transition-all duration-300 group-hover:bg-white">
                  <img
                    class="max-w-48"
                    src="{{ esc_url($card['card_icon']['url']) }}"
                    width="{{ $card['card_icon']['width'] ?? 100 }}"
                    height="{{ $card['card_icon']['height'] ?? 98 }}"
                    alt="{{ esc_attr($card['card_icon']['alt'] ?? '') }}"
                  >
                </div>
              @endif

              @if(!empty($card['title']))
                <h4 class="mb-20 font-bold text-blue! transition-all duration-300 group-hover:text-white!">
                  {{ $card['title'] }}
                </h4>
              @endif

              @if(!empty($card['ic_description']))
                <div class="text-black leading-24! transition-all duration-300 group-hover:text-white">
                  {!! $card['ic_description'] !!}
                </div>
              @endif

            </article>
          @endforeach
        </div>
      @endif

    </div>
  </div>
</section>
@endif
