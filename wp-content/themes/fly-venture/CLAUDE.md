# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

This is a WordPress theme for a travel/tours business built on [Sage v11](https://roots.io/sage/) (Roots.io framework) with Acorn, Laravel Blade templating, Vite, and Tailwind CSS v4.

## Commands

### Frontend
```bash
npm run dev       # Start Vite dev server with HMR
npm run build     # Production build (outputs to public/build/)
```

### i18n
```bash
npm run translate:pot      # Generate POT translation template
npm run translate:update   # Update .po files
npm run translate:compile  # Compile .mo and .json files
```

### PHP Code Style
```bash
./vendor/bin/pint           # Fix PHP code style (Laravel Pint)
./vendor/bin/pint --test    # Check without fixing
```

### Acorn (WordPress CLI via Acorn)
```bash
wp acorn optimize           # Cache config/views for production
wp acorn view:clear         # Clear compiled Blade views
```

## Architecture

### Framework: Sage 11 + Acorn

- `functions.php` — Bootstraps `Roots\Acorn\Application`; registers custom post types (Tours), taxonomies, and disables Gutenberg for posts/pages
- `app/setup.php` — Theme support declarations, menu locations, sidebar registration, asset enqueueing
- `app/filters.php` — WordPress filter hooks

### Blade Templating

Templates live in `resources/views/`. Sage maps WordPress template hierarchy to Blade files:
- `index.blade.php` → archive/home fallback
- `page.blade.php` → static pages
- `single-tours.blade.php` → single tour posts
- `partials/content-*.blade.php` — per-post-type content blocks
- `layouts/app.blade.php` — main HTML shell

### View Composers

`app/View/Composers/` — Classes that inject data into Blade templates before render. Each composer declares `protected static $views` to specify which templates it applies to, then implements `with()` to return data.

- `App.php` — Global data (site name) for all views
- `Post.php` — Title, pagination for post/archive/search templates
- `PageContentComposer.php` — Parses ACF Flexible Content fields into structured objects for page sections (home_banner, faq_section, tours_section, logo_section, etc.)
- `TourSingle.php` — Tour-specific data for single tour pages

### Custom Post Types

Registered in `functions.php`:
- **Tours** CPT (`tour`) — REST API enabled
- **Taxonomies:** `tour_category` (hierarchical), `tour_tag`, `tour_price_tag`

### CSS/JS Structure

Entry points declared in `vite.config.js`:
- `resources/css/app.css` → imports base, components, layout, utilities partials
- `resources/js/app.js` → sets up globals (jQuery, gsap, Lenis smooth scroll, Swiper)

Feature-specific JS modules in `resources/js/`: `animation.js`, `gallery.js`, `header.js`, `faq.js`, `popularTours.js`, `popupOffer.js`, `slider.js`, `tab.js` — imported in `app.js`.

### Build System

`vite.config.js` uses:
- `@roots/vite-plugin` — Sage integration, HMR with WordPress
- `@tailwindcss/vite` — Tailwind CSS v4
- `laravel-vite-plugin` — manifest generation
- Module aliases: `@scripts` → `resources/js/`, `@styles` → `resources/css/`, `@fonts`, `@images`
- Output base: `/et/fly-venture/wp-content/themes/fly-venture/public/build/`

### Key Frontend Libraries

- **GSAP + ScrollTrigger** — animations
- **Swiper** — carousels/sliders
- **Lenis** — smooth scrolling (initialized globally in `app.js`)
- **jQuery 4** — DOM utilities (set as global)

## Code Style

`.editorconfig` enforces:
- PHP: 4-space indent
- Blade/JS/CSS: 2-space indent
- LF line endings, UTF-8, single quotes, no trailing whitespace
