<?php

class BF_Carousel extends WPBakeryShortCode {

    function __construct() {
        add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
        add_shortcode( 'bf_carousel', array( $this, 'bf_carousel_shortcode' ) );
    }        

    public function create_shortcode() {
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }        


		vc_map( array(
			'name'				=> 'Carosello',
			'base'				=> 'bf_carousel',
			'icon' 				=> 'bf-ico',
			'category' 			=> 'BF Elements',
			'admin_enqueue_css' => array( get_stylesheet_directory_uri() . '/app/Theme/vc_extend/vc.css' ),
			'params'  			=> array(
				array(
					'type' => 'dropdown',
					'heading' => 'Tipo di Post da Visualizzare',
					'param_name' => 'post_type',
					'save_always' => true,
					'admin_label' => false,
					'value' => array(
						'Spettacoli' => 'spettacoli',
						'Semplici Immagini' => 'immagini',
					),
					'std' => 'spettacoli', // Your default value
				),
				array(
					"type" => "attach_images",
					"class" => "",
					"heading" => 'Aggiungi Immagini',
					"value" => "",
					"param_name" => "images",
					'save_always' => true,
					'admin_label' => false,
					"description" => "",
					'dependency' => array(
						'element' => 'post_type',
						'value' => 'immagini',
					),
				),
				array(
					'type'          => 'textfield',
					'heading'       =>'ID',
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

    public function bf_carousel_shortcode( $atts, $content = null ) {
		$post_type = $images = $id_elem = $extra_class = '';
		extract(shortcode_atts( array(
			'post_type' => '',
			'images' => '',
			'id_elem' => '',
			'extra_class' => '',
			), $atts ));
	
			if ($post_type !== 'immagini') {

				$today = date('Ymd');

				$posts = get_posts(array(
					'post_type' => $post_type,
					'suppress_filters' => false,
					'numberposts' => -1,
					'meta_key' => 'data_inizio',
					'orderby' => 'meta_value',
					'order' => 'asc',
				));
			}
		
		$id = $id_elem ? ' id="'.$id_elem.'" ' : '';	

		$html  = '';
		$html .= '<div'.$id.' class="bf-carousel -'.$post_type.' '.$extra_class.'">';

		if ($post_type !== 'immagini') {
			$counter = 0;
			foreach ($posts as $post) {
		
				$dateStart = get_field('data_inizio', $post);
				$dateEnd = get_field('data_fine', $post);
				$dateStart_array = $dateStart ? explode('/', $dateStart) : array();
				$dateStart_d = wp_date('j F', strtotime($dateStart_array[2].$dateStart_array[1].$dateStart_array[0]));
				$dateEnd_d = wp_date('j F', strtotime($dateEnd));
				$dateMonth = wp_date('F', strtotime($dateStart_array[2].$dateStart_array[1].$dateStart_array[0]));
				$data_full = $dateStart_d;

				$real_data = date('Ymd', strtotime($dateStart_array[2].$dateStart_array[1].$dateStart_array[0]));

				// var_dump($dateStart);
				if ($real_data >= $today && $counter < 6) :
					$counter++;

				if (!empty($dateStart_array)) {
					$data_n = $dateStart_array[0];
					$data_mese = $dateMonth; //substr($dateMonth, 0, 3);
					$data_Y = $dateStart_array[2];

					$data_full = $data_n.' '.$data_mese.' '.$data_Y;
				}

				/* translators: %1$s è la data di inizio e %2$s è la data di fine spettacolo */
				$data = $dateStart_d != $dateEnd_d ? sprintf(__('From %1$s to %2$s', 'san-carlo-theme'), $dateStart_d, $dateEnd_d) : $dateStart_d;
				
				$cats = array();
				
				$terms = get_the_terms( $post->ID, 'categoria-'.$post_type );
				foreach ($terms as $term) {
					$cats[] = $term->name;
					$cat = $term->name;
				}
				$categorie = implode(' - ', $cats);
					

				$html .= '<div class="bf-carousel-single-slide '.$post_type.'">';

					$html .= '<div style="background-image:url('.get_the_post_thumbnail_url( $post, 'full' ).');">';
					if ($post_type == 'spettacoli') :

						$html .= '<a class="info-wrap" href="'.get_permalink( $post ).'" title="'. get_the_title( $post ) .'">';
							$html .= '<div class="info">';
								/* translators: %s è la data di inizio spettacolo */
								$html .= '<p class="data">'. sprintf(__('From %s', 'san-carlo-theme'), $data_full).'</p>';
								$html .= '<div class="col -title">';
									$html .= '<span>'. get_the_title( $post ) .'</span>';
								$html .= '</div>';
								$html .= '<div class="col -meta">';
									$html .= '<p>'.get_field('breve_descrizione', $post).'</p>';
								$html .= '</div>';
							$html .= '</div>';
							$html .= '<div class="col -buttons">';
								$html .= isset($cat) ? '<div class="cat">'.$cat.'</div>' : '';
								$html .= '<span class="bf-btn primary icon-only"><i class="bf-icon icon-arrow-right white"></i></span>';
							$html .= '</div>';
						$html .= '</a>';

					endif;
					$html .= '</div>';
				$html .= '</div>';
				endif;
			}
		} else {

			$images = explode(',', $images);

			if(is_array($images) && !empty($images)) :
				foreach ($images as $img_id) {
					$img = wp_get_attachment_url( $img_id );

					$html .= '<div class="bf-carousel-single-slide -immagini">';
						$html .= '<div style="background-image:url('.$img.')"><img src="'.$img.'" alt="" width="150" height=150"></div>';
					$html .= '</div>';
				}
			endif;

		}

		$html .= '</div>';
		
		return $html;
    }
}

new BF_Carousel();
