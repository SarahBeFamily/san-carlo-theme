<?php

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/

if (! file_exists($composer = __DIR__.'/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'sage'));
}

require $composer;

/*
|--------------------------------------------------------------------------
| Register The Bootloader
|--------------------------------------------------------------------------
|
| The first thing we will do is schedule a new Acorn application container
| to boot when WordPress is finished loading the theme. The application
| serves as the "glue" for all the components of Laravel and is
| the IoC container for the system binding all of the various parts.
|
*/

if (! function_exists('\Roots\bootloader')) {
    wp_die(
        __('You need to install Acorn to use this theme.', 'sage'),
        '',
        [
            'link_url' => 'https://roots.io/acorn/docs/installation/',
            'link_text' => __('Acorn Docs: Installation', 'sage'),
        ]
    );
}

\Roots\bootloader()->boot();

/*
|--------------------------------------------------------------------------
| Register Sage Theme Files
|--------------------------------------------------------------------------
|
| Out of the box, Sage ships with categorically named theme files
| containing common functionality and setup to be bootstrapped with your
| theme. Simply add (or remove) files from the array below to change what
| is registered alongside Sage.
|
*/

collect(['setup', 'filters'])
    ->each(function ($file) {
        if (! locate_template($file = "app/{$file}.php", true, true)) {
            wp_die(
                /* translators: %s is replaced with the relative file path */
                sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file)
            );
        }
    });


/**
 * Body classes
 *
 * @param [type] $classes
 * @return void
 */
function theme_body_classes( $classes ) {

    global $post;

	if ( is_page() && get_field('header_trasparente', $post->ID) == true ) {
		$classes[] = 'transparent-header';
	}

    if(is_user_logged_in(  )) {
        $classes[] = 'logged-in';
    }

	return $classes;
}
add_filter( 'body_class', 'theme_body_classes' );

/**
 * Theme Files
 *
 * Adding these files after the main setup is done
 * @san-carlo-theme
 */

 include __DIR__.'/app/Theme/cpt.php';
 include __DIR__.'/app/Theme/Classes/rest_api.php';
 include __DIR__.'/app/Theme/Calendar/calendar_class.php';
 include __DIR__.'/app/Theme/walker.php';
 include __DIR__.'/app/Theme/widget.php';
 include __DIR__.'/app/Theme/functions/spettacoli_functions.php';

// Check if constant is defined
if ( ! defined( 'ICL_LANGUAGE_CODE' ) ) {
    define( 'ICL_LANGUAGE_CODE', 'it' );
}

// Before VC Init
add_action( 'vc_before_init', 'vc_before_init_actions' );
 
function vc_before_init_actions() {
    vc_set_as_theme();

    require_once __DIR__ . '/app/Theme/js_composer/vc_extend/vc_custom_params.php';
    require_once __DIR__ . '/app/Theme/js_composer/vc_extend/dynamic-bfp-style.php';

    foreach (scandir(__DIR__.'/app/Theme/js_composer/vc_elements/') as $filename) {
        $path = __DIR__ . '/app/Theme/js_composer/vc_elements/' . $filename;
        if (is_file($path)) {
            require_once $path;
        }
    }

    // Remove VC Elements
    if( function_exists('vc_remove_element') ){

        vc_remove_element( 'vc_wp_meta' );
        vc_remove_element( 'vc_wp_rss' );
        vc_remove_element( 'vc_btn' );
        vc_remove_element( 'vc_facebook' );
        vc_remove_element( 'vc_tweetmeme' );
        vc_remove_element( 'vc_pinterest' );

    }

    remove_action( 'admin_bar_menu', array( vc_frontend_editor(), 'adminBarEditLink' ), 1000 );

    /**
     * Check if WooCommerce is activated
     */
    if ( ! function_exists( 'is_woocommerce_activated' ) ) {
        function is_woocommerce_activated() {
            if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
        }
    }
     
    //*********************//
    // VC COLUMN REMAPPING //
    //*********************//
 
    // Add Params
    $vc_column_new_params = array(
         
        array(
            "type"        => "dropdown",
            "class"       => "",
            "heading"     => 'Allineamento degli elementi',
            "param_name"  => "elem_position",
            'value'       => array(
                'Default' => 'default',
                'Centrato' => 'center',
                'Destra' => 'right',
            ),
            'std' => 'default', // Your default value
        ),
        array(
          "type"        => "vc_link",
          "class"       => "",
          "heading"     => "Column Link",
          "param_name"  => "column_link"
        ),
        array(
          "type"        => "checkbox",
          "class"       => "",
          'heading'     => 'Sticky',
          'param_name'  => 'sticky_col',
          'admin_label' => false,
          'value'       => true,
          'description' => 'Flagga per rendere sticky la colonna',
        ),
        array(
            "type"        => "checkbox",
            "class"       => "",
            'heading'     => 'Full Height',
            'param_name'  => 'full_height',
            'admin_label' => false,
            'value'       => true,
            'description' => 'Flagga per dare alla colonna il 100% dell\'altezza disponibile',
        ),
     
    );
     
    vc_add_params( 'vc_column', $vc_column_new_params );
    vc_add_params( 'vc_column_inner', $vc_column_new_params );


    // Add Params
    $vc_row_new_params = array(
         
        // Example
        array(
            "type"        => "textfield",
            "class"       => "",
            "heading"     => "Padding Desktop Wide (top right bottom left)",
            "param_name"  => "padding_desktop_wide",
            'description' => 'inserisci il padding della riga su desktop (> 1920px)',
            'value'       => '',
            'group'       => 'BF Responsive',
        ),
        array(
          "type"        => "textfield",
          "class"       => "",
          "heading"     => "Padding Desktop (top right bottom left)",
          "param_name"  => "padding_desktop",
          'description' => 'inserisci il padding della riga su desktop (1440px - 1920px)',
          'value'       => '',
          'group'       => 'BF Responsive',
        ),
        array(
            "type"        => "textfield",
            "class"       => "",
            "heading"     => "Padding Small Desktop (top right bottom left)",
            "param_name"  => "padding_small_desktop",
            'description' => 'inserisci il padding della riga su desktop (1280px - 1440px)',
            'value'       => '',
            'group'       => 'BF Responsive',
        ),
        array(
            "type"        => "textfield",
            "class"       => "",
            "heading"     => "Padding Mini (top right bottom left)",
            "param_name"  => "padding_mini",
            'description' => 'inserisci il padding della riga su device piccoli (1024px - 1280px)',
            'value'       => '',
            'group'       => 'BF Responsive',
        ),
        array(
            "type"        => "textfield",
            "class"       => "",
            "heading"     => "Padding Tablet (top right bottom left)",
            "param_name"  => "padding_tablet",
            'description' => 'inserisci il padding della riga su tablet (768px - 1024px)',
            'value'       => '',
            'group'       => 'BF Responsive',
        ),
        array(
            "type"        => "textfield",
            "class"       => "",
            "heading"     => "Padding Small Tablet (top right bottom left)",
            "param_name"  => "padding_small_tablet",
            'description' =>  'inserisci il padding della riga su tablet piccoli (640px - 768px)',
            'value'       => '',
            'group'       => 'BF Responsive',
        ),
        array(
            "type"        => "textfield",
            "class"       => "",
            "heading"     => "Padding Mobile (top right bottom left)",
            "param_name"  => "padding_mobile",
            'description' => 'inserisci il padding della riga su mobile (< 640px)',
            'value'       => '',
            'group'       => 'BF Responsive',
        ),
        array(
            'type' => 'dropdown',
            'holder' => 'h3',
            'class' => 'class-name',
            'heading' => 'Animazione',
            'param_name' => 'data_bf_animate',
            'value' => 'Default value',
            'description' => 'Animazione della riga allo scroll',
            'save_always' => true,
			'admin_label' => false,
			'value' => array(
                'Nessuna' => '',
				'Sfumato' => 'fadeIn',
				'Sfumato verso il basso' => 'fadeInDown',
				'Sfumato verso l\'alto' => 'fadeInUp',
				'Sfumato verso destra' => 'fadeInRight',
                'Sfumato verso sinistra' => 'fadeInLeft',

			),
			'std' => '', // Your default value
            'dependency' => '',
            'weight' => 0,
            'group' => 'BF Animazioni',
        ),
        array(
            "type" => "colorpicker",
            "class" => "",
            'save_always' => true,
            "heading" => 'Colore di Sovrapposizione',
            'admin_label' => false,
            "param_name" => "bf_overlay_color",
            // 'dependency' => array(
            //     'element' => 'parallax',
            //     'value' => 'content-moving',
            // ),
            'description' => 'Scegli un colore da sovrapporre all\'immagine di sfondo',
            "value" => '',
        ),
    );
     
    vc_add_params( 'vc_row', $vc_row_new_params );

}

