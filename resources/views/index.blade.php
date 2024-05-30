@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  @if (is_home())
    @include('sections.slider-news')
  @endif

  @if (! have_posts())
    <x-alert type="warning">
      {!! __('Sorry, no results were found.', 'sage') !!}
    </x-alert>

    {!! get_search_form(false) !!}
  @endif

  @if (is_home()) <div class="row-news --light-gray"> @endif
    
  @while(have_posts()) 
    @php(the_post())
    @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
  @endwhile

  {!! get_the_posts_navigation() !!}
@endsection

    @if (is_home()) </div> @endif

@section('sidebar')
  @include('sections.sidebar')
@endsection
