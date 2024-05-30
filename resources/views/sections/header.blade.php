@php
    global $post;
    $post_id = $post ? $post->ID : '';
    $transparent = '';
    if (!is_search() && !isset($_GET['s'])) {
      if (is_plugin_active('advanced-custom-fields-pro/acf.php')) :
        $transparent = get_field('header_trasparente', $post_id) == true || is_singular('spettacoli') || is_singular('post') || is_front_page(  ) ? 'transparent' : '';
      endif;
    }
    $accedi = get_page_by_title( 'Accedi', '', 'page' );
@endphp 

<header class="banner {{$transparent}}">
  <div class="row">

    <a class="logo" href="{{ home_url('/') }}" title="{!! $siteName !!}">
      <img id="logo-dark" src="@asset('images/logo-tsc-new-white-head.png')" alt="">
      <img id="logo-light" src="@asset('images/logo-tsc-new-black-head.png')" alt="">
    </a>

    <div class="col-right">
      {{-- mobile nav --}}
      <div class="mobile-nav">
        <div id="ham">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
        </div>  
        @if (has_nav_menu('primary_mobile'))
          <nav class="mobile" aria-label="{{ wp_get_nav_menu_name('primary_mobile') }}">
            {!! wp_nav_menu([
              'theme_location' => 'primary_mobile', 
              'menu_class' => 'nav-list', 
              'echo' => false, 
              'walker' => new SCarlo_Walker_Mobile	
              ]) !!}
          </nav>
        @endif
      </div>
      {{-- end mobile nav  --}}
      <div class="top-row">
        {{get_search_form()}}
        <div class="top-nav">
          @if (has_nav_menu('top_navigation'))
            <nav class="nav-top" aria-label="{{ wp_get_nav_menu_name('top_navigation') }}">
              {!! wp_nav_menu([
                'theme_location' => 'top_navigation', 
                'menu_class' => 'nav', 
                'echo' => false, 
                // 'walker' => new SCarlo_Walker	
                ]) !!}
            </nav>
          @endif
        {{-- cerca - user - carrello - selettore lingua --}}
        @set($area_utente, get_post( 192 ))
        @set($user_link, is_user_logged_in() ? get_permalink($area_utente->ID) : get_permalink($accedi->ID))
        
          <i id="icon-search" class="bf-icon icon-search"></i>
          <a href="{{$user_link}}" aria-label="{{ _e('Login', 'san-carlo-theme') }}"><i class="bf-icon icon-user"></i></a>
          @if (is_user_logged_in() && is_woocommerce_activated())
          <a href="{{wc_get_cart_url()}}">
            <i class="bf-icon icon-cart"></i>
            <span class="cart-count">{{WC()->cart->get_cart_contents_count()}}</span>
          </a>
          @endif
          {{-- <div class="lang">@action('wpml_add_language_selector')</div> --}}
          {{-- Aggiunto non da me, forse Emiliano?: --}}
          {{ custom_language_selector() }}
        </div>
        
      </div>

      <div class="row-nav">
        @if (has_nav_menu('primary_navigation'))
          <nav class="nav-primary" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
            {!! wp_nav_menu([
              'theme_location' => 'primary_navigation', 
              'menu_class' => 'nav', 
              'echo' => false, 
              'walker' => new SCarlo_Walker	
              ]) !!}
          </nav>
        @endif
      </div>
      
    </div>
  </div>
  
</header>
