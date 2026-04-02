@php
  $section_classes = 'blog-listing-section py-100 max-1023:py-40';

  if (!empty($content->hide_section) && $content->hide_section !== 'no') {
      $section_classes .= ' hidden';
  }

  if (!empty($content->class) && is_scalar($content->class)) {
      $section_classes .= ' ' . $content->class;
  }

  $section_id = (!empty($content->id) && is_scalar($content->id)) ? $content->id : '';

  $posts         = $content->posts ?? [];
  $max_num_pages = $content->max_num_pages ?? 1;
  $paged         = $content->paged ?? 1;

  $categories = get_categories(['hide_empty' => true, 'orderby' => 'name', 'order' => 'ASC']);
@endphp

{{-- blog-listing-start --}}
<section
  class="{{ $section_classes }}"
  data-ajax-url="{{ esc_url(admin_url('admin-ajax.php')) }}"
  data-nonce="{{ wp_create_nonce('flyventure_blog_listing') }}"
  @if(!empty($section_id)) id="{{ esc_attr($section_id) }}" @endif
>
  <div class="container-fluid">

    {{-- Filters --}}
    <div class="blog-filters">

      {{-- Search --}}
      <div class="blog-search-wrap">
        <input
          type="search"
          class="blog-search-input"
          placeholder="{{ esc_attr__('Search posts...', 'sage') }}"
          aria-label="{{ esc_attr__('Search blog posts', 'sage') }}"
          value=""
        >
        <button type="button" class="blog-search-btn" aria-label="{{ esc_attr__('Search', 'sage') }}">
          <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M17 17L12.514 12.506M14.556 7.778C14.556 11.514 11.514 14.556 7.778 14.556C4.042 14.556 1 11.514 1 7.778C1 4.042 4.042 1 7.778 1C11.514 1 14.556 4.042 14.556 7.778Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
      </div>

      {{-- Category Dropdown --}}
      <select class="blog-category-select" aria-label="{{ esc_attr__('Filter by category', 'sage') }}">
        <option value="0">{{ esc_html__('All', 'sage') }}</option>
        @foreach($categories as $cat)
          <option value="{{ esc_attr($cat->term_id) }}">
            {{ esc_html($cat->name) }}
          </option>
        @endforeach
      </select>

    </div>

    {{-- Posts Grid --}}
    @if(!empty($posts))
      <div class="blog-listing-grid">
        @foreach($posts as $post)
          <article class="blog-card">

            @if(!empty($post['thumbnail']['url']))
              <a href="{{ esc_url($post['permalink']) }}" class="blog-card__thumbnail" aria-label="{{ esc_attr($post['title']) }}" tabindex="-1">
                <img
                  src="{{ esc_url($post['thumbnail']['url']) }}"
                  alt="{{ esc_attr($post['thumbnail']['alt']) }}"
                  class="w-full h-full object-cover"
                  loading="lazy"
                >
              </a>
            @endif

            <div class="blog-card__body">

              @if(!empty($post['category']))
                <a href="{{ esc_url($post['category']['url']) }}" class="blog-card__category">
                  {{ esc_html($post['category']['name']) }}
                </a>
              @endif

              <h2 class="blog-card__title">
                <a href="{{ esc_url($post['permalink']) }}">
                  {{ esc_html($post['title']) }}
                </a>
              </h2>

              <div class="blog-card__meta">
                <span class="blog-card__author">
                  <a href="{{ esc_url($post['author_url']) }}">
                    {{ esc_html($post['author']) }}
                  </a>
                </span>
                <time class="blog-card__date" datetime="{{ esc_attr($post['date_time']) }}">
                  {{ $post['date'] }}
                </time>
              </div>

              @if(!empty($post['excerpt']))
                <div class="blog-card__excerpt">
                  {{ $post['excerpt'] }}
                </div>
              @endif

            </div>

          </article>
        @endforeach
      </div>
    @else
      <p class="blog-listing-empty">
        {{ esc_html__('No posts found.', 'sage') }}
      </p>
    @endif

    {{-- Pagination --}}
    @if($max_num_pages > 1)
      <nav class="blog-pagination" aria-label="{{ esc_attr__('Blog pages', 'sage') }}">
        @if($paged > 1)
          {!! previous_posts_link('<span>&laquo;</span> ' . esc_html__('Previous', 'sage')) !!}
        @endif
        @if($paged < $max_num_pages)
          {!! next_posts_link(esc_html__('Next', 'sage') . ' <span>&raquo;</span>', $max_num_pages) !!}
        @endif
      </nav>
    @endif

  </div>

</section>
{{-- blog-listing-end --}}
