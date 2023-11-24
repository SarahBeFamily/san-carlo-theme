<?php

class BF_Event_Calendar extends WPBakeryShortCode {

    function __construct() {
        add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
        add_shortcode( 'bf_event_calendar', array( $this, 'bf_event_calendar_shortcode' ) );
    }        

    public function create_shortcode() {
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }        


		vc_map( array(
			'name'				=> 'Calendario Eventi',
			'base'				=> 'bf_event_calendar',
			'icon' 				=> 'bf-ico',
			'category' 			=> 'BF Elements',
			'admin_enqueue_css' => array( get_stylesheet_directory_uri() . '/app/Theme/vc_extend/vc.css' ),
			'params'  			=> array(
				array(
					'type'          => 'textfield',
					'heading'       =>'ID',
					'param_name'    => 'id_elem',
					'value'         => '',
					'description'   => 'Imposta un ID all\'elemento',
				),
				array(
					'type'          => 'textfield',
					'heading'       => 'Classe extra',
					'param_name'    => 'extra_class',
					'value'         => '',
					'description'   => 'Aggiungi una classe extra all\'elemento',
				),
			)
		) );
	}

    public function bf_event_calendar_shortcode( $atts, $content = null ) {
		$id_elem = $extra_class = '';
		extract(shortcode_atts( array(
			'id_elem' => '',
			'extra_class' => '',
			), $atts ));
	
			$calendar = new Calendar();

			$id = $id_elem ? ' id="'.$id_elem.'" ' : '';

		$html  = '';
		$html .= '<div'.$id.' class="bf-calendar-wrap '.$extra_class.'">';
			$html .= $calendar->show();
		$html .= '</div>';
		
		return $html;
    }
}

new BF_Event_Calendar();
