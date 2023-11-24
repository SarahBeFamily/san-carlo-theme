<?php

class BF_Titles extends WPBakeryShortCode {

    function __construct() {
        add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
        add_shortcode( 'bf_titles', array( $this, 'bf_titles_shortcode' ) );
    }        

    public function create_shortcode() {
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }        

    vc_map( array(
        'name'				=> 'Titolo',
        'base'				=> 'bf_titles',
        'icon' 				=> get_template_directory_uri() . '/app/Theme/js_composer/vc_extend/BF.png',
        'category' 			=> 'BF Elements',
		'admin_enqueue_css' => array( get_stylesheet_directory_uri() . '/app/Theme/js_composer/vc_extend/vc.css' ),
        'params'  			=> array(
			array(
                'type'          => 'textarea',
                'heading'       => 'Inserisci Testo',
                'param_name'    => 'title',
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
				'std' => 'h1', // Your default value
			),
			array(
				'type' => 'dropdown',
				'heading' => 'Grandezza',
				'param_name' => 'size',
				'save_always' => true,
				'admin_label' => false,
				'value' => array(
					'Standard' => '',
					'Piccolo' => 'small',
					'Medio' => 'medium',
					'Grande' => 'big',
				),
				'std' => '', // Your default value
				'description'   => 'Selezionando "standard" si applicheranno le grandezze proprietarie dell\'intestazione scelta',
			),
			array(
				'type' => 'dropdown',
				'heading' => 'Posizione testo',
				'param_name' => 'align',
				'save_always' => true,
				'admin_label' => false,
				'value' => array(
					'Sinistra' => '',
					'Centrato' => 'align-center',
					'Destra' => 'align-right',
				),
				'std' => '', // Your default value
			),
			array(
				'type' => 'dropdown',
				'heading' => 'Colore Testo',
				'param_name' => 'color_title_accent',
				'save_always' => true,
				'admin_label' => false,
				'value' => array(
					'Standard' => 'titles',
					'Rosso Primario' => 'primary',
					'Testo' => 'text',
					'Bianco' => 'white',
					'Custom' => 'custom',
				),
				'std' => 'titles', // Your default value
			),
			array(
				"type" => "colorpicker",
				"class" => "",
				'save_always' => true,
				"heading" => 'Colore Testo',
				'admin_label' => false,
				"param_name" => "color_title",
				'dependency' => array(
					'element' => 'color_title_accent',
					'value' => 'custom',
				),
				'description' => 'Scegli un colore',
				"value" => '',
			),
			array(
				'type' => 'dropdown',
				'heading' => 'Stile titoletto',
				'param_name' => 'pre_title_style',
				'save_always' => true,
				'admin_label' => false,
				'value' => array(
					'Standard' => 'standard',
					'Tutto maiuscolo' => 'uppercase',
					'Nessun titoletto' => 'none',
				),
				'std' => 'none', // Your default value
				'group'         => 'Titoletto',
			),
			array(
                'type'          => 'textfield',
                'heading'       => 'Testo titoletto',
                'param_name'    => 'pre_title_text',
                'value'         => __( '', 'san-carlo-theme' ),
				'dependency' => array(
					'element' => 'pre_title_style',
					'value' 	=> array('standard', 'uppercase'),
				),
                'description'   => '',
				'group'         => 'Titoletto',
            ),
			array(
				'type' => 'dropdown',
				'heading' => 'Colore Titoletto',
				'param_name' => 'pre_title_accent',
				'save_always' => true,
				'admin_label' => false,
				'dependency' => array(
					'element' => 'pre_title_style',
					'value' 	=> array('standard', 'uppercase'),
				),
				'value' => array(
					'Testo' => 'text',
					'Custom' => 'custom',
				),
				'std' => 'primary', // Your default value
				'group'         => 'Titoletto',
			),
			array(
				"type" => "colorpicker",
				"class" => "",
				'save_always' => true,
				"heading" => 'Colore Titoletto',
				'admin_label' => false,
				"param_name" => "pre_title_color",
				'dependency' => array(
					'element' => 'pre_title_accent',
					'value' => 'custom',
				),
				'description' => 'Scegli un colore',
				"value" => '',
				'group'         => 'Titoletto',
			),
			array(
				'type' => 'checkbox',
				'heading' => 'Aggiungere descrizione?',
				'param_name' => 'description',
				'save_always' => true,
				'admin_label' => false,
				'value' => '',
				'std' => '', // Your default value
				'group'         => 'Descrizione',
			),
			array(
                'type'          => 'textarea_html',
                'heading'       => 'Inserisci Descrizione',
                'param_name'    => 'descr_text',
				'save_always' 	=> true,
				'admin_label' 	=> false,
				'dependency' 	=> array(
					'element'	=> 'description',
					'value' 	=> 'true',
				),
                'value'         => __( '', 'san-carlo-theme' ),
                'description'   => 'Inserisci del testo che verrà visualizzato sotto il titolo',
				'group'         => 'Descrizione',
            ),
			array(
				'type' => 'dropdown',
				'heading' => 'Colore Descrizione',
				'param_name' => 'color_descr_accent',
				'save_always' => true,
				'admin_label' => false,
				'value' => array(
					'Standard' => 'titles',
					'Rosso Primario' => 'primary',
					'Testo' => 'text',
					'Bianco' => 'white',
					'Custom' => 'custom',
				),
				'std' => 'text', // Your default value
				'group'         => 'Descrizione',
			),
			array(
				"type" => "colorpicker",
				"class" => "",
				'save_always' => true,
				"heading" => 'Colore Descrizione',
				'admin_label' => false,
				"param_name" => "color_descr",
				'dependency' => array(
					'element' => 'color_descr_accent',
					'value' => 'custom',
				),
				'description' => 'Scegli un colore',
				"value" => '',
				'group'         => 'Descrizione',
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

    public function bf_titles_shortcode( $atts, $content = null ) {
		$pre_title_text = $pre_title_style = $pre_title_accent = $pre_title_color = $title = '';
		$title_size = $title_heading = $color_title_accent = $color_title = $size = $align = '';
		$uppercase = $description = $descr_text = $color_descr_accent = $color_descr = '';
		$id_elem = $extra_class = '';
		extract(shortcode_atts( array(
			'pre_title_text' => '',
			'pre_title_style' => '',
			'pre_title_accent' => '',
			'pre_title_color' => '',
			'title' => '',
			'title_size' => '',
			'title_heading' => '',
			'color_title_accent' => '',
			'color_title' => '',
			'size' => '',
			'align' => '',
			'uppercase' => '',
			'description' => '',
			'descr_text' => '',
			'color_descr_accent' => '',
			'color_descr' => '',
			'id_elem' => '',
			'extra_class' => '',
			), $atts ));
	
		$classes = array();
		$classes[] = $color_title_accent != 'custom' ? 'color-'.$color_title_accent : '';
		$classes[] = $uppercase == 'yes' ? 'uppercase' : '';
		$classes[] = $align;
		$classes[] = $size;
		$classes[] = $extra_class;

		$custom_color = $pre_title_accent == 'custom' && $pre_title_color != '' ? ' style="color: '.$pre_title_color.';"' : '';
		$custom_title_color = $color_title_accent == 'custom' && $color_title != '' ? ' style="color: '.$color_title.';"' : '';
		$pre_title_color_class = $color_title_accent != 'custom' ? $pre_title_accent : '';
		$custom_descr_color = $color_descr_accent == 'custom' && $color_descr != '' ? ' style="color: '.$color_descr.';"' : '';
		$descr_style = $color_descr_accent != 'custom' ? 'color-'.$color_descr_accent : '';
		$id = $id_elem ? ' id="'.$id_elem.'" ' : '';


		$html  = '<div'.$id.' class="bf-title-block '.implode(' ', $classes).'">';

			if ( $pre_title_text && $pre_title_style != 'none' ) {
				$html .= '<div>';
				$html .= '<p class="pre-title-text '.$pre_title_style.' '.$pre_title_color_class.'"'.$custom_color.'>'.$pre_title_text.'</p>';
			}

			$html .= '<'.$title_heading.' class="bf-title"'.$custom_title_color.'>';
			$html .= $title;
			$html .= '</'.$title_heading.'>';

			if ( $pre_title_text && $pre_title_style != 'none' ) {
				$html .= '</div>';
			}

			if ($description == 'true' && $descr_text) {
				$html .= '<div class="descr-text '.$descr_style.'"'.$custom_descr_color.'>'.$descr_text.'</div>';
			}

		$html .= '</div>';
		
		return $html;
    }
}

new BF_Titles();
