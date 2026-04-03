@extends('layouts.app')
@section('content')

    @php
        $image = get_field('search_image', 'option');
        $search_title = get_field('search_title', 'option');

        $search_query = get_search_query();

        global $wp_query;
        $paged = max(1, get_query_var('paged', 1)); // Ensure $paged is set correctly
        $total_pages = $wp_query->max_num_pages;
    @endphp

    @if (!have_posts())
        <div class="search-content bg-beige text-center h-dvh">
            <div class="h-full flex flex-col justify-center ">

                <div class="title title-black warning-title pb-20 1023:pb-40">
                    <h2>
                        <x-alert type="warning">
                            {!! __('Sorry, no results were found.', 'sage') !!}
                        </x-alert>
                    </h2>
                </div>
                <div class="ctm_btn">
                    <a href="{{ home_url() }}" class="btn btn-orange inline-flex gap-x-[10px]">Go Back To Home
                        <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.6067 9.34764L10.5011 14.4532C10.4266 14.5277 10.3382 14.5868 10.2409 14.6271C10.1436 14.6674 10.0393 14.6882 9.93391 14.6882C9.82857 14.6882 9.72427 14.6674 9.62695 14.6271C9.52963 14.5868 9.4412 14.5277 9.36671 14.4532C9.29223 14.3787 9.23314 14.2903 9.19283 14.193C9.15252 14.0957 9.13177 13.9914 9.13177 13.886C9.13177 13.7807 9.15252 13.6764 9.19283 13.5791C9.23314 13.4817 9.29223 13.3933 9.36671 13.3188L13.1027 9.57964H1.12031C0.908139 9.57964 0.704656 9.49535 0.554627 9.34532C0.404598 9.19529 0.320312 8.99181 0.320312 8.77964C0.320312 8.56746 0.404598 8.36398 0.554627 8.21395C0.704656 8.06392 0.908139 7.97964 1.12031 7.97964H13.1027L9.36671 4.24044C9.21628 4.09 9.13177 3.88598 9.13177 3.67324C9.13177 3.5679 9.15252 3.46359 9.19283 3.36627C9.23314 3.26895 9.29223 3.18052 9.36671 3.10604C9.4412 3.03155 9.52963 2.97246 9.62695 2.93215C9.72427 2.89184 9.82857 2.87109 9.93391 2.87109C10.1467 2.87109 10.3507 2.9556 10.5011 3.10604L15.6067 8.21164C15.6817 8.28601 15.7412 8.37449 15.7818 8.47197C15.8224 8.56946 15.8434 8.67403 15.8434 8.77964C15.8434 8.88524 15.8224 8.98981 15.7818 9.0873C15.7412 9.18478 15.6817 9.27326 15.6067 9.34764Z" fill="white"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if (have_posts())
        <section class="hero-section inner-hero-section single-tour-hero">
            @if (!empty($image))
                <div class="hero-bg">
                    <img src="{!! $image['url'] !!}" alt="{!! $image['url'] !!}" class="h-full w-full object-cover desktop-img max-767:hidden!" loading="eager">
                </div>
            @endif
            <div class="hero-overlay"></div>
            <div class="container-fluid h-full">
                <div class="hero-content-wrap">
                    <div class="hero-content-left fadeText">
                        @if (!empty($search_title))
                            <div class="title title-white" style="translate: none; rotate: none; scale: none; opacity: 1; transform: translate(0px, 0px);">
                                <h1>{!! $search_title !!}</h1>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <section class="search-grid-wrapper blog-listing py-100 max-1024:py-50">
            <div class="container-fluid">

                <div class="carrer-grid grid grid-cols-3 max-1199:grid-cols-2 max-575:grid-cols-1 gap-45 max-[1200px]:gap-20 items-center">
                    @while (have_posts())
                        @php(the_post())
                        @include('partials.content-search')
                    @endwhile
                </div>
            </div>
        </section>
    @endif

@endsection