function dynamic_copyright_shortcode() {
    global $wpdb;
    $copyright_dates = $wpdb->get_results("SELECT
        YEAR(min(post_date_gmt)) AS firstdate,
        YEAR(max(post_date_gmt)) AS lastdate
        FROM $wpdb->posts WHERE post_status = 'publish'");
    $output = '';
	if($copyright_dates) {
		$output = "&copy;  " . $copyright_dates[0]->lastdate. " / Teatro di San Carlo";
	}
return $output;
} // <?php echo dynamic_copyright(); //
add_shortcode('dynamic_copyright', 'dynamic_copyright_shortcode');

/*
* Redirect wp-login.php to Accedi Template page
* 
* @return void
*/

add_filter('login_redirect', 'my_login_redirect', 10, 3);
function my_login_redirect($redirect_to, $requested_redirect_to, $user) {
   $query_area_stampa = new \WP_Query([
       'post_type' => 'page',
       'post_title' => 'Area stampa',
       'post_status' => 'publish',
   ]);
   $frontpage_id = get_option( 'page_on_front' );
   $frontpage = get_post($frontpage_id);
   $press_page = is_array($query_area_stampa->posts) ? $query_area_stampa->posts[0] : $frontpage;
   $query_page = new \WP_Query([
       'post_type' => 'page',
       'post_title' => 'Accedi',
   ]);
   $redirect_page = $query_page->posts[0];

   if (is_wp_error($user)) {
       //Login failed, find out why...
       $error_types = array_keys($user->errors);
       //Error type seems to be empty if none of the fields are filled out
       $error_type = 'both_empty';
       //Otherwise just get the first error (as far as I know there
       //will only ever be one)
       if (is_array($error_types) && !empty($error_types)) {
           $error_type = $error_types[0];
       }
       $loginurl = ICL_LANGUAGE_CODE == 'it' ? '/accedi' : '/log-in';

       wp_redirect( home_url() . $loginurl."?login=failed&reason=" . $error_type ); 
       exit;

   } else {
       //Login OK - redirect to another page?
       if ($_SERVER['HTTP_REFERER'] == get_permalink($press_page->ID))
           return get_permalink($press_page->ID);
       else if (in_array('administrator', $user->roles))
           return admin_url();
       else if (in_array('press', $user->roles))
           return get_permalink($press_page->ID);
       else
           return home_url();
   }
}


/**
 * Hide admin bar for Press role
 *
 * @param [type] $roles
 * @return void
 */
function tf_check_user_role( $roles ) {
    /*@ Check user logged-in */
    if ( is_user_logged_in() ) :
        /*@ Get current logged-in user data */
        $user = wp_get_current_user();
        /*@ Fetch only roles */
        $currentUserRoles = $user->roles;
        /*@ Intersect both array to check any matching value */
        $isMatching = array_intersect( $currentUserRoles, $roles);
        $response = false;
        /*@ If any role matched then return true */
        if ( !empty($isMatching) ) :
            $response = true;        
        endif;
        return $response;
    endif;
}
$roles = [ 'press', 'subscriber' ];

if ( tf_check_user_role($roles) ) :
    add_filter('show_admin_bar', '__return_false');
endif;

/**
* is_woocommerce_page
* Returns true if on a page which uses WooCommerce templates (cart and checkout are standard pages with shortcodes and which are also included)
*
* @access public
* @return bool
*/
function is_woocommerce_page () {
    if( function_exists ( "is_woocommerce" ) && is_woocommerce()){
        return true;
    }
    
    $woocommerce_keys = array ( "woocommerce_shop_page_id" ,
        "woocommerce_terms_page_id" ,
        "woocommerce_cart_page_id" ,
        "woocommerce_checkout_page_id" ,
        "woocommerce_pay_page_id" ,
        "woocommerce_thanks_page_id" ,
        "woocommerce_myaccount_page_id" ,
        "woocommerce_edit_address_page_id" ,
        "woocommerce_view_order_page_id" ,
        "woocommerce_change_password_page_id" ,
        "woocommerce_logout_page_id" ,
        "woocommerce_lost_password_page_id" ) ;

    foreach ( $woocommerce_keys as $wc_page_id ) {
        if ( get_the_ID () == get_option ( $wc_page_id , 0 ) ) {
            return true ;
        }
    }
    return false;
}

 /**
 * Clean strings for slugs
 */
function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

/**
 * Insert featured media url in API rest
 */

add_action('rest_api_init', 'register_rest_images' );
function register_rest_images(){
    register_rest_field( array('spettacoli'),
        'evimg_url',
        array(
            'get_callback'    => 'get_rest_featured_image',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}
function get_rest_featured_image( $object, $field_name, $request ) {
    if( $object['featured_media'] ){
        $img = wp_get_attachment_image_src( $object['featured_media'], 'app-thumb' );
        return $img[0];
    }
    return false;
}


/**
 * Create custom ACF field class for Crop Img field
 * Insert custom field for image crop in API Rest
 */
function create_ACF_meta_in_REST() {
    $postypes_to_exclude = ['acf-field-group','acf-field'];
    $extra_postypes_to_include = ["page", 'spettacoli'];
    $post_types = array_diff(get_post_types(["_builtin" => false], 'names'),$postypes_to_exclude);

    array_push($post_types, $extra_postypes_to_include);

    foreach ($post_types as $post_type) {
        register_rest_field( $post_type, 'acf', [
            'get_callback'    => 'expose_ACF_fields',
            'schema'          => null,
       ]
     );
    }

}

function expose_ACF_fields( $object ) {
    $ID = $object['id'];
    return get_fields($ID);
}

add_action( 'rest_api_init', 'create_ACF_meta_in_REST' );

/**
 * Change the main query for news page
 *
 * @param [type] $query
 * @return void
 */
function news_query( $query ) {
    if ( $query->is_home() && $query->is_main_query() ) {
        $exclude = get_posts( array( 
            'post_type' => 'post',
            'fields' => 'ids',
            'meta_query' => [
                [
                    'key' => 'articolo_in_evidenza',
                    'value' => 1,
                    'compare' => '='
                ]
            ]
         ) );
        $query->set('ignore_sticky_posts', 1);
        $query->set('posts_per_page', 12);
        $query->set('post__not_in',$exclude);
    }
    return $query;
}
add_action( 'pre_get_posts', 'news_query' );

/*
 * Add columns to news post list
 */
function add_posts_columns ( $columns ) {
	return array_merge ( $columns, array ( 
	  'sticky' => __ ( 'In Evidenza' ),
	) );
  }
  add_filter ( 'manage_post_posts_columns', 'add_posts_columns' );

  function post_sticky_column ( $column, $post_id ) {
	switch ( $column ) {
	  case 'sticky':
		$sticky = get_field ('articolo_in_evidenza', $post_id);

        if ( $sticky == 1 ) {
          echo 'Si';
        } else {
          echo 'No';
        }
		
		break;
	}
  }
  add_action ( 'manage_post_posts_custom_column', 'post_sticky_column', 10, 2 );


function sticky_custom_edit_box_pt( $column, $post_type, $taxonomy ){
    static $printNonce = TRUE;
    if ( $printNonce ) {
        $printNonce = FALSE;
        wp_nonce_field( plugin_basename( __FILE__ ), 'news_edit_nonce' );
    }

    global $post;
    $post_id = $post->ID;

    switch ( $column ) {
        case 'sticky':
            $sticky = get_field('articolo_in_evidenza', $post_id);
            $checked = $sticky === true ? ' checked="checked" ' : null;

            $html = '<fieldset class="inline-edit-col-right ">';
                $html .= '<div class="inline-edit-group wp-clearfix">';
                    $html .= '<label class="alignleft" for="articolo_in_evidenza">In Evidenza</label>';
                    $html .= '<input type="checkbox" name="articolo_in_evidenza" id="articolo_in_evidenza"'.$checked.' value="1" />';
                $html .= '</div>';
            $html .= '</fieldset>';
            
            echo $html;
          break;
    }
}
add_action( 'quick_edit_custom_box', 'sticky_custom_edit_box_pt', 10, 3 );

// Salva le modifiche al campo custom
function sticky_update_custom_quickedit_box($post_id) {

    $post_type = get_post_type($post_id);
    if ( 'post' == $post_type ) {
        if ( !current_user_can( 'edit_post', $post_id ) )
            return $post_id;
    }

    if ( isset( $_REQUEST['articolo_in_evidenza'] ) ) {
        update_post_meta($post_id, 'articolo_in_evidenza', TRUE);
    } else {
        update_post_meta($post_id, 'articolo_in_evidenza', FALSE);
    }
    return;
}
add_action( 'save_post', 'sticky_update_custom_quickedit_box' );

/*
 * Add Sortable columns for news
 */

 function kd_post_sortable( $columns ) {
	$columns['sticky'] = 'sticky';
	return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'kd_post_sortable' );

// Custom directory per upload users
function secure_upload_directory( $param ) {
    $folder = get_home_path().'wp-content/uploads';  
  
    $param['path'] = $folder;
    $param['url'] = $folder;
    $param['subdir'] = $folder;
    $param['basedir'] = $folder;
    $param['baseurl'] = $folder;
    return $param;
}

function secure_upload_directory_rimborsi( $param ) {
    $folder = get_home_path().'wp-content/uploads/rimborsi';
  
    $param['path'] = $folder;
    $param['url'] = $folder;
    $param['subdir'] = $folder;
    $param['basedir'] = $folder;
    $param['baseurl'] = $folder;
    return $param;
}

// Utility
function encrypt_url($string) {
    $key = "MAL_979805"; //key to encrypt and decrypts.
    $result = '';

    for($i=0; $i<strlen($string); $i++) {
       $char = substr($string, $i, 1);
       $keychar = substr($key, ($i % strlen($key))-1, 1);
       $char = chr(ord($char)+ord($keychar));
       $result.=$char;
     }

     return urlencode(base64_encode($result));
  }

  function decrypt_url($string) {
      $key = "MAL_979805"; //key to encrypt and decrypts.
      $result = '';
      $string = base64_decode(urldecode($string));
     for($i=0; $i<strlen($string); $i++) {
       $char = substr($string, $i, 1);
       $keychar = substr($key, ($i % strlen($key))-1, 1);
       $char = chr(ord($char)-ord($keychar));
       $result.=$char;
     }
     return $result;
}

  function upload_file() {

    if ( isset($_POST['upload_nonce']) && ! wp_verify_nonce( $_POST['upload_nonce'], 'upload_file' ) ) {
        exit();
    }

    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }

    // Nome File (lo modifico per evitare sovrascrizioni)
    $id = $_POST['inputID'];
    $tipo = isset($_POST['tipo']) ? 'rimborsi' : '';
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $filename_temp = str_replace('.'.$ext, '', $_FILES['file']['name']);
    $filename = str_replace(' ', '-', $filename_temp.substr(md5(mt_rand()), 0, 7).'.'.$ext);
    $_FILES['file']['name'] = $filename;

    /* Getting File size */
    $filesize = $_FILES['file']['size'];

    /* Location */
    $location = $tipo !== '' ? ABSPATH .'wp-content/uploads/rimborsi/'.$filename : ABSPATH .'wp-content/uploads/'.$filename;
    $url_loc = $tipo !== '' ? home_url() .'/wp-content/uploads/rimborsi/'.$filename : home_url() .'/wp-content/uploads/'.$filename;
    // $location = ABSPATH .'wp-content/uploads/'.$filename;
    // $url_loc = home_url() .'/wp-content/uploads/'.$filename;

    $return_arr = array();

    /* Upload file */
    $upload_overrides = array('test_form' => false);
    $secure_dir = $tipo !== '' ? 'secure_upload_directory_rimborsi' : 'secure_upload_directory';

    add_filter('upload_dir', $secure_dir, 20);
    $movefile = wp_handle_upload( $_FILES['file'], $upload_overrides );
    remove_filter( 'upload_dir', $secure_dir );
    
    $return_arr = array(
        "id" => $id,
        "name" => $filename,
        "size" => $filesize,
        "src"=> $url_loc,
        "path"=> encrypt_url($location),
        "file"=> $movefile,
    );


    if ( $movefile && ! isset( $movefile['error'] ) && !empty($return_arr) ) {
        wp_send_json_success($return_arr, 200);
    } else {
        /*
        * Error generated by _wp_handle_upload()
        * @see _wp_handle_upload() in wp-admin/includes/file.php
        */
        // echo $movefile['error'];
        wp_send_json_error( $movefile['error'], 1);

    }
    die();
}
add_action ( 'wp_ajax_nopriv_upload_file', 'upload_file', 10 );
add_action ( 'wp_ajax_upload_file', 'upload_file', 10 );

function invia_mail_rimborso() {
    $data = $_POST['formData'];
    $post = array();

    if(isset($data)) :
        foreach ($data as $object) {
            $obj_name = $object['name'];
            $obj_val = $object['value'];

            if (stripos($obj_name, 'file-') !== false) {
                if ($obj_val !== '') {
                    // $tokens = explode('/rimborsi/', $obj_val);
                    $tokens = explode('/uploads/', $obj_val);
                    $str = trim(end($tokens));
                    $index = intval(str_replace('file-', '', $obj_name));
                    // $post['uploads'][]['path'] = WP_CONTENT_DIR . '/uploads/rimborsi/'.$str;
                    $post['uploads'][]['path'] = WP_CONTENT_DIR . '/uploads/'.$str;
                    $post['uploads'][]['url'] = $obj_val;
                }
            } else if (stripos($obj_name, 'fileRemove') !== false) {
                $post['file_remove'][$index] = $obj_val;
            } else if (stripos($obj_name, 'qty-') !== false) {
                $index = intval(str_replace('qty-', '', $obj_name));
                $post['tickets'][$index]['qty'] = $obj_val;
            } else if (stripos( $obj_name, 'sector-' ) !== false) {
                $index = intval(str_replace('sector-', '', $obj_name));
                $post['tickets'][$index]['sector'] = $obj_val;
            } else {
                $post[$obj_name] = $obj_val;
            }
        }
    endif;

    if ( isset($post['nonce']) && ! wp_verify_nonce( $post['nonce'], 'invia_form_rimborso' ) ) {
        exit();
    }

    if (!empty($post)) {
        // Recipient
        $destinatari = get_field('form_rimborsi', 'option') ? explode(',', get_field('form_rimborsi', 'option')) : array('teamweb@kidea.net');
        $to = $destinatari;
        
        // Sender 
        $from = $post['email']; 
        $fromName = $post['first-name'].' '.$post['last-name']; 
        
        // Headers
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'From: '.$fromName.' <'.$from.'>';
        // $headers[] = 'Cc: John Q Codex <jqc@wordpress.org>';
        // $headers[] = 'Cc: iluvwp@wordpress.org';


        // Email subject 
        $subject = 'Richiesta di rimborso';  
        
        // Attachment file
        $attachments = array();

        $content = array();
        $content[] = 'Nome: '.$post['first-name'];
        $content[] = 'Cognome: '.$post['last-name'];
        $content[] = 'Email: '.$post['email'];
        $content[] = 'Indirizzo: '.$post['address'];
        $content[] = 'Tel.: '.$post['phone'];
        $content[] = 'Spettacolo: '.$post['show-name'];
        $content[] = 'Data: '.$post['show-date'];

        foreach ($post['tickets'] as $key => $value) {
            $content[] = 'Quantità biglietti: '.$value['qty'].' x '.$value['sector'];
        }

        $content[] = 'Importo: '.$post['importo'].'€';
        $content[] = 'Banca: '.$post['bank-name'];
        $content[] = 'IBAN: '.$post['iban'];

        if (isset($post['swift']))
            $content[] = 'SWIFT/BIC: '.$post['swift'];

        if (isset($post['bsb']))
            $content[] = 'BSB: '.$post['bsb'];

        if (isset($post['uploads']) && !empty($post['uploads'])) {
            foreach ($post['uploads'] as $file) {
                if ($file['path'] != '') {
                    $attachments[] = $file['path'];
                }
            }
        }

        $message = implode('<br>', $content);
        
        // Send email 
        $mail = wp_mail( $to, $subject, $message, $headers, $attachments );

        $response_ok = array(
            'message_ok' => __('We received your request, thank you', 'san-carlo-theme'),
            'uploads' => isset($post['file_remove']) ? $post['file_remove'] : ''
        );

        $response_ko = array(
            'message_ko' => __('Error sending email', 'san-carlo-theme'),
            'uploads' => isset($post['file_remove']) ? $post['file_remove'] : ''
        );

        // wp_send_json_success( $data, 200);

        if ($mail) {
            wp_send_json_success($response_ok, 200);
        } else {
            wp_send_json_error($response_ko, 002 );
        }

    } else {
        wp_send_json_error( __('Error with data', 'san-carlo-theme'), 001 );
    }
}
add_action ( 'wp_ajax_nopriv_invia_mail_rimborso', 'invia_mail_rimborso', 10 );
add_action ( 'wp_ajax_invia_mail_rimborso', 'invia_mail_rimborso', 10 );

function elimina_allegati_rimborsi() {
    $delete = $_POST['uploads'];

    foreach ($delete as $file) {
        if ($file !== '') {
            $filepath = decrypt_url(wp_unslash($file));
            return is_file($filepath) ? unlink($filepath) : 'error';
        }
    }
}
add_action ( 'wp_ajax_nopriv_elimina_allegati_rimborsi', 'elimina_allegati_rimborsi', 10 );
add_action ( 'wp_ajax_elimina_allegati_rimborsi', 'elimina_allegati_rimborsi', 10 );

function invia_mail_prenotazione() {
    $data = $_POST['formData'];
    $post = array();

    if(isset($data)) :
        foreach ($data as $object) {
            $obj_name = $object['name'];
            $obj_val = $object['value'];

            if (stripos($obj_name, 'file-') !== false) {
                if ($obj_val !== '') {
                    // $tokens = explode('/rimborsi/', $obj_val);
                    $tokens = explode('/uploads/', $obj_val);
                    $str = trim(end($tokens));
                    $index = intval(str_replace('file-', '', $obj_name));
                    // $post['uploads'][]['path'] = WP_CONTENT_DIR . '/uploads/rimborsi/'.$str;
                    $post['uploads'][]['path'] = WP_CONTENT_DIR . '/uploads/'.$str;
                    $post['uploads'][]['url'] = $obj_val;
                }
            } else if (stripos($obj_name, 'fileRemove') !== false) {
                $post['file_remove'][$index] = $obj_val;
            } else if (stripos($obj_name, 'interesse') !== false) {
                $post['interessi'][] = $obj_val;
            } else {
                $post[$obj_name] = $obj_val;
            }
        }
    endif;

    if ( isset($post['booking_nonce']) && ! wp_verify_nonce( $post['booking_nonce'], 'invia_form_prenotazione' ) ) {
        exit();
    }

    if (!empty($post)) {
        // Recipient  
        $destinatari = get_field('form_rimborsi', 'option') ? explode(',', get_field('form_rimborsi', 'option')) : array('sarah@befamily.it', 'mariachiaratroise@kidea.net');
        $to = $destinatari;
        
        // Sender 
        $from = $post['email']; 
        $fromName = $post['first-name'].' '.$post['last-name']; 
        
        // Headers
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'From: '.$fromName.' <'.$from.'>';
        // $headers[] = 'Cc: John Q Codex <jqc@wordpress.org>';
        // $headers[] = 'Cc: iluvwp@wordpress.org';


        // Email subject 
        $subject = 'Richiesta di Prenotazione spettacoli scuole';  
        
        // Attachment file
        $attachments = array();

        $content = array();
        $content[] = 'Richiedente: '.$post['type'];
        $content[] = 'Nome: '.$post['first-name'];
        $content[] = 'Cognome: '.$post['last-name'];
        $content[] = 'Email: '.$post['email'];
        $content[] = 'Tel.: '.$post['phone'];
        $content[] = 'Indirizzo: '.$post['address'];

        if (isset($post['interessi']) && !empty($post['interessi'])) {
            $content[] = 'Interessi: '.implode(', ',$post['interessi']);
        }

        $content[] = 'Scuola: '.$post['school-name'];
        $content[] = 'Indirizzo Scuola: '.$post['school-address'];
        $content[] = 'Tel. Scuola: '.$post['school-phone'];
        $content[] = 'Email Scuola: '.$post['school-email'];
        $content[] = 'C.F. Scuola: '.$post['school-cf'];

        $content[] = 'Spettacolo: '.$post['show-name1'];
        $content[] = 'Data: '.$post['show-date1'];

        if (isset($post['show-name2']))
            $content[] = 'Spettacolo Alternativo: '.$post['show-name2'];

        if (isset($post['show-date2']))
            $content[] = 'Data: '.$post['show-date2'];

        $content[] = 'Numero studenti paganti: '.$post['num-studenti-paganti'];
        $content[] = 'Numero docenti: '.$post['num-docenti'];
        $content[] = 'Numero studenti NON paganti: '.$post['num-studenti-non-paganti'];
        $content[] = 'Numero docenti sostegno: '.$post['num-docenti-sostegno'];

        $content[] = 'Richiesta fattura: '.$post['fattura'];
        if (isset($post['unicode']))
            $content[] = 'Codice univoco fattura: '.$post['unicode'];

        if (isset($post['uploads']) && !empty($post['uploads'])) {
            foreach ($post['uploads'] as $file) {
                if ($file['path'] != '') {
                    $attachments[] = $file['path'];
                }
            }
        }

        $message = implode('<br>', $content);
        
        // Send email 
        $mail = wp_mail( $to, $subject, $message, $headers, $attachments );

        $response_ok = array(
            'message_ok' => __('We received your request, thank you', 'san-carlo-theme'),
            'content' => $content,
            'uploads' => isset($post['file_remove']) ? $post['file_remove'] : ''
        );

        $response_ko = array(
            'message_ko' => __('Error sending email', 'san-carlo-theme'),
            'content' => $content,
            'uploads' => isset($post['file_remove']) ? $post['file_remove'] : ''
        );

        if ($mail) {
            wp_send_json_success($response_ok, 200);
        } else {
            wp_send_json_error($response_ko, 002 );
        }

    } else {
        wp_send_json_error( __('Error with data', 'san-carlo-theme'), 001 );
    }
}
add_action ( 'wp_ajax_nopriv_invia_mail_prenotazione', 'invia_mail_prenotazione', 10 );
add_action ( 'wp_ajax_invia_mail_prenotazione', 'invia_mail_prenotazione', 10 );

add_filter( 'wpml_pb_shortcode_encode', 'wpml_pb_shortcode_encode_urlencoded_json', 10, 3 );
function wpml_pb_shortcode_encode_urlencoded_json( $string, $encoding, $original_string ) {
    if ( 'urlencoded_json' === $encoding ) {
        $output = array();
        foreach ( $original_string as $combined_key => $value ) {
            $parts = explode( '_', $combined_key );
            $i = array_pop( $parts );
            $key = implode( '_', $parts );
            $output[ $i ][ $key ] = $value;
        }
        $string = urlencode( json_encode( $output ) );
    }
    return $string;
}
	 
add_filter( 'wpml_pb_shortcode_decode', 'wpml_pb_shortcode_decode_urlencoded_json', 10, 3 );
function wpml_pb_shortcode_decode_urlencoded_json( $string, $encoding, $original_string ) {
    if ( 'urlencoded_json' === $encoding ) {
        $rows = json_decode( urldecode( $original_string ), true );
        $string = array();
        foreach ( $rows as $i => $row ) {
            foreach ( $row as $key => $value ) {
            if ( in_array( $key, array( 'text', 'title', 'features', 'featured_img', 'substring', 'btn_text', 'label', 'value', 'link_button_slide', 'title_slide', 'text_slide', 'date_slide' ) ) ) {
                    $string[ $key . '_' . $i ] = array( 'value' => $value, 'translate' => true );
                } else {
                    $string[ $key . '_' . $i ] = array( 'value' => $value, 'translate' => false );
                }
            }
        }
    }
    return $string;
}

function events_en_query_shortcode($atts = [], $content = null) {
    $atts = shortcode_atts( array(
        'categoria_spettacoli' => '',
        'data_inizio' => 'now',
        ), $atts, 'events_en' );
    
    $output = array();

    if('icl_language_code' == 'en') {
        $categoria_spettacoli = $atts['categoria_spettacoli'];
        $data_inizio = $atts['data_inizio'];

        $tax_query = array();
        if ($categoria_spettacoli !== '') {
            $tax_query[] = array(
                'taxonomy' => 'categoria-spettacoli',
                'field' => 'term_id',
                'terms' => $categoria_spettacoli,
                'operator'   => 'IN',
            );
            $tax_query['relation'] = 'AND';
        }

        $args = array(
            'post_type' => 'spettacoli',
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby' => 'meta_value',
            'suppress_filters' => false,
            'meta_query' => array(array(
                'key' => 'data_inizio',
                'value' => date('Ymd', strtotime($data_inizio)),
                'compare' => '>=',
                'type' => 'DATE',
            )),
            'tax_query' => $tax_query
        );

        // var_dump($atts);
        // var_dump($args);
        $query = get_posts($args);

        // $output = array();

        foreach ($query as $post) {
            $id = $post->ID;
            $cats = array();
            $terms = get_the_terms( $id, 'categoria-spettacoli' ); 
            if (is_array($terms) && !empty($terms)):
            foreach($terms as $term) {
                $cats[] = $term->term_id;
            }
            endif;

            $output[] = (object) [
                'id' => $id,
                'slug' => $post->post_name,
                'link' => get_permalink($id),
                'title' => [
                    'rendered' => get_the_title($id)
                ],
                'excerpt' => get_the_excerpt( $id ),
                'categoria-spettacoli' => $cats,
                'acf' => [
                    'data_inizio' => get_field('data_inizio', $id),
                    'data_fine' => get_field('data_fine', $id),
                    'immagine_verticale' => get_field('immagine_verticale', $id),
                ]
            ];
        }
    }

    return json_encode($output);
}
add_shortcode( 'events_en', 'events_en_query_shortcode' );

function redirect_after_logout(){
    wp_set_current_user(0);
    wp_redirect( home_url() );
    exit();
}
add_action('wp_logout', 'redirect_after_logout');

function skip_logout_confirmation() {
    global $wp;
    if ( isset( $wp->query_vars['customer-logout'] ) ) {
        wp_redirect( str_replace( '&amp;', '&', wp_logout_url( home_url() ) ) );
        exit;
      }
}
add_action( 'template_redirect', 'skip_logout_confirmation' );

/**
 * Redirect no logged user after password reset to login page
 *
 * @return void
 */
function redirect_no_logged() {
    $body_classes = get_body_class();
    
    if (isset($_GET['password-reset']) && !in_array('logged-in', $body_classes)) {
        wp_redirect( home_url().'/accedi' );
        exit();
    }
}
add_action('template_redirect', 'redirect_no_logged');

/**
 * Redirect to home page if user try to access author page
 *
 * @return void
 */
function disable_author_page() {
    if ( is_author() ) {
        wp_redirect( home_url() );
    }
}
add_action( 'template_redirect', 'disable_author_page' );


/**
 * Add custom button text to WooCommerce checkout page
 */
add_filter( 'woocommerce_order_button_text', 'wc_custom_order_button_text' ); 

function wc_custom_order_button_text() {
    return __( 'Pay Securely', 'woocommerce' ); 
}

//REGISTRAZIONE CHECKBOX//

add_action( 'woocommerce_register_form', 'wtwh_add_registration_privacy_policy', 11 );
   
function wtwh_add_registration_privacy_policy() {
 
woocommerce_form_field( 'privacy_policy_reg', array(
   'type'          => 'checkbox',
   'class'         => array('form-row privacy'),
   'label_class'   => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
   'input_class'   => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
   'required'      => true,
   'label'         => __( "Ho preso visione della ", "woocommerce" ).'<a href="/privacy-policy">'. __( "Privacy Policy", "woocommerce" ).'</a>'. __( " della Fondazione Teatro di San Carlo", "woocommerce" ),
));
  
}
  
// Show error if user does not tick
   
add_filter( 'woocommerce_registration_errors', 'wtwh_validate_privacy_registration', 10, 3 );
  
function wtwh_validate_privacy_registration( $errors, $username, $email ) {
if ( ! is_checkout() ) {
    if ( ! (int) isset( $_POST['privacy_policy_reg'] ) ) {
        $errors->add( 'privacy_policy_reg_error', __( 'Devi accettare la Privacy Policy', 'woocommerce' ) );
    }
}
return $errors;
}



// Aggiungi script JavaScript per spostare gli elementi HTML
add_action('wp_footer', 'move_elements_with_js');

function move_elements_with_js() {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var privacyPolicyText = document.querySelector('.woocommerce-privacy-policy-text');
            var privacyRow = document.querySelector('.form-row.privacy');
            if (privacyPolicyText && privacyRow) {
                privacyRow.parentNode.insertBefore(privacyPolicyText, privacyRow);
            }
        });
    </script>
    <?php
}



