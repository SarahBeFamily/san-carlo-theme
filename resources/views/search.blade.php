@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  <div class="vc_row wpb_row vc_row-fluid vc_row-bf-default flex fwrap --light-gray pad-b-5">
    @if (! have_posts())
      <x-alert type="warning">
        {!! __('Sorry, no results were found.', 'san-carlo-theme') !!}
      </x-alert>

      {!! get_search_form(false) !!}
    @endif

    @while(have_posts()) @php(the_post())
      @include('partials.content-search')
    @endwhile

    {!! get_the_posts_navigation() !!}
  </div>
  
@endsection
