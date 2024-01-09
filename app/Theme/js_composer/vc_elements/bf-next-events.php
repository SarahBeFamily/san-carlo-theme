<?php

class BF_Next_Events extends WPBakeryShortCode {

    function __construct() {
        add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
        add_shortcode( 'bf_next_events', array( $this, 'bf_next_events_shortcode' ) );
    }        

    public function create_shortcode() {
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }        


		vc_map( array(
			'name'				=> 'Widget Prossimi Spettacoli',
			'base'				=> 'bf_next_events',
			'icon' 				=> 'bf-ico',
			'category' 			=> 'BF Elements',
			'admin_enqueue_css' => array( get_stylesheet_directory_uri() . '/app/Theme/vc_extend/vc.css' ),
			'params'  			=> array(
				array(
					'type'          => 'textarea',
					'heading'       => 'Inserisci Titolo Sezione',
					'param_name'    => 'title',
					'save_always' => true,
					'admin_label' => true,
					'value'         => __( '', 'san-carlo-theme' ),
					'description'   => '',
				),
				array(
					'type'          => 'textfield',
					'heading'       => 'Numero di articoli da visualizzare',
					'param_name'    => 'numberposts',
					'value'         => '3',
					'description'   => 'Inserisci in cifre quanti eventi vuoi visualizzare',
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

    public function bf_next_events_shortcode( $atts, $content = null ) {
		$title = $numberposts = $id_elem = $extra_class = '';
		extract( shortcode_atts( array(
			'title' => '',
			'numberposts' => '4',
			'id_elem' => '',
			'extra_class' => '',
			), $atts ));

			$numberposts = $numberposts != '' ? intval($numberposts) -1 : 4;
			$posts = get_posts(array('post_type' => 'spettacoli'));

		$eventi = array();
		$eventi_page = get_field('eventi_page', 'options') ? get_field('eventi_page', 'options') : '#';
		
		$id = $id_elem ? ' id="'.$id_elem.'" ' : '';	

		$html  = '';
		$html .= '<div'.$id.' class="bf-next-events '.$extra_class.'">';

			if ( $title != '') $html .= '<h3>'.$title.'</h3>';

		// $eventi_arr = json_decode(file_get_contents(get_home_url().'/wp-json/wp/v2/events-datetime'), true);
		$request = new WP_REST_Request( 'GET', '/wp/v2/events-datetime' );
		$request->set_query_params( [ 'per_page' => 999 ] );
		$response = rest_do_request( $request );
		$server = rest_get_server();
		$data = $server->response_to_data( $response, false );
		// $json = wp_json_encode( $data );
		
		// var_dump($data);
		$counter_eventi = 0;
		foreach ($data as $evento_array) {

			foreach ($evento_array as $data => $arr) {

				if ($counter_eventi < $numberposts) :

				foreach ($arr as $id => $evento) {
					$counter_eventi++;
					$dateStart_array = $data ? explode('/', $data) : array();
					$dateMonth = wp_date('F', strtotime(str_replace('/', '-', $data)));

					if (!empty($dateStart_array)) {
						$data_n = $dateStart_array[2];
						$data_mese = $dateMonth; //substr($dateMonth, 0, 3);
						$data_Y = $dateStart_array[0];
					}

					$link = ! is_null($evento['ticket_link']) ? ' href="'.$evento['ticket_link'].'"' : ' href="#"';

					$html .= '<div class="single">';

						$html .= '<div class="image" style="background-image: url('.$evento['featured_image'].');"></div>';

						$html .= '<div class="meta">';
							$html .= '<span class="info"><i class="bf-icon icon-calendar"></i> '.$data_n.' '.$data_mese.' '.$data_Y.'</span>';
							$html .= '<span class="title">'. $evento['titolo'] .'</span>';
						$html .= '</div>';

						$html .= '<div class="info-wrap">';
							$html .= '<span class="info"><i class="bf-icon icon-pin"></i> '.$evento['location'].'</span>';
							/* translators: %s è l'orario dello spettacolo */
							$html .= '<span class="info"><i class="bf-icon icon-clock"></i> '.sprintf(__('%s', 'san-carlo-theme'), $evento['orario']).'</span>';
						$html .= '</div>';
						
						$html .= '<div class="buttons-area">';
							$html .= '<a class="bf-btn primary icon-ticket"'.$link.'>'.__('Buy tickets', 'san-carlo-theme').'</a>';
							$html .= '<a class="bf-link primary" href="'.$evento['permalink'].'">'.__('Discover more', 'san-carlo-theme').'</a>';
						$html .= '</div>';

					$html .= '</div>';
				}

				endif;
			}

			
		}

		$html .= '<a class="bf-link titles icon-arrow-right icon-arrow-right-primary" role="button" href="'.$eventi_page.'">'.__('See all upcoming shows', 'san-carlo-theme').'</a>';

		$html .= '</div>';
		
		return $html;
    }
}

new BF_Next_Events();