// Aggiungi script JavaScript per inserire il paragrafo
add_action('wp_footer', 'insert_paragraph_with_js');

function insert_paragraph_with_js() {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var paragraph = document.createElement('p');
            var checkboxRow = document.querySelector('.mc4wp-checkbox.mc4wp-checkbox-wp-registration-form');
            if (paragraph && checkboxRow) {
                paragraph.innerHTML = '<?php echo esc_html__("Dando il tuo consenso, potrai anche registrarti gratuitamente alla Community del Teatro di San Carlo. Entrando a far parte di questo mondo, riceverai periodicamente newsletter, informazioni su iniziative speciali e promozioni dedicate sugli spettacoli in programma e sarai sempre aggiornato su tutto quello che accade al Teatro San Carlo. Se lo desideri, barra la casella in basso e unisciti a noi!", "woocommerce"); ?>';
                checkboxRow.parentNode.insertBefore(paragraph, checkboxRow);
            }
        });
    </script>
    <?php
}
//REGISTRAZIONE CHECKBOX//


/* FORM EDUCATIONAL NON ELIMINARE */

add_action('wpcf7_init', 'add_acf_fields_to_contact_form');

function add_acf_fields_to_contact_form() {
    
	wpcf7_add_form_tag('spettacolo_field', 'render_spettacolo_field_in_contact_form');
   
	wpcf7_add_form_tag('spettacolo_1_field', 'render_spettacolo_1_field_in_contact_form');
    wpcf7_add_form_tag('spettacolo_2_field', 'render_spettacolo_2_field_in_contact_form');
    wpcf7_add_form_tag('spettacolo_3_field', 'render_spettacolo_3_field_in_contact_form');
    wpcf7_add_form_tag('orario_1_field', 'render_orario_1_field_in_contact_form');
    wpcf7_add_form_tag('orario_2_field', 'render_orario_2_field_in_contact_form');
    wpcf7_add_form_tag('orario_3_field', 'render_orario_3_field_in_contact_form');
	wpcf7_add_form_tag('orario_4_field', 'render_orario_4_field_in_contact_form');
	wpcf7_add_form_tag('orario_5_field', 'render_orario_5_field_in_contact_form');
	wpcf7_add_form_tag('orario_6_field', 'render_orario_6_field_in_contact_form');
	wpcf7_add_form_tag('orario_7_field', 'render_orario_7_field_in_contact_form');
	wpcf7_add_form_tag('orario_8_field', 'render_orario_8_field_in_contact_form');
	wpcf7_add_form_tag('orario_9_field', 'render_orario_9_field_in_contact_form');
    
	wpcf7_add_form_tag('data_1_field', 'render_data_1_field_in_contact_form');
	wpcf7_add_form_tag('data_2_field', 'render_data_2_field_in_contact_form');
	wpcf7_add_form_tag('data_3_field', 'render_data_3_field_in_contact_form');
	wpcf7_add_form_tag('data_4_field', 'render_data_4_field_in_contact_form');
	wpcf7_add_form_tag('data_5_field', 'render_data_5_field_in_contact_form');
	wpcf7_add_form_tag('data_6_field', 'render_data_6_field_in_contact_form');
	wpcf7_add_form_tag('data_7_field', 'render_data_7_field_in_contact_form');
	wpcf7_add_form_tag('data_8_field', 'render_data_8_field_in_contact_form');
	wpcf7_add_form_tag('data_9_field', 'render_data_9_field_in_contact_form');
	
	wpcf7_add_form_tag('data_1_2_field', 'render_data_1_2_field_in_contact_form');
	wpcf7_add_form_tag('data_2_2_field', 'render_data_2_2_field_in_contact_form');
	wpcf7_add_form_tag('data_3_2_field', 'render_data_3_2_field_in_contact_form');
	wpcf7_add_form_tag('data_4_2_field', 'render_data_4_2_field_in_contact_form');
	wpcf7_add_form_tag('data_5_2_field', 'render_data_5_2_field_in_contact_form');
	wpcf7_add_form_tag('data_6_2_field', 'render_data_6_2_field_in_contact_form');
	wpcf7_add_form_tag('data_7_2_field', 'render_data_7_2_field_in_contact_form');
	wpcf7_add_form_tag('data_8_2_field', 'render_data_8_2_field_in_contact_form');
	wpcf7_add_form_tag('data_9_2_field', 'render_data_9_2_field_in_contact_form');
}


