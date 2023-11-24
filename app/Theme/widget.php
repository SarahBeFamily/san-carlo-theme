<?php

// Creating the widget 
class BF_Buttons_Widget extends \WP_Widget {
 
    function __construct() {
        parent::__construct(
        
        // Base ID of your widget
        'bf_buttons_widget', 
        
        // Widget name will appear in UI
        __('Bottone', THEMEDOMAIN), 
        
        // Widget description
        array( 'description' => __( 'Inserisci un bottone', THEMEDOMAIN ), )
        );
	}


    // Widget Backend 
    public function form( $instance ) {

        $text    = isset($instance['text']) ? $instance['text'] : __( 'Testo del bottone', THEMEDOMAIN );
        $url     = isset($instance['url']) ? $instance['url'] : '#';
        $arrow   = isset($instance['arrow']) ? $instance['arrow'] : 'off';
        $attr    = isset($instance['attr']) ? $instance['attr'] : 'off';


        // Widget admin form
        $html = '<p>
                <label for="'. $this->get_field_id( 'text' ).'">'. __( 'Testo del bottone:', THEMEDOMAIN ) .'</label> 
                <input class="widefat" id="'. $this->get_field_id( 'text' ) .'" name="'.$this->get_field_name( 'text' ).'" type="text" value="'. esc_attr( $text ) .'" />
            </p>';

        $html .= '<p>
                <label for="'. $this->get_field_id( 'url' ) .'">'. __( 'Link:', THEMEDOMAIN ) .'</label> 
                <input class="widefat" id="'. $this->get_field_id( 'url' ) .'" name="'. $this->get_field_name( 'url' ) .'" type="text" value="'. esc_attr( $url ) .'" />
            </p>';

        $html .= '<p>
                <input class="checkbox" type="checkbox" '.checked( $arrow, 'on' ).' id="'. $this->get_field_id( 'arrow' ) .'" name="'. $this->get_field_name( 'arrow' ) .'" />
                <label for="'. $this->get_field_id( 'arrow' ) .'">'. __( 'Inserire l\'icona della freccia?', THEMEDOMAIN ) .'</label> 
            </p>';

        $html .= '<p>
                <input class="checkbox" type="checkbox" '. checked( $attr, 'on' ).' id="'. $this->get_field_id( 'attr' ) .'" name="'. $this->get_field_name( 'attr' ) .'" />
                <label for="'. $this->get_field_id( 'attr' ) .'">'. __( 'Aprire il link in una nuova scheda?', THEMEDOMAIN ) .'</label> 
            </p>';

        echo $html;
    
    }
    

    // Creating widget front-end
    public function widget( $args, $instance ) {

        extract( $args );

        $text       = apply_filters( 'widget_text', $instance['text'] );
        $url        = apply_filters( 'widget_url', $instance['url'] );
        $arrow      = apply_filters( 'widget_arrow', $instance['arrow'] );
        $attr       = apply_filters( 'widget_attr', $instance['attr'] );

        $text_btn 	= esc_html( $text );
		$link_btn   = esc_url( $url );

		$class      = $arrow == 'on' ? 'icon-arrow-right' : '';
        $target     = $attr == 'on' ? 'target="_blank"' : '';
    
        echo $args['before_widget'];

		echo '<a class="bf-btn white '.$class.'" '.$target.' role="button" href="'.$link_btn.'">';
		echo $text_btn;
		echo '</a>';
		                                
        echo $args['after_widget'];
    }
        
     
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['text']   = ( ! empty( $new_instance['text'] ) ) ? strip_tags( $new_instance['text'] ) : '';
        $instance['url']    = ( ! empty( $new_instance['url'] ) ) ? esc_url( $new_instance['url'] ) : '#';
        $instance['arrow']  = $new_instance['arrow'];
        $instance['attr']   = $new_instance['attr'];

        return $instance;
    }

} // Class bf_widget ends here
