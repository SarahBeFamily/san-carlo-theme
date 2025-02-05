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
        $terms = get_the_terms( $post_id, 'categoria-spettacoli' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            $cats = join( ", ", array_map( function( $t ) { return $t->name; }, $terms ) );
        }
        
        if (isset($spettacolo_data['date']) && is_array($spettacolo_data['date'])) :
            foreach ($spettacolo_data['date'] as $dettaglio) {
                $data_ora_array = explode(' ', $dettaglio['date']);
				$data = date('Y/m/d', strtotime($data_ora_array[0])); // 2023/09/16
                $orario = $data_ora_array[1];
                $ticket = isset($dettaglio['url']) ? $dettaglio['url'] : '';
                $location = isset($spettacolo_data['location']) ? $spettacolo_data['location'] : '';

                $date[$data][$post_id]['ID'] = $post_id;
                $date[$data][$post_id]['titolo'] = get_the_title( $post_id );
                $date[$data][$post_id]['cat'] = $cats;
                $date[$data][$post_id]['permalink'] = get_permalink( $post_id );
                $date[$data][$post_id]['featured_image'] = get_the_post_thumbnail_url( $post_id, 'large' );
                $date[$data][$post_id]['featured_vertical'] = is_plugin_active('advanced-custom-fields-pro/acf.php') ? get_field( 'immagine_verticale', $post_id ) : '';
                $date[$data][$post_id]['data'] = date('d/m/Y', strtotime($data_ora_array[0])); // 16/09/2023
                $date[$data][$post_id]['orario'] = $orario;
                $date[$data][$post_id]['location'] = $location;
                $date[$data][$post_id]['ticket_link'] = $ticket;
            }
        endif;

        $spettacolo_date_db = get_post_meta( $post_id, 'spettacolo_date', true );
        
        if ( ! empty( $date ) && $spettacolo_data !== '' && empty($spettacolo_date_db) ) {
            update_post_meta( $post_id, 'spettacolo_date', $date );
        } 

        // Saving acf field date_passate to post meta
        $date_passate = get_field('date_passate', $post_id);
        $date_past_arr = array();
        if ( is_array($date_passate) && ! empty( $date_passate ) ) {
            foreach ($date_passate as $key => $value) {
                $date_past_arr[$post_id]['date'][] = $value['data_passata'];
            }
            update_post_meta( $post_id, 'date_passate_cal', $date_past_arr );
        } else if( empty( $date_passate ) ) {
            update_post_meta( $post_id, 'date_passate_cal', array() );
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
    $orderdata = $order->get_data();

    echo '<pre>';
    var_dump(json_encode($orderdata));
    echo '</pre>';
}

// Cerco un post per ID con shortcode
function get_post_by_id_func( $atts ) {
    $a = shortcode_atts( array(
        'id' => '',
    ), $atts );

    $post = get_post($a['id']);
    
    if ($post) {
        echo '<pre>';
        var_dump($post);
        echo '</pre>';
    } else {
        return 'Post non trovato';
    }
}
add_shortcode( 'get_post_by_id', 'get_post_by_id_func' );

