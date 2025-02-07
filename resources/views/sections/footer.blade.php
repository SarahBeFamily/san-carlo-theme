@php 
  global $post;
  $post_id = $post ? $post->ID : '';
 @endphp

<footer class="main-footer">

  @if ( get_field('newsletter_footer', $post_id) == true || is_singular('spettacoli'))

    <div class="newsletter">

      <div class="bf-title-block">
        <p class="pre-title-text">{{ _e( 'Join our Community!', 'san-carlo-theme' ) }}</p>
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
      {{-- @php(dynamic_sidebar('sidebar-footer-info')) --}}
      <section class="widget widget_text">
        <div class="textwidget">
          @option('info_footer')
        </div>
      </section>
    </div>
  </div>

  <div class="widget-bottom">
    
    @php(dynamic_sidebar('sidebar-footer-bottom'))

    <div class="social-wrap">
      @options('social', 'options')
        {{-- translators: %s è il profilo social --}}
        @set($aria_label, sprintf(__('See our %s profile', 'san-carlo-theme'), strtolower(get_sub_field('tipo_social'))))
        <a href="@sub('link')" class="social @sub('tipo_social')" aria-label="{{ $aria_label }}">
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

<div id="loading-progress" class="hidden">
	<p id="loading-text"><?php _e('Loading Calendar...', 'san-carlo-theme'); ?></p>
	<div class="progress">
		<div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
	</div>
</div>