function render_spettacolo_field_in_contact_form($tag) {
    
    $spettacolo_1 = get_field('spettacolo_1');
    $spettacolo_2 = get_field('spettacolo_2');
    $spettacolo_3 = get_field('spettacolo_3');

    
    $spettacoli_options = array(
        $spettacolo_1,
        $spettacolo_2,
        $spettacolo_3
    );

   
    $spettacoli_options = array_unique($spettacoli_options);

    
    $output = '<select name="spettacolo" id="spettacolo">';
	$counter = 1;
    foreach ($spettacoli_options as $option) {
       
		
		if ($option) {
            
            $name = 'spettacolo_' . $counter;
            $output .= '<option value="' . esc_attr($option) . '" name="' . esc_attr($name) . '">' . esc_html($option) . '</option>';
            $counter++; 
        }
    }
	
    $output .= '</select>';

    return $output;
}

function render_data_1_field_in_contact_form($tag) {
    $data1 = get_field('prima_data_spettacolo_1');
    if ($data1) {
        return '<input id="data_1_field" type="checkbox" name="prima_data_spettacolo_1" value="' . esc_attr($data1) . '">' . esc_html($data1);
    } else {
        return '';
    }
}

function render_data_2_field_in_contact_form($tag) {
    $data2 = get_field('seconda_data_spettacolo_1');
    if ($data2) {
        return '<input id="data_2_field" type="checkbox" name="seconda_data_spettacolo_1" value="' . esc_attr($data2) . '">' . esc_html($data2);
    } else {
        return '';
    }
}

