<?php

class BF_Milestones extends WPBakeryShortCode {

    function __construct() {
        add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
        add_shortcode( 'bf_milestones', array( $this, 'bf_milestones_shortcode' ) );
    }        

    public function create_shortcode() {
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }        

    vc_map( array(
        'name'				=> 'Milestones',
        'base'				=> 'bf_milestones',
        'icon' 				=> get_template_directory_uri() . '/app/Theme/js_composer/vc_extend/BF.png',
        'category' 			=> 'BF Elements',
		'admin_enqueue_css' => array( get_stylesheet_directory_uri() . '/app/Theme/js_composer/vc_extend/vc.css' ),
        'params'  			=> array(
			array(
                'type' => 'param_group',
				'value' => '',
				'param_name' => 'milestones',
				'heading' =>'Inserisci milestone',
				// Note params is mapped inside param-group:
				'params' => array(
                    array(
                        'type'          => 'textfield',
                        'heading'       => 'Numero',
                        'param_name'    => 'milestone_number',
                        'admin_label' 	=> true,
                        'value'         => intval(''),
                        'description'   => 'Inserisci il numero da animare',
                    ),
                    array(
                        'type'          => 'textfield',
                        'heading'       => 'Suffisso',
                        'param_name'    => 'milestone_suffix',
                        'admin_label' 	=> true,
                        'value'         => __( '', 'san-carlo-theme' ),
                        'description'   => 'Inserisci un suffisso al numero (Es. +)',
                    ),
                    array(
                        'type'          => 'textfield',
                        'heading'       => 'Descrizione',
                        'param_name'    => 'milestone_description',
                        'admin_label' 	=> true,
                        'value'         => __( '', 'san-carlo-theme' ),
                        'description'   => '',
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
                ),
            )
        )
    ) );

}

    public function bf_milestones_shortcode( $atts, $content = null ) {
		$milestones = $milestone_number = $milestone_suffix = $milestone_description = $id_elem = $extra_class = '';
		extract(shortcode_atts( array(
            'milestones' => '',
			'milestone_number' => '',
			'milestone_suffix' => '',
			'milestone_description' => '',
			'id_elem' => '',
			'extra_class' => '',
			), $atts ));

            $miles = vc_param_group_parse_atts( $atts['milestones'] );


		$html  = '<div class="bf-milestones">';
        
        foreach ( $miles as $mile ) {

            $class = isset($mile['extra_class']) ? $mile['extra_class'] : '';
            $id = isset($mile['id_elem']) ? ' id="'.$mile['id_elem'].'"' : '';

            $html .= '<div'.$id.' class="milestone '.$class.'">';
            $html .= '<span class="milestone-number" data-number="'.$mile['milestone_number'].'">0</span>';
		
            if ( isset($mile['milestone_suffix']) ) {
                $html .= '<span class="milestone-suffix">'.$mile['milestone_suffix'].'</span>';
            }

            $html .= '<div class="line"></div>';

            if ( isset($mile['milestone_description']) ) {
                $html .= '<p class="milestone-desc">'.$mile['milestone_description'].'</p>';
            }
            
            $html .= '</div>';
        }

		$html .= '</div>';
		
		return $html;
    }
}

new BF_Milestones();
