<?php

class BF_Parallax_Images extends WPBakeryShortCode {

    function __construct() {
        add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
        add_shortcode( 'bf_parallax_images', array( $this, 'bf_parallax_images_shortcode' ) );
    }        

    public function create_shortcode() {
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }        


		vc_map( array(
			'name'				=> 'Immagini Parallax',
			'base'				=> 'bf_parallax_images',
			'icon' 				=> 'bf-ico',
			'category' 			=> 'BF Elements',
			'admin_enqueue_css' => array( get_stylesheet_directory_uri() . '/app/Theme/vc_extend/vc.css' ),
			'params'  			=> array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => 'Immagine Principale',
					"value" => "",
					"param_name" => "main_image",
					'save_always' => true,
					'admin_label' => false,
					"description" => "",
				),
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => 'Immagine in Movimento',
					"value" => "",
					"param_name" => "image_move",
					'save_always' => true,
					'admin_label' => false,
					"description" => "",
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

    public function bf_parallax_images_shortcode( $atts, $content = null ) {
		$main_image = $image_move = $id_elem = $extra_class = '';
		extract(shortcode_atts( array(
			'main_image' => '',
			'image_move' => '',
			'id_elem' => '',
			'extra_class' => '',
			), $atts ));
	

		$first_img = wp_get_attachment_image( $main_image, 'large', "", array( "class" => "img-main" ));
		$move_img = wp_get_attachment_image( $image_move, 'large', "", array( "class" => "img-move" ));

		$id = $id_elem ? ' id="'.$id_elem.'" ' : '';	

		$html  = '';
		$html .= '<div'.$id.' class="bf-parallax-image '.$extra_class.'">';
		$html .= $first_img;
		$html .= $move_img;
		$html .= '</div>';
		
		return $html;
    }
}

new BF_Parallax_Images();