function render_data_3_field_in_contact_form($tag) {
    $data3 = get_field('terza_data_spettacolo_1');
    if ($data3) {
        return '<input id="data_3_field" type="checkbox" name="terza_data_spettacolo_1" value="' . esc_attr($data3) . '">' . esc_html($data3);
    } else {
        return '';
    }
}

function render_data_4_field_in_contact_form($tag) {
    $data4 = get_field('prima_data_spettacolo_2');
    if ($data4) {
        return '<input id="data_4_field" type="checkbox" name="quarta_data_spettacolo_1" value="' . esc_attr($data4) . '">' . esc_html($data4);
    } else {
        return '';
    }
}

function render_data_5_field_in_contact_form($tag) {
    $data5 = get_field('seconda_data_spettacolo_2');
    if ($data5) {
        return '<input id="data_5_field" type="checkbox" name="quinta_data_spettacolo_1" value="' . esc_attr($data5) . '">' . esc_html($data5);
    } else {
        return '';
    }
}

function render_data_6_field_in_contact_form($tag) {
    $data6 = get_field('terza_data_spettacolo_2');
    if ($data6) {
        return '<input id="data_6_field" type="checkbox" name="sesta_data_spettacolo_1" value="' . esc_attr($data6) . '">' . esc_html($data6);
    } else {
        return '';
    }
}

