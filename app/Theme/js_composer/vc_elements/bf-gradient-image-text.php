<?php

class BF_Image_Gradient_Text extends WPBakeryShortCode {

    function __construct() {
        add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
        add_shortcode( 'bf_image_gradient_text', array( $this, 'bf_image_gradient_text_shortcode' ) );
    }        

    public function create_shortcode() {
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }        

    vc_map( array(
        'name'				=> 'Immagine con testo e sovrapposizione colore',
        'base'				=> 'bf_image_gradient_text',
        'icon' 				=> get_template_directory_uri() . '/app/Theme/js_composer/vc_extend/BF.png',
        'category' 			=> 'BF Elements',
		'admin_enqueue_css' => array( get_stylesheet_directory_uri() . '/app/Theme/js_composer/vc_extend/vc.css' ),
        'params'  			=> array(
			array(
				"type" => "attach_image",
				"class" => "",
				"heading" => 'Aggiungi Immagine',
				"value" => "",
				"param_name" => "image",
				'save_always' => true,
				'admin_label' => true,
				"description" => ""
			),
			array(
                'type'          => 'textarea',
                'heading'       => 'Inserisci Testo',
                'param_name'    => 'text',
				'save_always' => true,
				'admin_label' => true,
                'value'         => __( '', 'san-carlo-theme' ),
                'description'   => '',
            ),
			array(
				'type' => 'dropdown',
				'heading' => 'Intestazione',
				'param_name' => 'title_heading',
				'save_always' => true,
				'admin_label' => false,
				'value' => array(
					'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div',
				),
				'std' => 'div', // Your default value
			),
			array(
				'type' => 'dropdown',
				'heading' => 'Colore di Sovrapposizione',
				'param_name' => 'overcolor_accent',
				'save_always' => true,
				'admin_label' => false,
				'value' => array(
					'Standard' => 'titles',
					'Rosso Primario' => 'primary',
					'Testo' => 'text',
					'Bianco' => 'white',
					'Custom' => 'custom',
				),
				'std' => 'primary', // Your default value
			),
			array(
				"type" => "colorpicker",
				"class" => "",
				'save_always' => true,
				"heading" => 'Colore di Sovrapposizione',
				'admin_label' => false,
				"param_name" => "overcolor_accent_custom",
				'dependency' => array(
					'element' => 'overcolor_accent',
					'value' => 'custom',
				),
				'description' => 'Scegli un colore',
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

    public function bf_image_gradient_text_shortcode( $atts, $content = null ) {
		$image = $text = $title_heading = $overcolor_accent = $overcolor_accent_custom = $id_elem = $extra_class = '';
		extract(shortcode_atts( array(
			'image' => '',
			'text' => '',
			'title_heading' => '',
			'overcolor_accent' => '',
			'overcolor_accent_custom' => '',
			'id_elem' => '',
			'extra_class' => '',
			), $atts ));
	
		$color_class	= $overcolor_accent != 'custom' ? $overcolor_accent : '';
		$custom_color	= $overcolor_accent == 'custom' ? 'background: linear-gradient(180deg, rgba(224,0,77,0) 0%, '.$overcolor_accent_custom.' 100%);' : '';
		$image_bg = $image ? ' background-image: url('.wp_get_attachment_image_url( $image, 'large' ).');' : '';


		$html  = '<div id="'.$id_elem.'" class="bf-image-overcolor '.$extra_class.' '. $color_class .'" style="'.$image_bg.'">';

		$html .= '<div class="wrapper" style="'.$custom_color.'">';

		if ( $text ) {
			$html .= '<'.$title_heading.'>';
			$html .= $text;
			$html .= '</'.$title_heading.'>';
		}

		$html .= '</div>';
		$html .= '</div>';
		
		return $html;
    }
}

new BF_Image_Gradient_Text();
