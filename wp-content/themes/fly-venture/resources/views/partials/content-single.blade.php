@php
  $thumbnail_id  = get_post_thumbnail_id();
  $thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : '';
  $thumbnail_alt = $thumbnail_id ? get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true) : get_the_title();
  $button_1 = get_field('button_1');
  $button_2 = get_field('button_2');
  $categories = get_the_category();
  $category   = ! empty($categories) ? $categories[0] : null;
@endphp

<article @php(post_class())>

  <div class="container-fluid">
    <div class="blog-single__inner max-w-1200 mx-auto ">
      {{-- Featured Image --}}
      @if(!empty($thumbnail_url))
        <div class="blog-single__thumbnail">
          <img
            src="{{ esc_url($thumbnail_url) }}"
            alt="{{ esc_attr($thumbnail_alt) }}"
            class="w-full h-full object-cover rounded-lg min-h-550 max-h-550 max-1023:max-h-350 max-1023:min-h-350 max-575:min-h-300 max-575:max-h-300 mb-24"
          >
        </div>
      @endif

      {{-- Category --}}
      {{-- @if(!empty($category))
        <div class="blog-single__category">
          <a href="{{ esc_url(get_category_link($category->term_id)) }}" class="blog-category-link">
            {{ esc_html($category->name) }}
          </a>
        </div>
      @endif --}}

      {{-- Title --}}
      <div class="title title-blue blog-single__title text-center">
        <h1 class="h2" >
          {!! $title !!}
        </h1>
      </div>

      {{-- Meta: Author & Date --}}
      <div class="blog-single__meta my-24">
        <span class="blog-single__author">
        <img src="@asset('resources/images/author-icon.svg')" alt="Author Icon">
          <a href="{{ esc_url(get_author_posts_url(get_the_author_meta('ID'))) }}">
            {{ esc_html(get_the_author()) }}
          </a>
        </span>
        <span class="blog-single__author">
          <img src="@asset('resources/images/date-icon.svg')" alt="Date Icon">
          <time class="blog-single__date" datetime="{{ esc_attr(get_post_time('c', true)) }}">
            {!! get_the_date() !!}
          </time>
        </span>
        @if(!empty($categories))
  <div class="blog-single__author">
    <img src="@asset('resources/images/category-icon.svg')" alt="Category Icon">

    @foreach($categories as $cat)
      <a href="{{ esc_url(get_category_link($cat->term_id)) }}" class="blog-category-link">
        {{ esc_html($cat->name) }}
      </a>@if(!$loop->last)/ @endif
    @endforeach

  </div>
@endif
      </div>

      {{-- Content --}}
      <div class="blog-single__content e-content">
        {!! the_content() !!}
      </div>
      @if($button_1 || $button_2)
        <div class="btn-flex flex flex-wrap gap-16 justify-center mt-24">
          @if($button_1)
            <a href="{{ esc_url($button_1['url']) }}" class="btn btn-orange" aria-label="{{ esc_attr($button_1['title']) }}" target="{{ $button_1['target'] ?: '_self' }}" role="link">
              {{ $button_1['title'] }}
            </a>
          @endif
          @if($button_2)
            <a href="{{ esc_url($button_2['url']) }}" class="btn btn-b-white" aria-label="{{ esc_attr($button_2['title']) }}" target="{{ $button_2['target'] ?: '_self' }}" role="link">
              {!! $button_2['title'] !!}
            </a>
          @endif
        </div>
      @endif

      {{-- Pagination (multi-page posts) --}}
      @if($pagination())
        <nav class="page-nav" aria-label="Page">
          {!! $pagination !!}
        </nav>
      @endif

    </div>

  </div>


</article>