function render_data_7_field_in_contact_form($tag) {
    $data7 = get_field('prima_data_spettacolo_3');
    if ($data7) {
        return '<input id="data_7_field" type="checkbox" name="settima_data_spettacolo_1" value="' . esc_attr($data7) . '">' . esc_html($data7);
    } else {
        return '';
    }
}
function render_data_8_field_in_contact_form($tag) {
    $data7 = get_field('seconda_data_spettacolo_3');
    if ($data7) {
        return '<input id="data_8_field" type="checkbox" name="settima_data_spettacolo_1" value="' . esc_attr($data7) . '">' . esc_html($data7);
    } else {
        return '';
    }
}
function render_data_9_field_in_contact_form($tag) {
    $data7 = get_field('terza_data_spettacolo_3');
    if ($data7) {
        return '<input id="data_9_field" type="checkbox" name="settima_data_spettacolo_1" value="' . esc_attr($data7) . '">' . esc_html($data7);
    } else {
        return '';
    }
}

function render_orario_1_field_in_contact_form($tag) {
    $orario1 = get_field('orario_spettacolo_1');
    if ($orario1) {
        return '<input id="orario_1_field" type="checkbox" name="orario_spettacolo_1" value="' . esc_attr($orario1) . '">' . esc_html($orario1);
    } else {
        return '';
    }
}

