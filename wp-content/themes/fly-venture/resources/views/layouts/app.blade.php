<!doctype html>
<html @php(language_attributes())>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Preconnect to third-party origins --}}
    <link rel="preconnect" href="https://widget.yonderhq.com">
    <link rel="preconnect" href="https://fareharbor.com">
    <link rel="dns-prefetch" href="https://widget.yonderhq.com">
    <link rel="dns-prefetch" href="https://fareharbor.com">

    {{-- LCP resource preloads (injected by section templates via @push) --}}
    @stack('preload')

    @php(do_action('get_header'))
    @php(wp_head())

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Yonder widget: load after first user interaction to avoid blocking LCP --}}
    <script>
      window.YONDER__CLIENT_CODE = "974";
      (function () {
        var loaded = false;
        function loadYonder() {
          if (loaded) return;
          loaded = true;
          var s = document.createElement('script');
          s.src = 'https://widget.yonderhq.com/loader.js';
          s.async = true;
          document.head.appendChild(s);
        }
        ['scroll', 'mousemove', 'touchstart', 'keydown'].forEach(function (e) {
          window.addEventListener(e, loadYonder, { once: true, passive: true });
        });
        // Fallback: load after 5 s even without interaction
        setTimeout(loadYonder, 5000);
      })();
    </script>
</head>

<body @php(body_class())>
    @php(wp_body_open())

    <div id="app">
        <a class="sr-only focus:not-sr-only" href="#main">
            {{ __('Skip to content', 'sage') }}
        </a>

        @include('sections.header')

        <main id="main" class="main">
            @yield('content')

        </main>

        @hasSection('sidebar')
            <aside class="sidebar">
                @yield('sidebar')
            </aside>
        @endif

        @include('sections.footer')
    </div>

    @php(do_action('get_footer'))
    @php(wp_footer())

    {{-- FareHarbor: load after first user interaction --}}
    <script>
      (function () {
        var loaded = false;
        function loadFH() {
          if (loaded) return;
          loaded = true;
          var s = document.createElement('script');
          s.src = 'https://fareharbor.com/embeds/api/v1/?autolightframe=yes';
          s.async = true;
          document.body.appendChild(s);
        }
        ['scroll', 'mousemove', 'touchstart', 'keydown'].forEach(function (e) {
          window.addEventListener(e, loadFH, { once: true, passive: true });
        });
        setTimeout(loadFH, 5000);
      })();
    </script>
</body>

</html>
