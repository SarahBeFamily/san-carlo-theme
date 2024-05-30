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
			$vivaticket = stcticket_spettacolo_data($vivaticket_id);
			echo $vivaticket['titolo'];
		} else {
			echo 'Nessun prodotto relazionato';
		}
	}
}