function render_orario_2_field_in_contact_form($tag) {
    $orario2 = get_field('secondo_orario_spettacolo_1');
    if ($orario2) {
        return '<input id="orario_2_field" type="checkbox" name="secondo_orario_spettacolo_1" value="' . esc_attr($orario2) . '">' . esc_html($orario2);
    } else {
        return '';
    }
}

function render_orario_3_field_in_contact_form($tag) {
    $orario3 = get_field('terzo_orario_spettacolo_1');
    if ($orario3) {
        return '<input id="orario_3_field" type="checkbox" name="terzo_orario_spettacolo_1" value="' . esc_attr($orario3) . '">' . esc_html($orario3);
    } else {
        return '';
    }
}


function render_orario_4_field_in_contact_form($tag) {
    $orario4 = get_field('primo_orario_spettacolo_2');
    if ($orario4) {
        return '<input id="orario_4_field" type="checkbox" name="primo_orario_spettacolo_2" value="' . esc_attr($orario4) . '">' . esc_html($orario4);
    } else {
        return '';
    }
}

function render_orario_5_field_in_contact_form($tag) {
    $orario5 = get_field('secondo_orario_spettacolo_2');
    if ($orario5) {
        return '<input id="orario_5_field" type="checkbox" name="secondo_orario_spettacolo_2" value="' . esc_attr($orario5) . '">' . esc_html($orario5);
    } else {
        return '';
    }
}

function render_orario_6_field_in_contact_form($tag) {
    $orario6 = get_field('terzo_orario_spettacolo_2');
    if ($orario6) {
        return '<input id="orario_5_field" type="checkbox" name="terzo_orario_spettacolo_2" value="' . esc_attr($orario6) . '">' . esc_html($orario6);
    } else {
        return '';
    }
}
function render_orario_7_field_in_contact_form($tag) {
    $orario7 = get_field('primo_orario_spettacolo_3');
    if ($orario7) {
        return '<input id="orario_5_field" type="checkbox" name="primo_orario_spettacolo_3" value="' . esc_attr($orario7) . '">' . esc_html($orario7);
    } else {
        return '';
    }
}
function render_orario_8_field_in_contact_form($tag) {
    $orario8 = get_field('secondo_orario_spettacolo_3');
    if ($orario8) {
        return '<input id="orario_5_field" type="checkbox" name="secondo_orario_spettacolo_3" value="' . esc_attr($orario8) . '">' . esc_html($orario8);
    } else {
        return '';
    }
}
function render_orario_9_field_in_contact_form($tag) {
    $orario9 = get_field('terzo_orario_spettacolo_3');
    if ($orario9) {
        return '<input id="orario_5_field" type="checkbox" name="terzo_orario_spettacolo_3" value="' . esc_attr($orario9) . '">' . esc_html($orario9);
    } else {
        return '';
    }
}


add_action('wpcf7_init', 'add_acf_fields_to_contact_form2');

function add_acf_fields_to_contact_form2() {
    
	wpcf7_add_form_tag('spettacolo_field_2', 'render_spettacolo_field_in_contact_form2');
  
	wpcf7_add_form_tag('spettacolo_1_2_field', 'render_spettacolo_1_2_field_in_contact_form');
    wpcf7_add_form_tag('spettacolo_2_2_field', 'render_spettacolo_2_2_field_in_contact_form');
    wpcf7_add_form_tag('spettacolo_3_2_field', 'render_spettacolo_3_2_field_in_contact_form');
    wpcf7_add_form_tag('orario_1_2_field', 'render_orario_1_2_field_in_contact_form');
    wpcf7_add_form_tag('orario_2_2_field', 'render_orario_2_2_field_in_contact_form');
    wpcf7_add_form_tag('orario_3_2_field', 'render_orario_3_2_field_in_contact_form');
	wpcf7_add_form_tag('orario_4_2_field', 'render_orario_4_2_field_in_contact_form');
	wpcf7_add_form_tag('orario_5_2_field', 'render_orario_5_2_field_in_contact_form');
	wpcf7_add_form_tag('orario_6_2_field', 'render_orario_6_2_field_in_contact_form');
	wpcf7_add_form_tag('orario_7_2_field', 'render_orario_7_2_field_in_contact_form');
	wpcf7_add_form_tag('orario_8_2_field', 'render_orario_8_2_field_in_contact_form');
	wpcf7_add_form_tag('orario_9_2_field', 'render_orario_9_2_field_in_contact_form');
}


