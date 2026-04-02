@php
  $thumbnail_id  = get_post_thumbnail_id();
  $thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : '';
  $thumbnail_alt = $thumbnail_id ? get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true) : get_the_title();

  $categories = get_the_category();
  $category   = ! empty($categories) ? $categories[0] : null;
@endphp

<article @php(post_class())>

  {{-- Featured Image --}}
  @if(!empty($thumbnail_url))
    <div class="blog-single__thumbnail">
      <img
        src="{{ esc_url($thumbnail_url) }}"
        alt="{{ esc_attr($thumbnail_alt) }}"
        class="w-full h-full object-cover"
      >
    </div>
  @endif
  <div class="container-fluid">
    <div class="blog-single__inner max-w-1200 mx-auto ">

      {{-- Category --}}
      @if(!empty($category))
        <div class="blog-single__category">
          <a href="{{ esc_url(get_category_link($category->term_id)) }}" class="blog-category-link">
            {{ esc_html($category->name) }}
          </a>
        </div>
      @endif

      {{-- Title --}}
      <div class="title title-blue blog-single__title text-center">
        <h1 class="h2" >
          {!! $title !!}
        </h1>
      </div>

      {{-- Meta: Author & Date --}}
      <div class="blog-single__meta">
        <span class="blog-single__author">
          <a href="{{ esc_url(get_author_posts_url(get_the_author_meta('ID'))) }}">
            {{ esc_html(get_the_author()) }}
          </a>
        </span>
        <time class="blog-single__date" datetime="{{ esc_attr(get_post_time('c', true)) }}">
          {{ get_the_date() }}
        </time>
      </div>

      {{-- Content --}}
      <div class="blog-single__content e-content">
        @php(the_content())
      </div>

      {{-- Pagination (multi-page posts) --}}
      @if($pagination())
        <nav class="page-nav" aria-label="Page">
          {!! $pagination !!}
        </nav>
      @endif

    </div>

  </div>


</article>
