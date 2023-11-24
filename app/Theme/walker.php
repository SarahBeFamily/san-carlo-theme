<?php

class SCarlo_Walker extends Walker_Nav_Menu {
    
	// Displays start of an element. E.g '<li> Item Name'
    // @see Walker::start_el()
    function start_el(&$output, $item, $depth=0, $args=array(), $id = 0) {
    	$object = $item->object;
    	$type = $item->type;
    	// $title = $item->title;
    	$description = $item->description;
        $permalink = $item->url;
        $img = get_field('featured_img', $item);

        $attributes  = '';
        !empty( $item->attr_title ) && $attributes .= ' title="'. esc_attr( $item->attr_title ) .'"';
        !empty( $item->target ) && $attributes .= ' target="'. esc_attr( $item->target     ) .'"';
        !empty( $item->xfn ) && $attributes .= ' rel="'. esc_attr( $item->xfn        ) .'"';
        !empty( $item->url ) && $attributes .= ' href="'. esc_attr( $permalink ) .'"';

        $title = apply_filters( 'the_title', $item->title, $item->ID );

        $item_output = $args->before;
        $item_output .= '<li id="main-el-'. $item->ID .'" class="'.implode(' ', $item->classes) .'">';

        if (in_array('menu-item-has-children', $item->classes)) {
            $item_output .= '<div class="nav-foto parent" style="background-image:url('.$img.');"></div>';
        }

        if ($depth == 1) {
            $item_output .= '<div class="nav-foto" style="background-image:url('.$img.');"></div>';
        }
        
        $item_output .= $args->link_before
        . '<a class="menu__item" '. $attributes .'>'. $title . '</a>'
        . $args->link_after
        . $args->after;
        // . '</li>';

        // var_dump($item_output);

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

class SCarlo_Walker_Mobile extends Walker_Nav_Menu {
    
	// Displays start of an element. E.g '<li> Item Name'
    // @see Walker::start_el()
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class='main-submenu depth-{$depth} closed'>
        <button type='button' class='back-to-parent'>
            <span class='chevron'></span>
            <span>Back</span>
        </button>
        <span class='parent-title'></span>\n";
    }
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
}