{{--
  Template Name: Page Content
--}}

@extends('layouts.app')

@section('content')
    @while (have_posts())
        @php(the_post())
        @include('partials.content-page-content')
    @endwhile
@endsection
