<?php

class BF_Link extends WPBakeryShortCode {

    function __construct() {
        add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
        add_shortcode( 'bf_link', array( $this, 'bf_link_shortcode' ) );
    }        

    public function create_shortcode() {
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }        

    vc_map( array(
        'name'				=> 'Link con freccia',
        'base'				=> 'bf_link',
        'icon' 				=> 'bf-ico',
        'category' 			=> 'BF Elements',
		'admin_enqueue_css' => array( get_stylesheet_directory_uri() . '/app/Theme/vc_extend/vc.css' ),
        'params'  			=> array(
			array(
				'type' => 'vc_link',
				'holder' => '',
				'class' => 'text_link_button',
				'heading' => 'Link e testo',
				'param_name' => 'link',
				'admin_label' => true,
				'value' => __( '', 'san-carlo-theme' ),
				'description' => 'Inserisci un testo e un link per il bottone',
			),
			array(
				'type' => 'checkbox',
				'holder' => '',
				'class' => '',
				'heading' => 'Aggiungere icona freccia?',
				'param_name' => 'right_icon_thin',
				'admin_label' => false,
				'value' => true,
				'description' => 'Flagga per aggiungere una freccia alla destra del testo',
			),
			array(
				'type' => 'dropdown',
				'heading' => 'Colore Freccia',
				'param_name' => 'arrow_color',
				'save_always' => true,
				'admin_label' => false,
				'dependency' => array(
					'element' => 'right_icon_thin',
					'value' => 'true',
				),
				'value' => array(
					'Primary' => 'primary',
					'Colore Testi' => 'text',
					'Colore Titoli' => 'titles',
					'White' => 'white',
				),
				'std' => 'primary',
			),
			array(
				'type' => 'dropdown',
				'heading' => 'Colore Testo',
				'param_name' => 'color_text_accent',
				'save_always' => true,
				'admin_label' => false,
				'value' => array(
					'Primary' => 'primary',
					'Colore Testi' => 'text',
					'Colore Titoli' => 'titles',
					'White' => 'white',
				),
				'std' => 'primary',
			),
			array(
				'type' => 'dropdown',
				'heading' => 'Decorazione Testo',
				'param_name' => 'deco_text',
				'save_always' => true,
				'admin_label' => false,
				'value' => array(
					'Standard' => 'standard',
					'Sottolineato' => 'underline',
					'Sottolineato solo al passaggio del mouse' => 'underline_on_hover',
				),
				'std' => 'standard',
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

    public function bf_link_shortcode( $atts, $content = null ) {
		$link = $right_icon_thin = $color_text_accent = $arrow_color = $deco_text = $id_elem = $extra_class = '';
		extract(shortcode_atts( array(
			'link' => '',
			'right_icon_thin' => '',
			'color_text_accent' => '',
			'deco_text' => '',
			'arrow_color' => '',
			'id_elem' => '',
			'extra_class' => '',
			), $atts ));
	
		$link  		= vc_build_link( $link );
		$text_btn 	= esc_html($link['title']);
		$link_btn   = esc_url( $link['url'] );

		$classes = array();
		$classes[] = $deco_text != 'standard' ? $deco_text : '';
		$classes[] = $color_text_accent;
		$classes[] = $right_icon_thin == true ? 'icon-arrow-right icon-arrow-right-'.$arrow_color : '';
		$classes[] = $extra_class;

		$id = $id_elem ? ' id="'.$id_elem.'" ' : '';

		$html  = '';
		$html .= '<a'.$id.' class="bf-link '.implode(' ', $classes).'" role="button" href="'.$link_btn.'">';
		$html .= $text_btn;
		$html .= '</a>';
		
		return $html;
    }
}

new BF_Link();
