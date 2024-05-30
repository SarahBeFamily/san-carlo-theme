@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  <div class="vc_row wpb_row vc_row-bf-default pad-b-5 --light-gray">
    @if (! have_posts())
      <x-alert type="warning">
        {!! __('Sorry, but the page you are trying to view does not exist.', 'san-carlo-theme') !!}
      </x-alert>

      <br><br>
          
      <a class="bf-btn primary" href="{{ home_url() }}" title="">{{ __('Return to home', 'san-carlo-theme') }}</a>

      {!! get_search_form(false) !!}
    @endif
  </div>
@endsection
