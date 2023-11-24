<?php

class BF_Quotes extends WPBakeryShortCode {

    function __construct() {
        add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
        add_shortcode( 'bf_quotes', array( $this, 'bf_quotes_shortcode' ) );
    }        

    public function create_shortcode() {
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }        

    vc_map( array(
        'name'				=> 'Citazione',
        'base'				=> 'bf_quotes',
        'icon' 				=> get_template_directory_uri() . '/app/Theme/js_composer/vc_extend/BF.png',
        'category' 			=> 'BF Elements',
		'admin_enqueue_css' => array( get_stylesheet_directory_uri() . '/app/Theme/js_composer/vc_extend/vc.css' ),
        'params'  			=> array(
			array(
                'type'          => 'textarea',
                'heading'       => 'Testo citazione',
                'param_name'    => 'bf_quote',
                'value'         => __( '', 'san-carlo-theme' ),
                'description'   => '',
            ),
			array(
                'type'          => 'textfield',
                'heading'       =>  'Autore citazione',
                'param_name'    => 'quote_author',
				'save_always' => true,
				'admin_label' => true,
                'value'         => __( '', 'san-carlo-theme' ),
                'description'   => '',
            ),
            array(
				'type' => 'vc_link',
				'holder' => '',
				'class' => 'link',
				'heading' => 'Link',
				'param_name' => 'link',
				'admin_label' => true,
				'value' => __('', 'san-carlo-theme'),
				'description' => 'Inserisci un link per il bottone',
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

    public function bf_quotes_shortcode( $atts, $content = null ) {
		$bf_quote = $quote_author = $link = $id_elem = $extra_class = '';
		extract(shortcode_atts( array(
			'bf_quote' => '',
			'quote_author' => '',
            'link' => '',
			'id_elem' => '',
			'extra_class' => '',
			), $atts ));

            $link_b = vc_build_link( $link );
            $url = esc_url( $link_b['url'] );

            $url_b = !empty($link_b) ? ' href="'.$url.'" target="_blank"' : '';

		$html  = '<div id="'.$id_elem.'" class="bf-quote '.$extra_class.'">';
		$html .= '<div class="wrapper">';
		$html .= '<a '.$url_b.'>';
        $html .= $bf_quote;
        $html .= '</a>';
		$html .= '<span class="author">'. $quote_author .'</span>';
		$html .= '</div>';
		$html .= '</div>';
		
		return $html;
    }
}

new BF_Quotes();
