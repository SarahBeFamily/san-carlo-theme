{{--
  Template Name: Amministrazione Trasparente
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @include('partials.page-header')

        <div class="vc_row wpb_row vc_row-fluid vc_row-bf-default flex fwrap --light-gray sticky-wrap">

            <div class="bf-col-4">
              @if (has_nav_menu('amministrazione'))
              <nav class="nav-amm sticky" aria-label="{{ wp_get_nav_menu_name('amministrazione') }}">
                {!! wp_nav_menu([
                  'theme_location' => 'amministrazione', 
                  'menu_class' => 'menu-toggle', 
                  'echo' => false, 
                  ]) !!}
              </nav>
            @endif
                
            </div>
            
            <div class="bf-col-8 stik-cont">
                @if (count(get_post_ancestors(get_the_ID())) != 0)
                <h2>@title</h2>
                @endif
                @content
            </div>
        </div>

        <div class="--light-gray" style="height: 80px"></div>

  @endwhile
@endsection