@set($style, '')
@set($class, 'simple-title')
@set($title, get_field('titolo_area') ? get_field('titolo_area') : get_the_title())
@set($title2, is_archive('spettacoli') && get_field('titolo_pagina_spettacoli_ed_eventi', 'options') ? get_field('titolo_pagina_spettacoli_ed_eventi', 'options') : $title)
@set($titolo, get_field('title') ? get_field('title') : $title2)
@set($descr, get_field('description') == true ? 'true' : 'false')
@set($title_accent, is_archive() ? 'titles' : get_field('title_accent'))
@set($terms_array, get_the_terms(get_the_ID(), 'categoria-pagina') )
@set($terms, array())
@set($pre_title_text, get_field('pre_title_text'))

@if (isset($terms_array) && !empty($terms_array))
@foreach ($terms_array as $term) @php $terms[] = $term->slug @endphp @endforeach
@endif

@if (is_404())
  @set($titolo, __('Page not found', 'san-carlo-theme'))
  @set($title_accent, 'titles')
@endif

@if (in_array('amministrazione-trasparente', $terms))
  @set($titolo, __('Amministrazione Trasparente', 'san-carlo-theme'))
  @set($title_accent, 'titles')
@endif

@if (! is_search())
@isfield('bg_image', true)
  @set($style, 'background-image: linear-gradient(-90deg,rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.5) 140%), url( '.$image.' );')
  @set($class, 'article-title')
@endfield
@else
  @set($class, 'article-title search-page')
  @set($titolo, sprintf(
    /* translators: %s è la query di ricerca */
      __( 'Search results for &#8220;%s&#8221;', 'san-carlo-theme' ), 
      get_search_query() 
  );)
  @set($pre_title_text, '')
@endif

@if (in_array('woocommerce-page', get_body_class()))
  @set($class, 'hidden')
@endif

{{-- @php(var_dump(get_body_class())) --}}

<div class="{{$class}}" style="{{$style}}">
  @shortcode('[bf_titles title="'.$titolo.'" title_heading="h1" size="big" align="" color_title_accent="'.$title_accent.'" pre_title_style="'.get_field('titoletto').'" pre_title_text="'.$pre_title_text.'" pre_title_accent="'.get_field('pre_title_accent').'" pre_title_color="" description="'.$descr.'" descr_text="'.get_field('descr_text').'" color_descr_accent="'.get_field('descr_accent').'"]')
</div>