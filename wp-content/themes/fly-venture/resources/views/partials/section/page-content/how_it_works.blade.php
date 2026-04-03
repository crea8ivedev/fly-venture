@if (isset($content->hide_section) && $content->hide_section == 'no')
    <section class="how-it-works-section py-100 max-1199:py-60 max-767:py-40 @if ($content->extra_class) {!! $content->extra_class !!} @endif"
            @if ($content->id) id="{!! $content->id !!}" @endif>
        <div class="container-fluid">
            <div class="flex flex-col gap-44 items-center">

                <!-- Section Header -->
                <div class="how-it-works-header">
                    @if (!empty($content->works_icon))
                        <div class="how-it-works-icon">
                            <img src="{!! $content->works_icon['url'] !!}" width="100" height="83"
                                alt="{!! $content->works_icon['url'] !!}">
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
                                    <img src="{!! $add_steps['image']['url'] !!}" width="212" height="212"
                                        alt="{!! $add_steps['image']['alt'] !!}">
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
                                <img src="@asset('resources/images/how-it-works-arrow.svg')" width="187" height="120" alt="">
                            </div>
                        @endif
                    @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif