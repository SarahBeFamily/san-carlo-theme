<?php

/**
 * Custom Post Types
 */

function spettacoli_cpt() {

    // Imposto le etichette per i post types personalizzati
        $labels = array(
            'name'                => 'Spettacoli',
            'singular_name'       => 'Spettacolo',
            'menu_name'           => 'Spettacoli',
            'parent_item_colon'   => 'Spettacoli correlati',
            'all_items'           => 'Tutti gli Spettacoli',
            'view_item'           => 'Vedi Spettacolo',
            'add_new_item'        => 'Aggiungi nuovo Spettacolo',
            'add_new'             => 'Aggiungi Spettacolo',
            'edit_item'           => 'Modifica Spettacolo',
            'update_item'         => 'Aggiorna Spettacolo',
            'search_items'        => 'Cerca Spettacolo',
            'not_found'           => 'Spettacolo non trovato',
            'not_found_in_trash'  => 'Spettacolo non trovato nel cestino',
        );

    // Imposto altre opzioni per i post types personalizzati

        $args = array(

            'label'               => 'Spettacolo',
            'description'         => '',
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
            'taxonomies'          => array( 'categoria-spettacoli' ),
            'rewrite'   		  => array( 'slug' => 'spettacoli', 'with_front' => false ), /* Puoi specificare uno slug per gli URL */
            'menu_icon' 		  => 'dashicons-tickets-alt',
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_rest'        => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 2,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'query_var'           => true,
            'capability_type'     => 'page',

        );


    register_post_type( 'spettacoli', $args );
}
add_action( 'init', 'spettacoli_cpt', 0 );

// Rimborsi
function rimborsi_cpt() {

    // Imposto le etichette per i post types personalizzati
        $labels = array(
            'name'                => 'Richieste Rimborso',
            'singular_name'       => 'Richiesta Rimborso',
            'menu_name'           => 'Richieste Rimborso',
            'parent_item_colon'   => 'Richieste Rimborso correlate',
            'all_items'           => 'Tutte le Richieste Rimborso',
            'view_item'           => 'Vedi Richiesta Rimborso',
            'add_new_item'        => 'Aggiungi nuova Richiesta Rimborso',
            'add_new'             => 'Aggiungi Richiesta Rimborso',
            'edit_item'           => 'Modifica Richiesta Rimborso',
            'update_item'         => 'Aggiorna Richiesta Rimborso',
            'search_items'        => 'Cerca Richiesta Rimborso',
            'not_found'           => 'Richiesta Rimborso non trovata',
            'not_found_in_trash'  => 'Richiesta Rimborso non trovata nel cestino',
        );

    // Imposto altre opzioni per i post types personalizzati

        $args = array(

            'label'               => 'Richiesta Rimborso',
            'description'         => 'email inviate dagli utenti con le richieste di rimborso',
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
            'taxonomies'          => array(),
            'rewrite'   		  => array( 'slug' => 'richieste-rimborso', 'with_front' => false ), /* Puoi specificare uno slug per gli URL */
            'menu_icon' 		  => 'dashicons-tickets-alt',
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_rest'        => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 2,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'query_var'           => true,
            'capability_type'     => 'page',

        );


    register_post_type( 'rimborsi', $args );
}
add_action( 'init', 'rimborsi_cpt', 0 );

/** CPT Taxonomies **/

// Categorie Spettacolo
function categorie_spettacolo_tax()  {
	$labels = array(
		'name'                       => 'Categoria',
		'singular_name'              => 'Categoria',
		'menu_name'                  => 'Categoria Spettacolo',
		'all_items'                  => 'Tutte le Categorie',
		'parent_item'                => '',
		'parent_item_colon'          => '',
		'new_item_name'              => 'Nuova Categoria',
		'add_new_item'               => 'Aggiungi nuova Categoria',
		'edit_item'                  => 'Modifica Categoria',
		'update_item'                => 'Aggiorna Categoria',
		'separate_items_with_commas' => '',
		'search_items'               => 'Cerca Categoria',
		'add_or_remove_items'        => 'Aggiungi o rimuovi Categoria',
		'choose_from_most_used'      => '',
	);

	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_in_rest'        		 => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'has_archive'                => true,
		'rewrite'  					 => ['slug' => 'categoria-spettacoli', 'with_front' => false],
	);
	register_taxonomy( 'categoria-spettacoli', 'spettacoli', $args );
}
add_action( 'init', 'categorie_spettacolo_tax' );

// Categorie Pagine per ordinarle
function categorie_page_tax()  {
	$labels = array(
		'name'                       => 'Categoria',
		'singular_name'              => 'Categoria',
		'menu_name'                  => 'Categoria Pagina',
		'all_items'                  => 'Tutte le Categorie',
		'parent_item'                => '',
		'parent_item_colon'          => '',
		'new_item_name'              => 'Nuova Categoria',
		'add_new_item'               => 'Aggiungi nuova Categoria',
		'edit_item'                  => 'Modifica Categoria',
		'update_item'                => 'Aggiorna Categoria',
		'separate_items_with_commas' => '',
		'search_items'               => 'Cerca Categoria',
		'add_or_remove_items'        => 'Aggiungi o rimuovi Categoria',
		'choose_from_most_used'      => '',
	);

	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_in_rest'        		 => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'has_archive'                => true,
	);
	register_taxonomy( 'categoria-pagina', 'page', $args );
}
add_action( 'init', 'categorie_page_tax' );
