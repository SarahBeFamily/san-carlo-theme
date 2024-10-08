<?php
// Aggiungo la funzione di ordinamento per categoria
add_action('pre_get_posts', 'add_category_column_orderby');
function add_category_column_orderby($query)
{
	if (!is_admin() || !$query->is_main_query()) {
		return;
	}

	if ($query->get('orderby') == 'taxonomy') {
		$query->set('meta_key', 'data_fine');
		$query->set('orderby', 'meta_value');
	}
}

// Aggiungo la colonna fine data allo spettacolo in admin
add_filter('manage_edit-spettacoli_columns', 'add_end_date_column');
function add_end_date_column($columns)
{
	$columns['data_fine'] = 'Data Fine';
	return $columns;
}

add_action('manage_spettacoli_posts_custom_column', 'add_end_date_column_content', 10, 2);
function add_end_date_column_content($column, $post_id)
{
	if ($column == 'data_fine') {
		$end_date = get_post_meta($post_id, 'data_fine', true);
		echo wp_date('d/m/Y', strtotime($end_date));
	}
}

// Aggiungo la funzione di ordinamento per data
add_filter('manage_edit-spettacoli_sortable_columns', 'add_end_date_column_sortable');
function add_end_date_column_sortable($columns)
{
	$columns['data_fine'] = 'data_fine';
	return $columns;
}

add_action('pre_get_posts', 'add_end_date_column_orderby');
function add_end_date_column_orderby($query)
{
	if (!is_admin() || !$query->is_main_query()) {
		return;
	}

	if ($query->get('orderby') == 'data_fine') {
		$query->set('meta_key', 'data_fine');
		$query->set('orderby', 'meta_value');
	}
}

// Aggiungo la colonna per lo spettacolo relazionato vivaticket
add_filter('manage_edit-spettacoli_columns', 'add_vivaticket_column');
function add_vivaticket_column($columns)
{
	$columns['vivaticket'] = 'Vivaticket';
	return $columns;
}

add_action('manage_spettacoli_posts_custom_column', 'add_vivaticket_column_content', 10, 2);
function add_vivaticket_column_content($column, $post_id)
{
	if ($column == 'vivaticket') {
		$vivaticket_id = get_post_meta($post_id, 'prodotto_relazionato', true);
		
		if ($vivaticket_id !== '') {
			$vivaticket = is_plugin_active('stc-tickets/stc-tickets.php') && function_exists('stcticket_spettacolo_data') ? stcticket_spettacolo_data($vivaticket_id) : null;
			
			if ($vivaticket === null) {
				echo 'Prodotto non trovato';
			} else {
				echo $vivaticket['titolo'];
			}
			
		} else {
			echo 'Nessun prodotto relazionato';
		}
	}
}

/**
 * Save dates in meta for spettacoli when saving post
 */
function save_spettacoli_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $post = get_post($post_id);
    if ($post->post_type == 'spettacoli') {
        $spettacolo_VT = get_field('prodotto_relazionato', $post_id);
        $spettacolo_data = is_plugin_active('stc-tickets/stc-tickets.php') && function_exists('stcticket_spettacolo_data') ? stcticket_spettacolo_data($spettacolo_VT) : '';
        $date = array();

        $cats = '';
        $terms = get_the_terms( $post_id, 'spettacoli_cat' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            $cats = join( ", ", array_map( function( $t ) { return $t->name; }, $terms ) );
        }

        if (isset($spettacolo_data['date']) && is_array($spettacolo_data['date'])) :
            foreach ($spettacolo_data['date'] as $dettaglio) {
                $data_ora_array = explode(' ', $dettaglio['date']);
				$data = date('Y/m/d', strtotime($data_ora_array[0])); // 2023/09/16
                $ticket = isset($dettaglio['url']) ? $dettaglio['url'] : '';
                $location = isset($spettacolo_data['location']) ? $spettacolo_data['location'] : '';

                $date[$data][$post_id]['ID'] = $post_id;
                $date[$data][$post_id]['titolo'] = get_the_title( $post_id );
                $date[$data][$post_id]['cat'] = $cats;
                $date[$data][$post_id]['permalink'] = get_permalink( $post_id );
                $date[$data][$post_id]['featured_image'] = get_the_post_thumbnail_url( $post_id, 'large' );
                $date[$data][$post_id]['featured_vertical'] = is_plugin_active('advanced-custom-fields-pro/acf.php') ? get_field( 'immagine_verticale', $post_id ) : '';
                $date[$data][$post_id]['data'] = date('d/m/Y', strtotime($data_ora_array[0])); // 16/09/2023
                $date[$data][$post_id]['orario'] = $data_ora_array[1];
                $date[$data][$post_id]['location'] = $location;
                $date[$data][$post_id]['ticket_link'] = $ticket;
            }
        endif;
        
        if ( ! empty( $date ) && $spettacolo_data !== '' ) {
            update_post_meta( $post_id, 'spettacolo_date', $date );
			
        } 
        // Test fake dates
        // else {
        //     $data = '2024/05/31';
        //     $ora = '19:30';
        //     $ticket = '';
        //     $location = 'Teatro di San Carlo';
        //     $cats = 'Musica';

        //     $date[$data][$post_id]['ID'] = $post_id;
        //     $date[$data][$post_id]['titolo'] = get_the_title( $post_id );
        //     $date[$data][$post_id]['cat'] = $cats;
        //     $date[$data][$post_id]['permalink'] = get_permalink( $post_id );
        //     $date[$data][$post_id]['featured_image'] = get_the_post_thumbnail_url( $post_id, 'large' );
        //     $date[$data][$post_id]['featured_vertical'] = is_plugin_active('advanced-custom-fields-pro/acf.php') ? get_field( 'immagine_verticale', $post_id ) : '';
        //     $date[$data][$post_id]['data'] = date('d/m/Y', strtotime($data)); // 16/09/2023
        //     $date[$data][$post_id]['orario'] = $ora;
        //     $date[$data][$post_id]['location'] = $location;
        //     $date[$data][$post_id]['ticket_link'] = $ticket;

        //     update_post_meta( $post_id, 'spettacolo_date', $date );
        // }
    }
}
add_action( 'save_post', 'save_spettacoli_meta' );

// Var dump dell'ordine nel riepilogo in backoffice
// add_action( 'woocommerce_admin_order_data_after_order_details', 'custom_woocommerce_admin_order_data_after_order_details', 10, 1 );

function custom_woocommerce_admin_order_data_after_order_details( $order ){
    // Order meta data
    $ordermeta = get_post_meta( $order->get_id() );
    echo '<pre>';
    var_dump($ordermeta);
    echo '</pre>';
}