// Shortcode per check form scuola incanto
function check_scuola_callback() {
    global $wpdb;
    $cfdb        = apply_filters( 'cfdb7_database', $wpdb );
    $table_name  = $cfdb->prefix.'db7_forms';

    // $string = 'a:46:{s:12:"cfdb7_status";s:4:"read";s:6:"scuola";s:6:"scuola";s:6:"plesso";s:6:"plesso";s:9:"indirizzo";s:9:"via Prova";s:3:"cap";s:4:"1234";s:5:"citta";s:6:"Padova";s:9:"provincia";s:8:"Cagliari";s:8:"telefono";s:14:"00393453455011";s:11:"emailscuola";s:21:"g7a99d0km@mozmail.com";s:19:"dirigentescolastico";s:11:"Mario Rossi";s:13:"codiceunivoco";s:5:"24567";s:13:"codicefiscale";s:5:"jsjjs";s:3:"cig";s:0:"";s:7:"fattura";a:1:{i:0;s:2:"SI";}s:11:"nomedocente";s:5:"Sarah";s:14:"cognomedocente";s:5:"Pinna";s:12:"emaildocente";s:17:"sarah@befamily.it";s:9:"cellulare";s:10:"3453455092";s:16:"totalunnipaganti";s:1:"3";s:32:"totdocentiaccompagnatoristudenti";s:1:"5";s:17:"totdocentipaganti";s:1:"2";s:17:"totalunnigratuiti";s:1:"5";s:18:"totdocentisostegno";s:1:"3";s:21:"totalepartecipantitot";s:2:"18";s:6:"classe";s:1:"4";s:7:"sezione";s:1:"B";s:11:"ordinegrado";s:8:"primaria";s:6:"alunni";s:1:"5";s:14:"alunnigratuiti";s:1:"3";s:21:"docentiaccompagnatori";s:1:"2";s:15:"docentisostegno";s:1:"1";s:13:"nomereferente";s:0:"";s:16:"cognomereferente";s:0:"";s:14:"emailreferente";s:0:"";s:13:"cellreferente";s:0:"";s:10:"spettacolo";s:51:"Scuola InCanto - Il barbiere di Siviglia - 24 marzo";s:23:"prima_data_spettacolo_1";s:20:"24&#047;03&#047;2025";s:19:"orario_spettacolo_1";s:5:"10:00";s:12:"spettacolo-2";s:51:"Scuola InCanto - Il barbiere di Siviglia - 25 marzo";s:25:"prima_data_spettacolo_2_2";s:20:"25&#047;03&#047;2025";s:27:"terzo_orario_spettacolo_2_2";s:5:"11:30";s:10:"iscrizione";a:1:{i:0;s:1:"1";}s:7:"privacy";a:1:{i:0;s:1:"1";}s:10:"SCUOLEINCA";s:10:"SCUOLEINCA";s:10:"consensonl";s:0:"";s:4:"NLOK";a:0:{}}';
    // $unserialized = unserialize($string);
    
    $fid = '5264'; //(int)$_REQUEST['fid'];
    $heading_row = $cfdb->get_results("SELECT form_id, form_value, form_date FROM $table_name
        WHERE form_post_id = '$fid' ORDER BY form_id DESC LIMIT 1",OBJECT);

    $heading_row    = reset( $heading_row );
    $heading_row    = isset($heading_row->form_value) ? unserialize( $heading_row->form_value ) : array();
    $heading_key    = array_keys( $heading_row );
    $rm_underscore  = apply_filters('cfdb7_remove_underscore_data', true); 


    $total_rows  = $cfdb->get_var("SELECT COUNT(*) FROM $table_name WHERE form_post_id = '$fid' "); 
    $per_query    = 1000;
    $total_query  = ( $total_rows / $per_query );

    // $this->download_send_headers( "cfdb7-" . date("Y-m-d") . ".csv" );
    // $df = fopen("php://output", 'w');
    // ob_start();

    for( $p = 0; $p <= $total_query; $p++ ){

        $offset  = $p * $per_query;
        $results = $cfdb->get_results("SELECT form_id, form_value, form_date FROM $table_name
        WHERE form_post_id = '$fid' ORDER BY form_id DESC  LIMIT $offset, $per_query",OBJECT);
        
        $data  = array();
        $i     = 0;
        foreach ($results as $result) :
            
            $i++;
            $data['form_id'][$i]    = $result->form_id;
            $data['form_date'][$i]  = $result->form_date;
            $resultTmp              = unserialize( $result->form_value );
            $upload_dir             = wp_upload_dir();
            $cfdb7_dir_url          = $upload_dir['baseurl'].'/cfdb7_uploads';

            foreach ($resultTmp as $key => $value):
                $matches = array();

                if ( ! in_array( $key, $heading_key ) ) continue;
                if( $rm_underscore ) preg_match('/^_.*$/m', $key, $matches);
                if( ! empty($matches[0]) ) continue;

                $value = str_replace( 
                            array('&quot;','&#039;','&#047;','&#092;'),
                            array('"',"'",'/','\\'), $value 
                        );

                if (strpos($key, 'cfdb7_file') !== false ){
                    $data[$key][$i] = empty( $value ) ? '' : $cfdb7_dir_url.'/'.$value;
                    continue;
                }
                if ( is_array($value) ){

                    $data[$key][$i] = implode(', ', $value);
                    continue;
                }
                $data[$key][$i] = $value;
                // $data[$key][$i] = escape_data( $data[$key][$i] );

            endforeach;

        endforeach;
    
    }

    echo '<pre>';
    print_r($data);
    echo '</pre>';
}
// add_shortcode( 'check_scuola', 'check_scuola_callback' );

/**
* Escape a string to be used in a CSV context
* @param string $data CSV field to escape.
* @return string    
*/
function escape_data( $data ) {
    $active_content_triggers = array( '=', '+', '-', '@', ';' );

    if ( in_array( mb_substr( $data, 0, 1 ), $active_content_triggers, true ) ) {
        $data = '"'. $data.'"';
    }

    return $data;
}

// Aggiungo l'orario di creazione dell'ordine nella colonna pubblicazione in admin
add_filter('manage_edit-shop_order_columns', 'add_order_date_column');
function add_order_date_column($columns)
{
    $columns['order_date'] = 'Data Ordine';
    return $columns;
}

// Cambio il formato della data
add_action('manage_shop_order_posts_custom_column', 'add_order_date_column_content', 10, 2);
function add_order_date_column_content($column, $post_id)
{
    if ($column == 'order_date') {
        $order = wc_get_order($post_id);
        echo 'ore '.$order->get_date_created()->format('H:i'). ' ';
    }
}

/**
 * Add relationship between spettacoli and vivaticket in quick edit panel
 * 
 * @param string $column_name
 * @param string $post_type
 * @param int $post_id
 * @return void
 */
function add_vivaticket_quick_edit($column_name, $post_type, $post_id)
{
    if ($column_name !== 'vivaticket') {
        return;
    }

    $vivaticket_id = get_post_meta($post_id, 'prodotto_relazionato', true);
    $vivaticket = is_plugin_active('stc-tickets/stc-tickets.php') && function_exists('stcticket_spettacolo_data') ? stcticket_spettacolo_data($vivaticket_id) : null;
    $vivaticket_title = $vivaticket !== null ? $vivaticket['titolo'] : 'Nessun prodotto relazionato';

    ?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <label class="inline-edit-group">
                <span class="title">Prodotto relazionato</span>
                <!-- add select -->
                <select name="prodotto_relazionato">
                    <option value="">Nessun prodotto relazionato</option>
                    <?php
                    $args = array(
                        'post_type' => 'spettacolo',
                        'posts_per_page' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC'
                    );
                    $vivatickets = get_posts($args);
                    foreach ($vivatickets as $vivaticket) {
                        $selected = $vivaticket_id == $vivaticket->ID ? 'selected' : '';
                        echo '<option value="' . $vivaticket->ID . '" ' . $selected . '>' . $vivaticket->post_title . '</option>';
                    }
                    ?>
                </select>
            </label>
            <label class="inline-edit-group">
                <span class="title">Titolo</span>
                <span><?php echo $vivaticket_title; ?></span>
            </label>
        </div>
    </fieldset>
    <?php
}
add_action('quick_edit_custom_box', 'add_vivaticket_quick_edit', 10, 3);

/**
 * Save relationship between spettacoli and vivaticket in quick edit and bulk edit panel
 * 
 * @param int $post_id
 * @return void
 */
function save_vivaticket_quick_edit($post_id)
{
    if (!isset($_REQUEST['prodotto_relazionato'])) {
        return;
    }

    $vivaticket_id = sanitize_text_field($_REQUEST['prodotto_relazionato']);
    update_post_meta($post_id, 'prodotto_relazionato', $vivaticket_id);
}
add_action('save_post', 'save_vivaticket_quick_edit');