function render_spettacolo_field_in_contact_form2($tag) {
   
    $spettacolo_1 = get_field('spettacolo_1_2');
    $spettacolo_2 = get_field('spettacolo_2_2');
    $spettacolo_3 = get_field('spettacolo_3_2');

    $spettacoli_options = array(
        $spettacolo_1,
        $spettacolo_2,
        $spettacolo_3
    );

    $spettacoli_options = array_unique($spettacoli_options);

    
    $output = '<select name="spettacolo-2" id="spettacolo-2">';
	$counter = 1;
    foreach ($spettacoli_options as $option) {
      
		if ($option) {
            
            $name = 'spettacolo_' . $counter;
            $output .= '<option value="' . esc_attr($option) . '" name="' . esc_attr($name) . '">' . esc_html($option) . '</option>';
            $counter++; 
        }
    }
	
    $output .= '</select>';

    return $output;
}


function render_orario_1_2_field_in_contact_form($tag) {
    $orario1 = get_field('orario_spettacolo_1_2');
    if ($orario1) {
        return '<input id="orario_1_field" type="checkbox" name="orario_spettacolo_1" value="' . esc_attr($orario1) . '">' . esc_html($orario1);
    } else {
        return '';
    }
}

function render_orario_2_2_field_in_contact_form($tag) {
    $orario2 = get_field('secondo_orario_spettacolo_1_2');
    if ($orario2) {
        return '<input id="orario_2_field" type="checkbox" name="secondo_orario_spettacolo_1" value="' . esc_attr($orario2) . '">' . esc_html($orario2);
    } else {
        return '';
    }
}

function render_orario_3_2_field_in_contact_form($tag) {
    $orario3 = get_field('terzo_orario_spettacolo_1_2');
    if ($orario3) {
        return '<input id="orario_3_field" type="checkbox" name="terzo_orario_spettacolo_1" value="' . esc_attr($orario3) . '">' . esc_html($orario3);
    } else {
        return '';
    }
}


function render_orario_4_2_field_in_contact_form($tag) {
    $orario4 = get_field('primo_orario_spettacolo_2_2');
    if ($orario4) {
        return '<input id="orario_4_field" type="checkbox" name="primo_orario_spettacolo_2" value="' . esc_attr($orario4) . '">' . esc_html($orario4);
    } else {
        return '';
    }
}

function render_orario_5_2_field_in_contact_form($tag) {
    $orario5 = get_field('secondo_orario_spettacolo_2_2');
    if ($orario5) {
        return '<input id="orario_5_field" type="checkbox" name="secondo_orario_spettacolo_2" value="' . esc_attr($orario5) . '">' . esc_html($orario5);
    } else {
        return '';
    }
}

function render_orario_6_2_field_in_contact_form($tag) {
    $orario6 = get_field('terzo_orario_spettacolo_2_2');
    if ($orario6) {
        return '<input id="orario_5_field" type="checkbox" name="terzo_orario_spettacolo_2" value="' . esc_attr($orario6) . '">' . esc_html($orario6);
    } else {
        return '';
    }
}
function render_orario_7_2_field_in_contact_form($tag) {
    $orario7 = get_field('primo_orario_spettacolo_3_2');
    if ($orario7) {
        return '<input id="orario_5_field" type="checkbox" name="primo_orario_spettacolo_3" value="' . esc_attr($orario7) . '">' . esc_html($orario7);
    } else {
        return '';
    }
}
function render_orario_8_2_field_in_contact_form($tag) {
    $orario8 = get_field('secondo_orario_spettacolo_3_2');
    if ($orario8) {
        return '<input id="orario_5_field" type="checkbox" name="secondo_orario_spettacolo_3" value="' . esc_attr($orario8) . '">' . esc_html($orario8);
    } else {
        return '';
    }
}
function render_orario_9_2_field_in_contact_form($tag) {
    $orario9 = get_field('terzo_orario_spettacolo_3_2');
    if ($orario9) {
        return '<input id="orario_5_field" type="checkbox" name="terzo_orario_spettacolo_3" value="' . esc_attr($orario9) . '">' . esc_html($orario9);
    } else {
        return '';
    }
}

	function render_data_1_2_field_in_contact_form($tag) {
    $data1 = get_field('prima_data_spettacolo_1_2');
    if ($data1) {
        return '<input id="data_1_2_field" type="checkbox" name="prima_data_spettacolo_1_2" value="' . esc_attr($data1) . '">' . esc_html($data1);
    } else {
        return '';
    }
}

	function render_data_2_2_field_in_contact_form($tag) {
    $data1 = get_field('seconda_data_spettacolo_1_2');
    if ($data1) {
        return '<input id="data_2_2_field" type="checkbox" name="seconda_data_spettacolo_1_2" value="' . esc_attr($data1) . '">' . esc_html($data1);
    } else {
        return '';
    }
}

	function render_data_3_2_field_in_contact_form($tag) {
    $data1 = get_field('terza_data_spettacolo_1_2');
    if ($data1) {
        return '<input id="data_3_2_field" type="checkbox" name="terza_data_spettacolo_1_2" value="' . esc_attr($data1) . '">' . esc_html($data1);
    } else {
        return '';
    }
}

	function render_data_4_2_field_in_contact_form($tag) {
    $data1 = get_field('prima_data_spettacolo_2_2');
    if ($data1) {
        return '<input id="data_3_2_field" type="checkbox" name="prima_data_spettacolo_2_2" value="' . esc_attr($data1) . '">' . esc_html($data1);
    } else {
        return '';
    }
}

	function render_data_5_2_field_in_contact_form($tag) {
    $data1 = get_field('seconda_data_spettacolo_2_2');
    if ($data1) {
        return '<input id="data_3_2_field" type="checkbox" name="seconda_data_spettacolo_2_2" value="' . esc_attr($data1) . '">' . esc_html($data1);
    } else {
        return '';
    }
}


	function render_data_6_2_field_in_contact_form($tag) {
    $data1 = get_field('terza_data_spettacolo_2_2');
    if ($data1) {
        return '<input id="data_3_2_field" type="checkbox" name="terza_data_spettacolo_2_2" value="' . esc_attr($data1) . '">' . esc_html($data1);
    } else {
        return '';
    }
}


	function render_data_7_2_field_in_contact_form($tag) {
    $data1 = get_field('prima_data_spettacolo_3_2');
    if ($data1) {
        return '<input id="data_3_2_field" type="checkbox" name="prima_data_spettacolo_3_2" value="' . esc_attr($data1) . '">' . esc_html($data1);
    } else {
        return '';
    }
}


	function render_data_8_2_field_in_contact_form($tag) {
    $data1 = get_field('seconda_data_spettacolo_3_2');
    if ($data1) {
        return '<input id="data_3_2_field" type="checkbox" name="seconda_data_spettacolo_3_2" value="' . esc_attr($data1) . '">' . esc_html($data1);
    } else {
        return '';
    }
}


	function render_data_9_2_field_in_contact_form($tag) {
    $data1 = get_field('terza_data_spettacolo_3_2');
    if ($data1) {
        return '<input id="data_3_2_field" type="checkbox" name="terza_data_spettacolo_3_2" value="' . esc_attr($data1) . '">' . esc_html($data1);
    } else {
        return '';
    }
}