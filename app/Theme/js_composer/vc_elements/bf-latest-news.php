<?php

class BF_Latest_News extends WPBakeryShortCode {

    function __construct() {
        add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
        add_shortcode( 'bf_latest_news', array( $this, 'bf_latest_news_shortcode' ) );
    }        

    public function create_shortcode() {
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }        


		vc_map( array(
			'name'				=> 'Ultime News',
			'base'				=> 'bf_latest_news',
			'icon' 				=> 'bf-ico',
			'category' 			=> 'BF Elements',
			'admin_enqueue_css' => array( get_stylesheet_directory_uri() . '/app/Theme/vc_extend/vc.css' ),
			'params'  			=> array(
				array(
					'type'          => 'textfield',
					'heading'       => 'Numero di articoli da visualizzare',
					'param_name'    => 'numero_post',
					'value'         => '3',
					'description'   => 'Inserisci in cifre quanti articoli vuoi visualizzare',
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

    public function bf_latest_news_shortcode( $atts, $content = null ) {
		$numero_post = $id_elem = $extra_class = '';
		extract( shortcode_atts( array(
			'numero_post' => '3',
			'id_elem' => '',
			'extra_class' => '',
			), $atts ));

			$nposts = $numero_post != '' ? intval($numero_post) : 3;
			$posts = get_posts(array(
				'public' => true,
				'_builtin' => false,
				'post_type' => 'post',
				'numberposts' => $nposts,
				'posts_per_page' => $nposts,
				'order' => 'DESC',
				'suppress_filters' => false,
			));
		
		$id = $id_elem ? ' id="'.$id_elem.'" ' : '';	

		$html  = '';
		$html .= '<div'.$id.' class="bf-latest-news '.$extra_class.'">';

		foreach ($posts as $post) {

			$giorno = get_the_date('j', $post->ID);
			$mese = substr(get_the_date( 'F', $post->ID ), 0, 3);
			$anno = get_the_date('Y', $post->ID );

			$cats = array();
			
			$terms = get_the_terms( $post->ID, 'category' );
			foreach ($terms as $term) {
				$cats['id'] = $term->term_id;
				$cats['name'] = $term->name;
				$cats['image'] = get_field('featured_img', $term->taxonomy.'_'.$term->term_id);
			}
			
			$img = get_the_post_thumbnail_url($post->ID, 'medium' ) ? get_the_post_thumbnail_url($post->ID, 'medium' ) : $cats['image'];

			$html .= '<a class="single" href="'.get_permalink($post->ID).'" title="'. get_the_title( $post->ID) .'">';

				$html .= '<div class="meta">';
					$html .= '<span class="date">'.$giorno.' '.$mese.' '.$anno.'</span>';
					$html .= '<span class="category">'.$cats['name'].'</span>';
				$html .= '</div>';
				$html .= '<div class="image" style="background-image: url('.$img.');"></div>';
				$html .= '<div class="title-wrap">';
					$html .= '<span class="title">'. get_the_title($post->ID ) .'</span>';
				$html .= '</div>';

			$html .= '</a>';
		}

		$html .= '</div>';
		
		return $html;
    }
}

new BF_Latest_News();
