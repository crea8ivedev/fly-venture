@if ($content->hide_section !== 'yes')
    <section id="{!! $content->id ?? '' !!}" class="highlights py-100 max-1199:py-60 max-767:py-40 {!! $content->class ?? '' !!}">
        <div class="container-fluid">
            <div class="flex flex-col items-center">
                <div class="common-head fadeText">
                    @if (!empty($content->highlights_icon))
                        <div class="common-head-icon" aria-hidden="true">
                            <img data-src="{!! $content->highlights_icon['url'] !!}" data-srcset="{{ wp_get_attachment_image_srcset($content->highlights_icon['ID'] ?? 0) }}" data-sizes="auto" width="68" height="100" alt="{!! $content->highlights_icon['alt'] ?? '' !!}" class="lazyload">
                        </div>
                    @endif
                    @if (!empty($content->title))
                        <div class="title title-blue">
                            <h2>{!! $content->title !!}</h2>
                        </div>
                    @endif
                    @if (!empty($content->sub_title))
                        <div class="content content-black">
                            <p>{!! $content->sub_title !!}</p>
                        </div>
                    @endif
                </div>
                @if (!empty($content->add_highlights))
                    <div class="features-grid__container">
                        @foreach ($content->add_highlights as $add_highlights)
                            <div class="feature-card">
                                @if ($add_highlights['image'])
                                    <img data-src="{!! $add_highlights['image']['url'] !!}" data-srcset="{{ wp_get_attachment_image_srcset($add_highlights['image']['ID'] ?? 0) }}" data-sizes="auto" alt="{!! $add_highlights['image']['alt'] ?? '' !!}" class="feature-card__img lazyload">
                                    <div class="feature-card__overlay">
                                        <h4 class="feature-card__title">{!! $add_highlights['title'] !!}</h4>
                                        {!! $add_highlights['description'] !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif