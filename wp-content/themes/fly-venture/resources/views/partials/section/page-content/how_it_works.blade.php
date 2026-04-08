@if ($content->hide_section !== 'yes')
    <section id="{!! $content->id ?? '' !!}" class="how-it-works-section py-100 max-1199:py-60 max-767:py-40 {!! $content->class ?? '' !!}">
        <div class="container-fluid">
            <div class="flex flex-col gap-44 items-center">

                <!-- Section Header -->
                <div class="how-it-works-header">
                    @if (!empty($content->works_icon))
                        <div class="how-it-works-icon">
                            <img data-src="{!! $content->works_icon['url'] !!}" data-srcset="{{ wp_get_attachment_image_srcset($content->works_icon['ID'] ?? 0) }}" data-sizes="auto" width="100" height="83"
                                alt="{!! $content->works_icon['alt'] ?? '' !!}" class="lazyload">
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

                @if (!empty($content->add_steps))
                    <div class="how-it-works-steps">
                    @foreach ($content->add_steps as $add_steps)
                        <div class="how-it-works-step">
                            
                            @if ($add_steps['image'])
                                <div class="step-image-wrap">
                                    <img data-src="{!! $add_steps['image']['url'] !!}" data-srcset="{{ wp_get_attachment_image_srcset($add_steps['image']['ID'] ?? 0) }}" data-sizes="auto" width="212" height="212"
                                        alt="{!! $add_steps['image']['alt'] !!}" class="lazyload">
                                </div>
                            @endif

                            <div class="step-body">
                                <div class="step-badge">
                                    <span>Step {{ $loop->iteration }}</span>
                                </div>

                                @if ($add_steps['title'])
                                    <div class="title title-blue">
                                        <h4>{!! $add_steps['title'] !!}</h4>
                                    </div>
                                @endif

                                @if ($add_steps['description'])
                                    <div class="content content-black">
                                        {!! $add_steps['description'] !!}
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Arrow (not after last item) --}}
                        @if (!$loop->last)
                            <div class="step-arrow {!! $loop->index % 2 != 0 ? 'step-arrow-flip' : '' !!}" aria-hidden="true">
                                <img data-src="@asset('resources/images/how-it-works-arrow.svg')" data-sizes="auto" width="187" height="120" alt="" class="lazyload">
                            </div>
                        @endif
                    @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif