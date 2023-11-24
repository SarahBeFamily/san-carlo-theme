<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a class="bf-link titles icon-arrow-right icon-arrow-right-primary" href="%s">%s</a>', get_permalink(), __('Continua a leggere', 'san-carlo-theme'));
});

/**
 * Truncate excerpt and add "…" to the end.
 *
 * @return string
 */
add_filter('shorten_excerpt', function () {
    return mb_strimwidth(get_the_excerpt(), 0, 66, '&hellip;');
});

/**
 * Truncate title and add "…" to the end.
 *
 * @return string
 */
add_filter('shorten_title', function () {
    return mb_strimwidth(get_the_title(), 0, 66, '&hellip;');
});


/*
 * Add Sortable category columns
 */
add_filter('manage_edit-page_sortable_columns', function ( $columns ) {
	$columns['taxonomy-categoria-pagina'] = 'categoria-pagina';
	return $columns;
} );

/**
 * Filter pages
 */
add_action( 'restrict_manage_posts', function () {
    global $typenow;
  
    if( $typenow == "spettacoli" || $typenow == "post" )
      return;

    if(ICL_LANGUAGE_CODE !== 'it')
      return;
  
    $tax_slug = 'categoria-pagina';
    $tax_obj = get_taxonomy($tax_slug);
    $tax_name = $tax_obj->labels->name;
    $terms = get_terms($tax_slug);

    echo '<select id="'.$tax_slug.'" class="postform" name="'.$tax_slug.'">';
    echo '<option value="">Tutte le Categorie</option>';

    foreach ($terms as $term) {
        $selected = isset($_GET[$tax_slug]) && sanitize_text_field($_GET[$tax_slug]) == $term->slug ? 'selected="selected" ' : '';
        echo '<option '.$selected.'value="'. $term->slug .'">'.$term->name.' ('.$term->count.')</option>';
    }

    echo '</select>';
        
  } );

  // Add active class to nav menu
	
	add_filter('nav_menu_css_class' , function ($classes, $item){
		if ( in_array('current-menu-item', $classes) ){
			$classes[] = 'active ';
		}
		return $classes;
	} ,10 ,2);

	/**
	 * Add a parent CSS class for nav menu items.
	 *
	 * @param array  $items The menu items, sorted by each menu item's menu order.
	 * @return array (maybe) modified parent CSS class.
	 */
	add_filter( 'wp_nav_menu_objects', function ( $items ) {
		$parents = array();

		// Collect menu items with parents.
		foreach ( $items as $item ) {
			if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
				$parents[] = $item->menu_item_parent;
			}
		}

		// Add class.
		foreach ( $items as $item ) {
			if ( in_array( $item->ID, $parents ) ) {
				$item->classes[] = 'menu-parent-item';
			}
		}
		return $items;
	} );

	/**
	 * Filters the CSS class(es) applied to a menu list element.
	 *
	 * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
	 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
	 * @param int      $depth   Depth of menu item. Used for padding.
	 * @return string[]
	 */
	add_filter( 'nav_menu_submenu_css_class', function ( $classes, $args, $depth ) {
		// Here we can additionally use menu arguments.
		if ( 'primary' === $args->theme_location ) {
			$default_class_name_key = array_search( 'sub-menu', $classes );
			if ( false !== $default_class_name_key ) {
				unset( $classes[ $default_class_name_key ] );
			}
			$classes[] = 'main-submenu';
			$classes[] = "depth-{$depth}";
			$classes[] = "closed";
		}

		return $classes;
	}, 10, 3 );

	/**
	 * Add meta your meta field to the allowed values of the REST API orderby parameter
	 *
	 * @param [type] $current_vars
	 * @return void
	 */
	$post_type ="spettacoli";
	add_filter(
		'rest_' . $post_type . '_collection_params',
		function( $params ) {
			$fields = ["data_inizio", "data_fine"];
			foreach ($fields as $key => $value) {
				$params['orderby']['enum'][] = $value;
			}
			$filters = ['annullato'];
			foreach ($filters as $key => $value) {
				$params['annullato'][] = $value;
			}
			return $params;
		},
		10,
		1
	);

	add_filter(
		'rest_' . $post_type . '_query',
		function ( $args, $request ) {
			$fields = ["data_inizio", "data_fine"];
			$values = [true];
			$order_by = $request->get_param( 'orderby' );
			$filter = $request->get_param( 'annullato' );

			if ( isset( $order_by ) && in_array($order_by, $fields)) {
				// $args['meta_key'] = $order_by;
				// $args[$fields.'__order_by']  = 'meta_value'; // user 'meta_value_num' for numerical fields
				// $args['meta_compare'] = '>=';
				// $args['meta_query'][$fields.'__order_by']['key'] = $order_by;
				// $args['meta_query'][$fields.'__order_by']['value'] = date('Ymd', strtotime('now'));
				// $args['meta_query'][$fields.'__order_by']['compare'] = '>=';
				// $args['meta_query'][$fields.'__order_by']['type'] = 'DATE';
				$args['meta_key'] = $order_by;
				// $args['meta_query']['relation'] = 'AND';
				// $args['meta_query'][1]['key'] = 'data_inizio';
				// $args['meta_query'][0]['value'] = date('Ymd', strtotime('now'));
				// $args['meta_query'][0]['compare'] = '>=';
				// $args['meta_query'][0]['type'] = 'DATE';
				$args['meta_query']['key'] = 'data_fine';
				$args['meta_query']['value'] = date('Ymd', strtotime('now'));
				$args['meta_query']['compare'] = '>';
				$args['meta_query']['type'] = 'DATE';
			}

			if (isset( $filter ) && in_array($filter, $values)) {
				$args['meta_key'] = 'annullato';
				$args['meta_value'] = true;
			}
			return $args;
		},
		10,
		2
	);

	/**
	 * Edit woocommerce notices
	 */

	 // Item removed from cart
	 // woocommerce_cart_item_removed_notice_type

	 add_filter('wc_add_to_cart_message', 'edit_message', 10, 2);
		function edit_message($message, $product_id) {
			return "Thank you for adding product" . $product_id;
		}