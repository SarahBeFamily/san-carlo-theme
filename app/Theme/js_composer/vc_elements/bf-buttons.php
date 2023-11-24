<?php

class BF_Buttons extends WPBakeryShortCode {

    function __construct() {
        add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
        add_shortcode( 'bf_buttons', array( $this, 'bf_buttons_shortcode' ) );
    }        

    public function create_shortcode() {
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }        

    vc_map( array(
        'name'				=> 'Bottone',
        'base'				=> 'bf_buttons',
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
				'admin_label' => false,
				'value' => '',
				'description' => 'Inserisci un testo e un link per il bottone',
			),
			array(
				'type' => 'checkbox',
				'holder' => '',
				'class' => '',
				'heading' => 'Aggiungere icona freccia?',
				'param_name' => 'right_icon',
				'admin_label' => false,
				'value' => true,
				'description' => 'Flagga per aggiungere una freccia alla destra del testo',
			),
			array(
				'type' => 'dropdown',
				'heading' => 'Colore Sfondo',
				'param_name' => 'color_bg_accent',
				'save_always' => true,
				'admin_label' => false,
				'value' => array(
					'Primary' => 'primary',
					'Colore Testi' => 'text',
					'Colore Titoli' => 'titles',
					'Bianco' => 'white',
					'Custom' => 'custom',
				),
				'std' => 'primary', // Your default value
			),
			array(
				"type" => "colorpicker",
				"class" => "",
				'save_always' => true,
				"heading" => 'Colore sfondo',
				'admin_label' => false,
				"param_name" => "color_bg",
				'dependency' => array(
					'element' => 'color_bg_accent',
					'value' => 'custom',
				),
				'description' => 'Scegli un colore di sfondo',
				"value" => '',
			),
			array(
				"type" => "colorpicker",
				"class" => "",
				'save_always' => true,
				"heading" => 'Colore testo',
				"admin_label" => false,
				"param_name" => "color_text",
				'description' => 'Scegli un colore di per il testo del bottone',
				"value" => '',
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

    public function bf_buttons_shortcode( $atts, $content = null ) {
		$link = $right_icon = $color_bg_accent = $color_bg = $color_text = $id_elem = $extra_class = '';
		extract(shortcode_atts( array(
			'link' => '',
			'right_icon' => '',
			'color_bg_accent' => '',
			'color_bg' => '',
			'color_text' => '',
			'id_elem' => '',
			'extra_class' => '',
			), $atts ));
	
		$link  		= vc_build_link( $link );
		$text_btn 	= esc_html($link['title']);
		$link_btn   = esc_url( $link['url'] );

		$classes = array();
		$classes[] = $color_bg_accent;
		$classes[] = $extra_class;

		if ($right_icon == true && $color_bg_accent !== 'primary') {
			$classes[] = 'icon-arrow-right';
		} else if ($right_icon == true && $color_bg_accent == 'primary') {
			$classes[] = 'icon-arrow-right-white';
		}

		$datas = array();
		$datas[] = $color_text != '' ? 'data-text-color="'.$color_text.'"' : '';
		$datas[] = $color_bg != '' ? 'data-bg-color="'.$color_bg.'"' : '';

		$styles = array();
		if ( $color_bg_accent == 'custom' && $color_bg != '' ) { $styles[] = 'background-color: '.$color_bg.';'; }
		if ( $color_text != '' ) { $styles[] = 'color: '.$color_text.';'; }

		$inline_style = !empty($styles) ? ' style="'.implode(' ', $styles).'"' : '';

		$id = $id_elem ? 'id="'.$id_elem.'" ' : '';

		$html  = '';
		$html .= '<a '.$id.'class="bf-btn '.implode(' ', $classes).'" '.implode(' ', $datas).$inline_style.' role="button" href="'.$link_btn.'">';
		$html .= $text_btn;
		$html .= '</a>';
		
		return $html;
    }
}

new BF_Buttons();
