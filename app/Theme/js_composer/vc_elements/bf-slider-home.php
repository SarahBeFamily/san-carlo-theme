<?php

class BF_Slider_Home extends WPBakeryShortCode {

    function __construct() {
        add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
        add_shortcode( 'bf_slider_home', array( $this, 'bf_slider_home_shortcode' ) );
    }        

    public function create_shortcode() {
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }        


		vc_map( array(
			'name'				=> 'Slider Ultimi Spettacoli Main Home',
			'base'				=> 'bf_slider_home',
			'icon' 				=> 'bf-ico',
			'category' 			=> 'BF Elements',
			'admin_enqueue_css' => array( get_stylesheet_directory_uri() . '/app/Theme/vc_extend/vc.css' ),
			'params'  			=> array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => 'Aggiungi Logo Sponsor',
					"value" => "",
					"param_name" => "sponsor",
					'save_always' => true,
					'admin_label' => false,
					"description" => "",
				),
				array(
					'type' => 'dropdown',
					'heading' => 'Tipo di contenuto',
					'param_name' => 'type',
					'save_always' => true,
					'admin_label' => false,
					'value' => array(
						'Prossimi Spettacoli' => 'spettacoli',
						'Contenuti Vari' => 'custom',
					),
					'std' => 'spettacoli', // Your default value
				),
				array(
					'type' => 'param_group',
					'value' => '',
					'param_name' => 'slide',
					'heading' => 'Aggiungi Slide',
					'dependency' 	=> array(
						'element'	=> 'type',
						'value' 	=> 'custom',
					),
					// Note params is mapped inside param-group:
					'params' => array(
						array(
							"type" => "attach_image",
							"class" => "",
							"heading" => 'Aggiungi Immagine di sfondo',
							"value" => "",
							"param_name" => "featured_img",
							'save_always' => true,
							'admin_label' => false,
							"description" => "",
						),
						array(
							'type' => 'textfield',
							'value' => '',
							'heading' => 'Data',
							'param_name' => 'date_slide',
							'admin_label' => true,
							"description" => "Inserire una data (facoltativo)",
						),
						array(
							'type' => 'textfield',
							'value' => '',
							'heading' => 'Titolo',
							'param_name' => 'title_slide',
							'admin_label' => true,
						),
						array(
							'type'          => 'textarea',
							'heading'       => 'Inserisci Testo',
							'param_name'    => 'text_slide',
							'save_always' 	=> true,
							'admin_label' 	=> false,
							'value'         => __( '', 'san-carlo-theme' ),
							'description'   => 'Inserisci del testo che verrà visualizzato sotto il titolo',
						),
						array(
							'type' => 'textfield',
							'value' => '',
							'heading' => 'Inserisci link Video',
							'param_name' => 'link_video',
							'admin_label' => true,
						),
						array(
							'type' => 'vc_link',
							'holder' => '',
							'class' => 'link_button_slide',
							'heading' => 'Link e testo',
							'param_name' => 'link',
							'admin_label' => false,
							'value' => '',
							'description' => 'Inserisci un testo e un link per il bottone',
						),
					),
				),
				array(
					'type'          => 'textfield',
					'heading'       => 'ID',
					'param_name'    => 'id_elem',
					'value'         => '',
					'description'   => 'Imposta un ID all\'elemento',
					'group'         => 'Extra',
				),
				array(
					'type'          => 'textfield',
					'heading'       => 'Classe extra',
					'param_name'    => 'extra_class',
					'value'         => '',
					'description'   => 'Aggiungi una classe extra all\'elemento',
					'group'         => 'Extra',
				),  
			)
		) );
	}

    public function bf_slider_home_shortcode( $atts, $content = null ) {
		$sponsor = $type = $slide = $featured_img = $title_slide = $date_slide = $text_slide = $link_button_slide = $id_elem = $extra_class = '';
		extract(shortcode_atts( array(
			'sponsor' => '',
			'type' => '',
			'slide' => '',
			'featured_img' => '',
			'title_slide' => '',
			'date_slide' => '',
			'text_slide' => '',
			'link_button_slide' => '',
			'id_elem' => '',
			'extra_class' => '',
			), $atts ));
	
		$foto_sponsor = wp_get_attachment_image( $sponsor, 'full' );
		$id = $id_elem ? ' id="'.$id_elem.'" ' : '';

		$today = date('Ymd');
		$args = array(
			'post_type' => 'spettacoli',
			'numberposts' => 4,
			'posts_per_page' => 4,
			'meta_key' => 'data_inizio',
			'orderby' => 'meta_value',
			'order' => 'ASC',
			'suppress_filters' => false,
			'meta_query' => array(
				array(
					'key'  => 'data_inizio',
					'compare'   => '>=',
					'value'     => $today,
				),
			)
		);

		if ($type == 'spettacoli') {
			$posts = new WP_Query($args);
			$second_post = $posts->have_posts() ? $posts->posts[1] : array();
			$post_count = $posts->post_count; 
		} else
		if ($type == 'custom') {
			$posts = vc_param_group_parse_atts( $atts['slide'] );
			$second_post = is_array($posts) && !empty($posts) ? $posts[1] : array();
			$post_count = count($posts);
		}

		// $posts = $type == 'custom' && isset($atts['slide']) ? vc_param_group_parse_atts( $atts['slide'] ) : $query;
		$next_img = $type == 'custom' ? wp_get_attachment_image_src($second_post['featured_img'], 'medium')[0] : get_the_post_thumbnail_url( $second_post->ID, 'medium' );
		$next_title = $type == 'custom' ? $second_post['title_slide'] : get_the_title( $second_post->ID );

		$html  = '';
		$html .= '<div'.$id.' class="bf-slider-home full '.$extra_class.'">';

		$html .= '<div class="slides-wrapper">';

			if ($type == 'spettacoli' && $posts->have_posts()) {

				while($posts->have_posts(  )) : $posts->the_post(  );
				
				$i = $posts->current_post;
				$current = $i == 0 ? ' current' : '';

				$dateStart = get_field('data_inizio');
				$dateEnd = get_field('data_fine');
				$dateStart_noY = wp_date('j F', strtotime($dateStart));
				/* translators: %1$s è la data di inizio e %2$s è la data di fine spettacolo */
				$data = $dateStart != $dateEnd ? sprintf(__('From %1$s to %2$s', 'san-carlo-theme'), $dateStart, $dateEnd) : $dateStart;

				$descrizione = get_field('breve_descrizione') ? '<p>'.get_field('breve_descrizione').'</p>' : '';
				$preview_img = get_the_post_thumbnail_url( get_the_id(), 'medium' );

				$cats = array();
				
				$terms = get_the_terms( get_the_ID(), 'categoria-spettacoli' );
				foreach ($terms as $term) {
					$cats[] = $term->name;
				}
				$categorie = implode(' - ', $cats);

				$thumb_url = get_the_post_thumbnail_url( get_the_id(), 'full' ) ? get_the_post_thumbnail_url(get_the_id(), 'full' ) : get_field('show_placeholder', 'options');
				$html .= '<div id="hero-'.$i.'" class="single-slide'.$current.'" preview-img="'.$preview_img.'" style="background-image: linear-gradient(180deg, rgba(0, 0, 0, 0.5) 0%, rgba(0,0,0,0.5) 70%), url('.$thumb_url.');">';

					$html .= '<div class="info">';
						$html .= '<span class="date">'. $data .'</span>';
						$html .= '<span class="title">'. get_the_title( ) .'</span>';
						$html .= '<div class="meta">'. $descrizione .'</div>';
					$html .= '</div>';
					$html .= '<a class="bf-btn white" href="'.get_permalink( ).'" title="'. get_the_title( ) .'">'.__('Discover more', 'san-carlo-theme').' <i class="bf-icon right icon-arrow-right"></i></a>';

				$html .= '</div>';
				endwhile;
			}
			// End spettacoli

			if ($type == 'custom' && is_array($posts) && !empty($posts)) {

				foreach ($posts as $i => $post) {
					$current = $i == 0 ? ' current' : '';
					$featured_img = wp_get_attachment_image_src($post['featured_img'], 'full');
					$preview_img = wp_get_attachment_image_src($post['featured_img'], 'medium');

					$link  		= vc_build_link( $post['link'] );
					$text_btn 	= esc_html($link['title']);
					$link_btn   = esc_url( $link['url'] );

					$html .= '<div id="hero-'.$i.'" class="single-slide'.$current.'" preview-img="'.$preview_img[0].'" style="background-image: linear-gradient(180deg, rgba(0, 0, 0, 0.5) 0%, rgba(0,0,0,0.5) 70%), url('.$featured_img[0].');">';

						$html .= '<div class="info">';
							isset($post['date_slide']) ? $html .= '<span class="date">'. $post['date_slide'] .'</span>' : '';

							$html .= '<span class="title">'. $post['title_slide'] .'</span>';
							$html .= '<div class="meta"><p>'. $post['text_slide'] .'</p></div>';
							
						$html .= '</div>';
						$html .= '<a class="bf-btn white" href="'.$link_btn.'" title="'. $text_btn .'">'.__('Discover more', 'san-carlo-theme').' <i class="bf-icon right icon-arrow-right"></i></a>';
						
						if ($post['link_video'] != '') {
							$html .= '<a class="play-video" href="'.$post['link_video'].'" data-fancybox title="'. $text_btn .'"><img src="'.get_stylesheet_directory_uri() . '/app/Theme/js_composer/Play.png'.'" /></a>';
						}
						
					$html .= '</div>';
				}

				wp_enqueue_script( 'fancybox', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js', array( 'jquery' ), '3.5.7', true );
    			wp_enqueue_style( 'fancybox', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css', array(), '3.5.7' );
			}

			$html .= '</div>'; // close slides wrapper

			$html .= '<div class="slider-footer flex">';

				$html .= '<div class="col">';
					$html .= '<div class="sponsor">
								<span>'.__('Main sponsor', 'san-carlo-theme').'</span>
								'.$foto_sponsor.'
							 </div>';

					if ($post_count > 1) {

						$html .= '<div class="bf-slider-nav">';
						$html .= '<a class="prev"><i class="bf-icon icon-arrow-right titles"></i></a>';
						$html .= '<a class="next"><i class="bf-icon icon-arrow-right titles"></i></a>';
						$html .= '</div>';

						$html .= '<ul class="bf-slider-controls bullets">
									<span>01</span>';

							if ($type == 'spettacoli' && $posts->have_posts()) {
								while($posts->have_posts(  )) : $posts->the_post(  );
									$i = $posts->current_post;
									$active = $i == 0 ? 'class="active "' : '';
									$html .= '<li '.$active.' data-slide="hero-'.$i.'"><span></span></li>';
								endwhile;
							} else if ($type == 'custom') {
								foreach ($posts as $i => $post) : $active = $i == 0 ? 'class="active "' : '';

									$html .= '<li '.$active.' data-slide="hero-'.$i.'"><span></span></li>';

								endforeach;
							}

						$html .= '<span>0'.$post_count.'</span>
								</ul>';
					}

				$html .= '</div>';
				$html .= '<div class="col">';
					// Next slide preview built in js
					if ($post_count > 1) {
					$html .= '<div class="bf-slider-next-slide">';
						$html .= '<div class="ns-photo" style="background-image: url('.$next_img.');"></div>';
						$html .= '<div class="info"><span>'.__('Next', 'san-carlo-theme').'</span><p class="ns-title">'.$next_title.'</p></div>';
					$html .= '</div>';
					}
				$html .= '</div>';

			$html .= '</div>';

		$html .= '</div>';

		return $html;
    }
}

new BF_Slider_Home();
