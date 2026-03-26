<header class="site-header" id="site-header">
  <div class="announcement-bar" aria-label="Announcements">
    <div class="flex flex-wrap justify-between items-center px-60 max-1199:px-0">

      {{-- Announcement Slider --}}
      <div class="announcement-slider swiper-container" id="announcement-slider">
        <div class="swiper-wrapper">

          @php
            $announcement_text   = $announcement_text ?? '';
            $announcement_button = (!empty($announcement_button) && is_array($announcement_button)) ? $announcement_button : [];
            $announcement_url    = esc_url($announcement_button['url'] ?? '');
            $announcement_title  = esc_html($announcement_button['title'] ?? '');
          @endphp

          @for ($i = 0; $i < 6; $i++)
            <div class="swiper-slide">
              <p class="announcement-item">
                {!! wp_kses_post($announcement_text) !!}
                @if(!empty($announcement_url) && !empty($announcement_title))
                  <a href="{{ $announcement_url }}" class="btn-white" aria-label="{{ $announcement_title }}" role="link">
                    {{ $announcement_title }}
                  </a>
                @endif
              </p>
            </div>
          @endfor

        </div>
      </div>

      {{-- Right Side: Phone + CTA --}}
      <div class="right-side-social">

        @php
          $header_phone       = $header_phone ?? '';
          $header_phone_label = !empty($header_phone) ? esc_html($header_phone) : '(844) 359-8368';
          $header_phone_href  = !empty($header_phone) ? esc_url('tel:' . $header_phone) : 'tel:(844) 359-8368';

          $book_flight_button = (!empty($book_flight_button) && is_array($book_flight_button)) ? $book_flight_button : [];
          $book_flight_url    = esc_url($book_flight_button['url'] ?? 'javascript:void(0);');
          $book_flight_title  = esc_html($book_flight_button['title'] ?? 'BOOK YOUR FLIGHT');
        @endphp

        <div class="phone">
          <img
            src="{{ get_theme_file_uri('/resources/images/white-call-icon.svg') }}"
            height="12"
            width="12"
            alt="phone icon"
          >
          <a href="{{ $header_phone_href }}" aria-label="{{ $header_phone_label }}" role="link">
            {{ $header_phone_label }}
          </a>
        </div>

        <div class="btn-custom">
          <a href="{{ $book_flight_url }}" class="btn btn-orange" aria-label="{{ $book_flight_title }}" role="link">
            {{ $book_flight_title }}
          </a>
        </div>

      </div>

    </div>
  </div>

  <div class="main-header">
    <div class="container-fluid">
      <div class="header-row">

        <a href="{{ home_url('/') }}" class="site-logo" aria-label="{{ get_bloginfo('name') }}">
          @if(!empty($header_logo) && is_array($header_logo) && !empty($header_logo['url']))
            <img
              src="{{ esc_url($header_logo['url']) }}"
              alt="{{ esc_attr($header_logo['alt'] ?? get_bloginfo('name')) }}"
              class="max-1441:max-w-250 max-1199:max-w-200 h-full w-full object-cover"
              height="60"
              width="280"
            >
          @else
            <img
              src="{{ get_theme_file_uri('/resources/images/flyventure/logo.png') }}"
              alt="{{ get_bloginfo('name') }}"
              class="max-1441:max-w-250 max-1199:max-w-200 h-full w-full object-cover"
              height="60"
              width="280"
            >
          @endif
        </a>

        <button
          class="menu-toggle"
          id="menu-toggle"
          aria-expanded="false"
          aria-controls="menu-main-menu"
          aria-label="Toggle menu"
        >
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 15C20.5523 15 21 15.4477 21 16C21 16.5523 20.5523 17 20 17H4C3.44772 17 3 16.5523 3 16C3 15.4477 3.44772 15 4 15H20ZM20 7C20.5523 7 21 7.44772 21 8C21 8.55228 20.5523 9 20 9H4C3.44772 9 3 8.55228 3 8C3 7.44772 3.44772 7 4 7H20Z" fill="white"/>
          </svg>
        </button>

        @if (has_nav_menu('primary_navigation'))
          <nav class="main-navigation" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
            {!! wp_nav_menu([
              'theme_location' => 'primary_navigation',
              'container'      => false,
              'menu_class'     => 'menu',
              'items_wrap'     => '<ul id="menu-main-menu" class="%2$s">%3$s</ul>',
              'echo'           => false,
            ]) !!}
          </nav>
        @endif

      </div>
    </div>
  </div>
</header>
