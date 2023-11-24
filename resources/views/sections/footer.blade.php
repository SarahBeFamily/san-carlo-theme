@php global $post @endphp

<footer class="main-footer">

  @if ( get_field('newsletter_footer', $post->ID) == true || is_singular('spettacoli'))

    <div class="newsletter">

      <div class="bf-title-block">
        <p class="pre-title-text">{{ _e( 'Newsletter', 'san-carlo-theme' ) }}</p>
        <p class="bf-title">@option('newsletter_text')</p>
		  </div>

      @shortcode('[contact-form-7 id="170" title="'.__( 'Newsletter', 'san-carlo-theme' ).'"]')

    </div>

  @endif
  
  <div class="widget-top">
    @php(dynamic_sidebar('sidebar-footer-sponsor'))
  </div>

  <div class="content-info">
    <div class="col">
      @php(dynamic_sidebar('sidebar-footer-menu'))
    </div>

    <div class="col">
      @php(dynamic_sidebar('sidebar-footer-info'))
    </div>
  </div>

  <div class="widget-bottom">
    
    @php(dynamic_sidebar('sidebar-footer-bottom'))

    <div class="social-wrap">
      @options('social', 'options')
        <a href="@sub('link')" class="social @sub('tipo_social')">
          <i class="bf-icon white @sub('tipo_social')"></i>
        </a>
      @endoptions
    </div>
  </div>
</footer>

<footer class="copyright">
  <p>
    @shortcode('[dynamic_copyright]') | PI @option('p_iva')
  </p>
</footer>