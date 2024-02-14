<?php
/*
 * Add Custom FIeld on Product Page
 */
//$user_id = get_current_user_id ();

/**
 * Display custom data on cart and checkout page.
 */
add_filter ( 'woocommerce_get_item_data', 'get_item_data', 25, 2 );

function get_item_data($other_data, $cart_item) {
    ob_start ();
    $selected_seats = $cart_item [ 'selected_seat_price' ];
    $transaction_ids = $cart_item [ 'transaction_ids' ];
//    if($_GET['print'] == 1){
//        echo "<pre>";
//        print_r($cart_item);
//        echo "</pre>";
//    }
    $tickets_array = array();
    foreach($transaction_ids[0] as $transaction_ids_key => $transaction_ids_value){
        $ticketName = $transaction_ids_value['ticketName'];
        $zoneId = $transaction_ids_key;
        $zoneName = $transaction_ids_value['zoneName'];
        $seatObject = $transaction_ids_value['seatObject'];
        
        $tickets_array[$ticketName][] = array(
            'zoneId' => $transaction_ids_key,
            'zoneName' => $zoneName,
            'seatObject' => $seatObject,
        );
    }
//    if($_GET['print'] == 1){
//        echo "<pre>";
//        print_r($tickets_array);
//        echo "</pre>";
//    }
    ?>
    <div class="spettacolo-cart-wrapper wc-spettacolo-cart-wrapper test">
        <div class="container">
            <div class="spettacolo-cart-inner">
                <div class="spettacolo-tickets">
                    <?php
                    if( ! empty ( $tickets_array ) ) {
                        $counter = 1;
                        foreach($tickets_array as $tickets_array_key => $tickets_array_value){
                            $extra_class = $counter < count($tickets_array) ? 'border-bot' : '' ;
                            $counter++;
                            $ticketName = $tickets_array_key;
                            ?>
                            <div class="ticket-datails-wrap <?php echo $extra_class; ?>">
                                <div class="ticket-title" data-name="<?php echo $ticketName; ?>">
                                    <h2><?php echo $ticketName; ?></h2>
                                </div>
                                <?php
                                if( ! empty ( $tickets_array_value ) ) {
                                    foreach ( $tickets_array_value as $tickets_array_value_k => $tickets_array_value_v ) {
                                        $zoneName   = $tickets_array_value_v[ 'zoneName' ];
                                        $zoneId     = $tickets_array_value_v[ 'zoneId' ];
                                        $seatObject = $tickets_array_value_v[ 'seatObject' ];
                                        ?>
                                        <div class="ticket-zone">
                                            <div class="zone-title" data-zoneId="<?php echo $zoneId; ?>">
                                                <h4><?php echo $zoneName; ?></h4>
                                            </div>
                                            <?php
                                            if(!empty($seatObject)){
                                                if( array_key_first( $seatObject ) == '0' ) {
                                                    $seatObject_new = $seatObject;
                                                } else {
                                                    $seatObject_new = array($seatObject);            
                                                }
                                            }
                                            foreach ( $seatObject_new as $seatObject_key => $seatObject_value ) {
                                                $seat_desc      = $seatObject_value[ 'description' ];
                                                $seat_price     = $seatObject_value[ 'price' ];
                                                $seat_price = !empty($seat_price) ? (float)$seat_price/100  : 0 ;
                                                $seat_reduction = $seatObject_value[ 'reduction' ];
                                                $reduction_id   = $seat_reduction[ '@attributes' ][ 'id' ];
                                                $reduction_name = $seat_reduction[ 'description' ];
                                                $reductionQuantity = 1;
                                                ?>
                                                <div class="zone-reductions">
                                                    <div class="seat-title" data-reductionId="<?php echo $reduction_id; ?>">
                                                        <p><?php echo preg_replace('/' . preg_quote($zoneName, '/') . '/', '', $seat_desc, 1); ?></p>
                                                    </div>
                                                    <div class="tipoticketeprezzo">
                                                    <div class="reduction-title-wrap">
                                                        <div class="reduction-title">
                                                            <p><?php echo $reduction_name; ?></p>
                                                        </div>
                                                        <div class="reduction-qty">
                                                            <p><?php echo " X " . $reductionQuantity; ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="reduction-price">
                                                        <p><?php echo "- " . $seat_price . " &euro;"; ?></p>
                                                    </div>
                                                </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>    
                        <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    $html = ob_get_clean ();
//    return $html;
//    if ( isset( $cart_item [ 'custom_data' ] ) ) {
//        $custom_data = $cart_item [ 'custom_data' ];

    $other_data[] = array (
        'name'    => 'Spettacolo',
        'display' => $html
    );
//    }
    return $other_data;
}

/**
 * Add data to cart item
 */
 
add_filter ( 'woocommerce_add_cart_item_data', 'add_cart_item_data', 99999, 2 );

function add_cart_item_data($cart_item_meta, $product_id) {
//    if( isset ( $_POST [ 'product_del_price_' . $user_id ] ) ) {
    
        $user_id        = get_current_user_id ();
        $get_user_meta  = get_user_meta ( $user_id, 'addToCartObject' );
        $transactionIds  = get_user_meta ( $user_id, 'transactionIds' );
            $cart_item_meta [ 'selected_seat_price' ] = $get_user_meta;
            $cart_item_meta [ 'transaction_ids' ] = $transactionIds;
//        }

    return $cart_item_meta;
}

/**
 * Add order item meta.
 */
add_action ( 'woocommerce_add_order_item_meta', 'add_order_item_meta', 10, 2 );

function add_order_item_meta($item_id, $values) {
    
    if( isset ( $values [ 'selected_seat_price' ] ) ) {

//        $custom_data = $values [ 'custom_data' ];
        wc_add_order_item_meta ( $item_id, 'Prezzo del posto scelto', $values [ 'selected_seat_price' ] );
    }
}

/*
 * set price for item in cart
 */
add_action ( 'woocommerce_before_calculate_totals', 'alter_price_cart', 9999 );

function alter_price_cart($cart) {
    $totalPrice     = 0;
    $totalQty       = 0;
    foreach ( $cart->get_cart () as $cart_item_key => $cart_item ) {
        $selected_seats = $cart_item[ 'selected_seat_price' ];
        if( ! empty ( $selected_seats[ 0 ] ) ) {
            foreach ( $selected_seats[ 0 ] as $meta_key => $meta_value ) {
                if( ! empty ( $meta_value ) ) {
                    foreach ( $meta_value as $meta_k => $meta_v ) {
                        $reductions = $meta_v[ 'reductions' ];
                        if( ! empty ( $reductions ) ) {
                            foreach ( $reductions as $reductions_key => $reductions_value ) {
                                $reductionQuantity = $reductions_value[ 'reductionQuantity' ];
                                $reductionPrice    = $reductions_value[ 'reductionPrice' ];
                                $totalPrice        = $totalPrice + ((int) $reductionPrice * (int) $reductionQuantity);
                                $totalQty          = $totalQty + (int) $reductionQuantity;
                            }
                        }
                    }
                }
            }
        }
//        $total_price   = $custom_data[ 'product_del_price_' . $user_id ];
        if( ! empty ( $totalPrice ) && $totalPrice != 0 ) {
            $cart_item[ 'data' ]->set_price ( $totalPrice );
        }
    }
}

// ADD THE INFORMATION AS META DATA SO THAT IT CAN BE SEEN AS PART OF THE ORDER
//add_action('woocommerce_add_order_item_meta','add_and_update_values_to_order_item_meta', 1, 3 );
//function add_and_update_values_to_order_item_meta( $item_id, $item_values, $item_key ) {
//    echo "<pre>";
//    print_r($item_id);
//    print_r($item_values);
//    print_r($item_key);
//    echo "</pre>";
//    // Getting your custom product ID value from order item meta
//    $custom_value = wc_get_order_item_meta( $item_id, 'custom_field_key', true );
//
//    // Here you update the attribute value set in your simple product
//    wc_update_order_item_meta( $item_id, 'pa_your_attribute', $custom_value );
//    
//}

//add_action( 'woocommerce_thankyou', 'woocommerce_redirectcustom');
//function woocommerce_redirectcustom( $order_id ){
//    echo "<pre>";
//    print_r($order );
//    echo "</pre>";
//    die();
//    $order = wc_get_order( $order_id );
//    $url = 'https://www.google.com/';
//    if ( ! $order->has_status( 'failed' ) ) {
////        wp_redirect( $url , 302 );
//        header('Location: ' . $url);
//        exit;
//    }
//}

add_filter( 'woocommerce_return_to_shop_redirect', 'woocommerce_change_return_shop_url_fun' );
 
function woocommerce_change_return_shop_url_fun() {
   return site_url().'/spettacoli/';
}

add_action( 'woocommerce_order_details_after_order_table_items', 'woocommerce_order_details_fun' );

function woocommerce_order_details_fun( $order ) {
    ob_start ();
    $selected_seats = get_post_meta( $order->get_id(), 'confirmedOrderObject', true );
    $transaction_ids = get_post_meta( $order->get_id(), 'transactionIds', true );    
//    echo "<pre>";
//    print_r($transaction_ids);
//    echo "</pre>";
    $tickets_array = array();
    foreach($transaction_ids as $transaction_ids_key => $transaction_ids_value){
        $ticketName = $transaction_ids_value['ticketName'];
        $zoneId = $transaction_ids_key;
        $zoneName = $transaction_ids_value['zoneName'];
        $seatObject = $transaction_ids_value['seatObject'];
        
        $tickets_array[$ticketName][] = array(
            'zoneId' => $transaction_ids_key,
            'zoneName' => $zoneName,
            'seatObject' => $seatObject,
        );
    }
    ?>
    <div class="spettacolo-cart-wrapper wc-spettacolo-cart-wrapper ticket-order-table">
        <div class="container">
            <div class="spettacolo-cart-inner">
                <div class="spettacolo-tickets">
                    <?php
                    if( ! empty ( $tickets_array ) ) {
                        $counter = 1;
                        foreach($tickets_array as $tickets_array_key => $tickets_array_value){
                            $extra_class = $counter < count($tickets_array) ? 'border-bot' : '' ;
                            $counter++;
                            $ticketName = $tickets_array_key;
                            ?>
                            <div class="ticket-datails-wrap <?php echo $extra_class; ?>">
                                <div class="ticket-title" data-name="<?php echo $ticketName; ?>">
                                    <h2><?php echo $ticketName; ?></h2>
                                </div>
                                <?php
                                if( ! empty ( $tickets_array_value ) ) {
                                    foreach ( $tickets_array_value as $tickets_array_value_k => $tickets_array_value_v ) {
                                        $zoneName   = $tickets_array_value_v[ 'zoneName' ];
                                        $zoneId     = $tickets_array_value_v[ 'zoneId' ];
                                        $seatObject = $tickets_array_value_v[ 'seatObject' ];
                                        ?>
                                        <div class="ticket-zone">
                                            <div class="zone-title" data-zoneId="<?php echo $zoneId; ?>">
                                                <h4><?php echo $zoneName; ?></h4>
                                            </div>
                                            <?php
                                            if(!empty($seatObject)){
                                                if( array_key_first( $seatObject ) == '0' ) {
                                                    $seatObject_new = $seatObject;
                                                } else {
                                                    $seatObject_new = array($seatObject);            
                                                }
                                            }
                                            foreach ( $seatObject_new as $seatObject_key => $seatObject_value ) {
                                                $seat_desc      = $seatObject_value[ 'description' ];
                                                $seat_price     = $seatObject_value[ 'price' ];
                                                $seat_price = !empty($seat_price) ? (float)$seat_price/100  : 0 ;
                                                $seat_reduction = $seatObject_value[ 'reduction' ];
                                                $reduction_id   = $seat_reduction[ '@attributes' ][ 'id' ];
                                                $reduction_name = $seat_reduction[ 'description' ];
                                                $reductionQuantity = 1;
                                                ?>
                                                <div class="zone-reductions">
                                                    <div class="seat-title" data-reductionId="<?php echo $reduction_id; ?>">
                                                        <p><?php echo preg_replace('/' . preg_quote($zoneName, '/') . '/', '', $seat_desc, 1); ?></p>
                                                    </div>
                                                    <div class="tipoticketeprezzo">
                                                    <div class="reduction-title-wrap">
                                                        <div class="reduction-title">
                                                            <p><?php echo $reduction_name; ?></p>
                                                        </div>
                                                        <div class="reduction-qty">
                                                            <p><?php echo " X " . $reductionQuantity; ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="reduction-price">
                                                        <p><?php echo "- " . $seat_price . " &euro;"; ?></p>
                                                    </div>
                                                </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>    
                        <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    $contact_method = ob_get_clean ();
    if( $contact_method ){
        ?>
            <tr>
                <th scope="row">Biglietti ordinati:</th>
                <td><?php echo $contact_method; ?></td>
            </tr>
        <?php
    }
}

/*
function remove_woocommerce_order_details_table_head() {
    
    if (is_order_received_page()) {
        remove_action('woocommerce_before_order_table', 'woocommerce_order_details_table', 10);
        add_action('woocommerce_order_details_after_order_table', 'custom_order_details_table');
    }
}

function custom_order_details_table() {
    // Get the order
    $order = wc_get_order($GLOBALS['wp']->query_vars['order-received']);

    // Output the order details table without the thead
    ?>
    <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
        <tbody>
            <?php
            foreach ($order->get_items() as $item_id => $item) :
                $product = $item->get_product();
                ?>
                <tr class="woocommerce-table__line-item order_item">
                    <td class="woocommerce-table__product-name product-name">
                        <?php echo $product->get_name(); ?>
                    </td>
                    <!-- Add other columns as needed -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}

add_action('woocommerce_order_details_after_order_table', 'custom_order_details_table', 20);
add_action('init', 'remove_woocommerce_order_details_table_head');
*/

//add_filter( 'woocommerce_order_item_display_meta_key', 'change_order_item_meta_title', 20, 3 );
//
///**
// * Changing a meta title
// * @param  string        $key  The meta key
// * @param  WC_Meta_Data  $meta The meta object
// * @param  WC_Order_Item $item The order item object
// * @return string        The title
// */
//function change_order_item_meta_title( $key, $meta, $item ) {
//    echo "<pre>";
//    print_r($key);
//    print_r($meta);
//    print_r($item);
//    echo "</pre>";
//    // By using $meta-key we are sure we have the correct one.
//    if ( 'confirmedOrderObject' == $meta->key ) { $key = 'SOMETHING'; }
//     
//    return $key;
//}
//
//add_filter( 'woocommerce_order_item_display_meta_value', 'change_order_item_meta_value', 20, 3 );
//
///**
// * Changing a meta value
// * @param  string        $value  The meta value
// * @param  WC_Meta_Data  $meta   The meta object
// * @param  WC_Order_Item $item   The order item object
// * @return string        The title
// */
//function change_order_item_meta_value( $value, $meta, $item ) {
//    
//    echo "<pre>";
//    print_r($key);
//    print_r($meta);
//    print_r($item);
//    echo "</pre>";
//    // By using $meta-key we are sure we have the correct one.
//    if ( 'confirmedOrderObject' == $meta->key ) { $value = 'SOMETHING'; }
//     
//    return $value;
//}

// Save custom order item meta
//add_action( 'woocommerce_checkout_create_order_line_item', 'save_custom_order_item_meta', 10, 4 );
//function save_custom_order_item_meta( $item, $cart_item_key, $values, $order ) {
//    if ( isset($values['file']) && ! empty($values['file']) ) {
//        // Save it in an array to hide meta data from admin order items
//        $item->add_meta_data('file', array( $values['file'] ) );
//    }
//}

// Get custom order item meta and display a linked download button
add_action( 'woocommerce_after_order_itemmeta', 'display_admin_order_item_custom_button', 10, 3 );
function display_admin_order_item_custom_button( $item_id, $item, $product ){
    // Only "line" items and backend order pages
    global $woocommerce;
    $order_id = $item->get_order_id();
    $order = wc_get_order( $order_id );
    $confirmedOrderObject = $order->get_meta('confirmedOrderObject',true,'view'); // Get order item meta data (array)
    $transaction_ids = $order->get_meta('transactionIds',true,'view'); // Get order item meta data (array)
    $tickets_array = array();
    /** MoD sarah */
    if(is_array($transaction_ids) && !empty($transaction_ids) ): //Check if $trasaction_id is an array and not empty
        foreach($transaction_ids as $transaction_ids_key => $transaction_ids_value){
            $ticketName = $transaction_ids_value['ticketName'];
            $zoneId = $transaction_ids_key;
            $zoneName = $transaction_ids_value['zoneName'];
            $seatObject = $transaction_ids_value['seatObject'];
            
            $tickets_array[$ticketName][] = array(
                'zoneId' => $transaction_ids_key,
                'zoneName' => $zoneName,
                'seatObject' => $seatObject,
            );
        }
    endif;
    ?>
    <div class="spettacolo-cart-wrapper wc-spettacolo-cart-wrapper ticket-order-table">
        <div class="container">
            <div class="spettacolo-cart-inner">
                <div class="spettacolo-tickets">
                    <?php
                    if( ! empty ( $tickets_array ) ) {
                        $counter = 1;
                        foreach($tickets_array as $tickets_array_key => $tickets_array_value){
                            $extra_class = $counter < count($tickets_array) ? 'border-bot' : '' ;
                            $counter++;
                            $ticketName = $tickets_array_key;
                            ?>
                            <div class="ticket-datails-wrap <?php echo $extra_class; ?>">
                                <div class="ticket-title" data-name="<?php echo $ticketName; ?>">
                                    <h2><?php echo $ticketName; ?></h2>
                                </div>
                                <?php
                                if( ! empty ( $tickets_array_value ) ) {
                                    foreach ( $tickets_array_value as $tickets_array_value_k => $tickets_array_value_v ) {
                                        $zoneName   = $tickets_array_value_v[ 'zoneName' ];
                                        $zoneId     = $tickets_array_value_v[ 'zoneId' ];
                                        $seatObject = $tickets_array_value_v[ 'seatObject' ];
                                        ?>
                                        <div class="ticket-zone">
                                            <div class="zone-title" data-zoneId="<?php echo $zoneId; ?>">
                                                <h4><?php echo $zoneName; ?></h4>
                                            </div>
                                            <?php
                                            if(!empty($seatObject)){
                                                if( array_key_first( $seatObject ) == '0' ) {
                                                    $seatObject_new = $seatObject;
                                                } else {
                                                    $seatObject_new = array($seatObject);            
                                                }
                                            }
                                            foreach ( $seatObject_new as $seatObject_key => $seatObject_value ) {
                                                $seat_desc      = $seatObject_value[ 'description' ];
                                                $seat_price     = $seatObject_value[ 'price' ];
                                                $seat_price = !empty($seat_price) ? (float)$seat_price/100  : 0 ;
                                                $seat_reduction = $seatObject_value[ 'reduction' ];
                                                $reduction_id   = $seat_reduction[ '@attributes' ][ 'id' ];
                                                $reduction_name = $seat_reduction[ 'description' ];
                                                $reductionQuantity = 1;
                                                ?>
                                                <div class="zone-reductions">
                                                    <div class="seat-title" data-reductionId="<?php echo $reduction_id; ?>">
                                                        <p><?php echo preg_replace('/' . preg_quote($zoneName, '/') . '/', '', $seat_desc, 1); ?></p>
                                                    </div>
                                                    <div class="tipoticketeprezzo">
                                                    <div class="reduction-title-wrap">
                                                        <div class="reduction-title">
                                                            <p><?php echo $reduction_name; ?></p>
                                                        </div>
                                                        <div class="reduction-qty">
                                                            <p><?php echo " X " . $reductionQuantity; ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="reduction-price">
                                                        <p><?php echo "- " . $seat_price . " &euro;"; ?></p>
                                                    </div>
                                                </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>    
                        <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/*
* Show Phone Number field at registration Page
*/
function wooc_extra_register_fields() {
    $country_code = (isset($_POST['country_code']) && !empty($_POST['country_code']))  ? $_POST['country_code'] : "+39" ;
    $billing_phone = (isset($_POST['billing_phone']) && !empty($_POST['billing_phone'])) ? $_POST['billing_phone'] :  '';
    $dob = (isset($_POST['dob']) && !empty($_POST['dob'])) ? $_POST['dob'] :  '';
    $pob = (isset($_POST['place_of_birth']) && !empty($_POST['place_of_birth'])) ? $_POST['place_of_birth'] :  '';
    ?>
        <p class="form-row form-row-wide">
            <label for="reg_billing_phone"><?php _e( 'Telefono *', 'woocommerce' ); ?></label>
            <input type="text" class="input-text" name="country_code" id="reg_country_code" value="<?php echo $country_code; ?>" disabled/>
            <input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php esc_attr_e( $billing_phone ); ?>" />
        </p>
        <p class="form-row form-row-wide">
            <label for="reg_dob"><?php _e( 'Data di nascita *', 'woocommerce' ); ?></label>
            <input type="date" class="input-text" name="dob" id="reg_dob" placeholder="GG-MM-AAAA" value="<?php esc_attr_e( $dob ); ?>" />
        </p>
        <p class="form-row form-row-wide">
            <label for="reg_place_of_birth"><?php _e( 'Luogo di nascita *', 'woocommerce' ); ?></label>
            <input type="text" class="input-text" name="place_of_birth" id="reg_place_of_birth" value="<?php esc_attr_e( $pob ); ?>" />
        </p>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide otp-box" style="display:none;">
            <label for="registerotp"><?php esc_html_e( 'OTP', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
            <input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="registerotp" id="registerotp" autocomplete="OTP" />
        </p>
       <?php
 }
//add_action( 'woocommerce_register_form', 'wooc_extra_register_fields' );

/*
* Validate Phone Number field at registration Page
*/
function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
	if ( isset( $_POST['billing_phone'] ) && empty( $_POST['billing_phone'] ) ) {
            $validation_errors->add( 'billing_phone_error', __( '<strong>Errore</strong>: Numero di telefono obbligatorio', 'woocommerce' ) );
       }
	if ( isset( $_POST['registerotp'] ) && empty( $_POST['registerotp'] ) ) {
            $validation_errors->add( 'otp_error', __( '<strong>Errore</strong>: Il codice OTP è obbligatorio', 'woocommerce' ) );
       }
}
add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );

/**
* Save Phone Number got from registration page
*/
function wooc_save_extra_register_fields( $customer_id ) {
    if ( isset( $_POST['billing_phone'] ) ) {
        // Phone input filed which is used in WooCommerce
        update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
    }
}
add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );

// After registration, logout the user and redirect to home page
function custom_registration_redirect() {
//    wp_logout();
    return site_url()."/mio-account";
}
add_action('woocommerce_registration_redirect', 'custom_registration_redirect', 20);

add_action( 'user_register', 'new_contact', 10, 3 );
function new_contact( $user_id ) {

    if ( isset( $_POST['first_name'] ) )
        update_user_meta($user_id, 'first_name', $_POST['first_name']);

    $update_user_meta = update_user_meta($user_id,'country_code',$_POST['country_code']);
    $update_user_meta = update_user_meta($user_id,'billing_phone',$_POST['billing_phone']);
    $update_user_meta = update_user_meta($user_id,'dob',$_POST['dob']);
    $update_user_meta = update_user_meta($user_id,'place_of_birth',$_POST['place_of_birth']);
    $update_user_meta = update_user_meta($user_id,'user_ip',$_SERVER['REMOTE_ADDR']);
}

function woocommerce_cart_updated( $cart_item_key, $cart ) {

    $user_id = get_current_user_id();
    $transactionIds          = get_user_meta( $user_id, 'transactionIds' , true );
    $transactions_str = "";
    if(!empty($transactionIds)){
        foreach($transactionIds as $transactionIds_key => $transactionIds_value){
            $transactions_str .= "transactionCode[]=".$transactionIds_value['transaction_id']."&";
        }
        $transactions_str = rtrim($transactions_str,"&");
        $curl_url = API_HOST . 'backend/backend.php?id=' . APIKEY . '&cmd=setExpiry&' . $transactions_str . '&timeout=0&preserveOnError=1';
        
        $set_expiry_curl = curl_init();

        curl_setopt_array($set_expiry_curl, array(
          CURLOPT_URL => $curl_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $set_expiry_response = curl_exec ( $set_expiry_curl );
        $err      = curl_error ( $set_expiry_curl );
        curl_close($set_expiry_curl);
        
        $xml         = simplexml_load_string ( $set_expiry_response );
        $json        = json_encode ( $xml );
        $set_expiry_response = json_decode ( $json, TRUE );
    }
    
    update_user_meta($user_id,'addToCartObject',array());
    update_user_meta($user_id,'transactionIds',array());
    
};
add_action( 'woocommerce_remove_cart_item', 'woocommerce_cart_updated', 99, 2 );

function change_date_time_in_italy($date , $format){
    $default_timezone = date_default_timezone_get();
    date_default_timezone_set('Europe/Rome');

    // Define the language and locale
    $locale = 'it_IT';

    // Create an IntlDateFormatter object
    $date_formatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'Europe/Rome', IntlDateFormatter::GREGORIAN);

    // Set the date pattern
    $date_formatter->setPattern($format);

    // Format the date
    $italian_date = $date_formatter->format($date);

    // Print the formatted date
    date_default_timezone_set($default_timezone);
    return $italian_date;
}

/**
 * Show custom user profile fields
 * 
 * @param  object $profileuser A WP_User object
 * @return void
 */
function custom_user_profile_fields( $profileuser ) {
    $dob = get_user_meta($profileuser->ID,'dob',true);
    $place_of_birth = get_user_meta($profileuser->ID,'place_of_birth',true);
    $user_ip = get_user_meta($profileuser->ID,'user_ip',true);
    $user_registered = get_user_meta($profileuser->ID,'user_registered',true);
    $user_modified = get_user_meta($profileuser->ID,'user_modified',true);
    $gender = get_user_meta($profileuser->ID,'gender',true);
    $newsletter = get_user_meta($profileuser->ID,'newsletter',true);
    $marketing = get_user_meta($profileuser->ID,'marketing',true);
    $active = get_user_meta($profileuser->ID,'active',true);
    $old_user_id = get_user_meta($profileuser->ID,'old_user_id',true);
?>
	<table class="form-table">
            <tr>
                <th>
                    <label for="dob"><?php echo 'Data di nascita'; ?></label>
                </th>
                <td>
                    <input type="text" name="dob" id="dob" value="<?php echo $dob ; ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="place_of_birth"><?php echo 'Luogo di nascita'; ?></label>
                </th>
                <td>
                    <input type="text" name="place_of_birth" id="place_of_birth" value="<?php echo $place_of_birth ; ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="user_ip"><?php echo 'Indirizzo IP di registrazione'; ?></label>
                </th>
                <td>
                    <input type="text" name="user_ip" id="user_ip" value="<?php echo $user_ip ; ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="user_registered"><?php echo 'user registered time'; ?></label>
                </th>
                <td>
                    <input type="text" name="user_registered" id="user_registered" value="<?php echo $user_registered ; ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="user_modified"><?php echo 'user modified time'; ?></label>
                </th>
                <td>
                    <input type="text" name="user_modified" id="user_modified" value="<?php echo $user_modified ; ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="gender"><?php echo 'Gender'; ?></label>
                </th>
                <td>
                    <input type="text" name="gender" id="gender" value="<?php echo $gender ; ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="newsletter"><?php echo 'Newsletter'; ?></label>
                </th>
                <td>
                    <input type="text" name="newsletter" id="newsletter" value="<?php echo $newsletter ; ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="marketing"><?php echo 'Marketing'; ?></label>
                </th>
                <td>
                    <input type="text" name="marketing" id="marketing" value="<?php echo $marketing ; ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="active"><?php echo 'Active user'; ?></label>
                </th>
                <td>
                    <input type="text" name="active" id="active" value="<?php echo $active ; ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th>
                    <label for="old_user_id"><?php echo 'Old user id'; ?></label>
                </th>
                <td>
                    <input type="text" name="old_user_id" id="old_user_id" value="<?php echo $old_user_id ; ?>" class="regular-text" />
                </td>
            </tr>
	</table>
<?php
}
add_action( 'show_user_profile', 'custom_user_profile_fields', 10, 1 );
add_action( 'edit_user_profile', 'custom_user_profile_fields', 10, 1 );

add_filter( 'manage_spettacolo_posts_columns', 'manage_spettacolo_posts_columns_fun' );
function manage_spettacolo_posts_columns_fun( $columns ) {
    $columns = array(
      'cb' => $columns['cb'],
      'title' => __( 'Title' ),
      'shortcode' => __( 'Shortcode' ),
      'date' => __( 'Date' )
    );
  return $columns;
}
add_action( 'manage_spettacolo_posts_custom_column', 'manage_spettacolo_posts_custom_column_fun', 10, 2);
function manage_spettacolo_posts_custom_column_fun( $column, $post_id ) {
  // Image column
  if ( 'shortcode' === $column ) {
    echo '[spettacolo_event_listing id="'.$post_id.'"]';
  }
}

// create custom plugin settings menu
add_action('admin_menu', 'tickets_plugin_create_menu');

function tickets_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('Tickets Plugin Settings', 'Tickets Settings', 'administrator', __FILE__, 'tickets_plugin_settings_page' , 'dashicons-tickets-alt' );

	//call register settings function
	add_action( 'admin_init', 'register_tickets_plugin_settings' );
}


function register_tickets_plugin_settings() {
	//register our settings
	register_setting( 'my-tickets-settings-group', 'select_product' );
}

function tickets_plugin_settings_page() {
?>
<div class="wrap">
<h1>Tickets</h1>
    <?php
    if(isset($_POST['wc-ticket-product'])){
        update_option( 'wc_ticket_product', $_POST['wc-ticket-product'] );
    }
    ?>
    <form method="post" action="">
        <?php settings_fields( 'my-tickets-settings-group' ); ?>
        <?php do_settings_sections( 'my-tickets-settings-group' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th><label for="wc-ticket-product">Choose a Product:</label></th>
                <td><select name="wc-ticket-product" id="wc-ticket-product">
                <?php 
                $products = wc_get_products( array( 'status' => 'publish', 'limit' => -1 ) );
                foreach ( $products as $product ){ 
                    $product_status = $product->get_status();  // Product status
                    $product_id     = $product->get_id();    // Product ID
                    $product_title  = $product->get_title(); // Product title
                    $product_slug   = $product->get_slug(); // Product slug
                    if($product_status == 'publish'){
                    ?>
                        <option value="<?php echo $product_id;?>" data-slug="<?php echo $product_slug;?>" data-id="<?php echo $product_id;?>"><?php echo $product_title;?></option>
                    <?php
                    }    
                }
                ?>
                </select></td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
<?php }

function stcticket_spettacolo_data( $id ) {
    $response = array(
        'titolo' => '',
        'location' => '',
        'date' => array()
    );
    if(!empty($id)){
        $post = get_post($id);
        $post_title = !empty($post) ? $post->post_title : '';
        $spe_start_date     = get_post_meta( $id, 'spt_startDate', true );
        $spe_end_date       = get_post_meta( $id, 'spt_endDate', true );
        $curr_start_date    = !empty($spe_start_date) ? explode( "/", $spe_start_date ) : '';
        $final_start_date   = !empty($curr_start_date) ? $curr_start_date[ 1 ] . '/' . $curr_start_date[ 0 ] . '/' . $curr_start_date[ 2 ] : '';
        $curr_end_date      = !empty($spe_end_date) ? explode( "/", $spe_end_date ) : '';
        $final_end_date     = !empty($curr_end_date) ? $curr_end_date[ 1 ] . '/' . $curr_end_date[ 0 ] . '/' . $curr_end_date[ 2 ] : '';
        $final_start_date   = change_date_time_in_italy(strtotime ( $final_start_date ),'dd MMMM y');
        $final_end_date     = change_date_time_in_italy(strtotime ( $final_end_date ),'dd MMMM y') ;
        $spt_vcode          = get_post_meta( $id, 'spt_vcode', true );
        $spt_tit_info_title = get_post_meta( $id, 'spt_tit_info_title', true );
        $tit_info_perform   = !empty($spt_tit_info_title[ 'tit_info_perform' ]) ? $spt_tit_info_title[ 'tit_info_perform' ] : '';
        $spt_location       = !empty(get_post_meta( $id, 'spt_location', true )) ? get_post_meta( $id, 'spt_location', true ) : 'Teatro San Carlo - NAPOLI';
        $date_array = array();
        if( ! empty( $tit_info_perform ) ) {
            foreach ( $tit_info_perform as $tit_info_perform_key => $tit_info_perform_value ) {
                if( count( $tit_info_perform ) > 1 ) {
                    $tit_info_perform_value = $tit_info_perform_value[ '@attributes' ];
                }
                $info_start_date = $tit_info_perform_value[ 'dataInizio' ];
                $info_curr_date  = explode( "/", $info_start_date );
                $info_final_date = $info_curr_date[ 1 ] . '/' . $info_curr_date[ 0 ] . '/' . $info_curr_date[ 2 ];
                $info_final_date = change_date_time_in_italy(strtotime ( $info_final_date ),'EEEE dd MMMM y');
                $info_time       = ! empty( $tit_info_perform_value[ 'time' ] ) ? $tit_info_perform_value[ 'time' ] : '';
                $cmd = ! empty( $tit_info_perform_value[ 'cmd' ] ) ? $tit_info_perform_value[ 'cmd' ] : 'prices';
                $pcode = ! empty( $tit_info_perform_value[ 'code' ] ) ? $tit_info_perform_value[ 'code' ] : '9654179';
                $regData = ! empty( $tit_info_perform_value[ 'regData' ] ) ? $tit_info_perform_value[ 'regData' ] : 0;
                array_push($date_array,
                        array(
                        "date" => str_replace ("/", "-", $info_start_date ) . ' ' . $info_time,
    //                    "date" => $info_final_date . ' ' . $info_time,
                        "url" => get_site_url().'/spettacolo-prices/?cmd=prices&id='.APIKEY.'&vcode='.$spt_vcode.'&pcode='.$pcode.'&postId='.$id.'&regData='.$regData.'&selectionMode=0'
                        )
                );
            }
        }
        $response = array(
            'titolo' => $post_title,
            'location' => $spt_location,
            'date' => $date_array
        );
    }
  return $response;
}

add_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_print_fun' , 10);

/*
function woocommerce_order_print_fun($order){
  ob_start ();
    $order_id = $order->ID;
    $orderTransactionCodeArr = get_post_meta( $order_id, 'orderTransactionCodeArr', true );
    $transaction_ids = get_post_meta( $order_id, 'transactionIds', true );
    $totalPrice     = 0;
    $totalQty       = 0;
    $tickets_array = array();
    foreach($transaction_ids as $transaction_ids_key => $transaction_ids_value){
        $ticketName = $transaction_ids_value['ticketName'];
        $zoneId = $transaction_ids_key;
        $zoneName = $transaction_ids_value['zoneName'];
        $seatObject = $transaction_ids_value['seatObject'];
        
        $tickets_array[$ticketName][] = array(
            'zoneId' => $transaction_ids_key,
            'zoneName' => $zoneName,
            'seatObject' => $seatObject,
        );
    }
    $orderTransactionUrl = site_url()."/mio-account/view-order/".$order_id."/";
    ?>
    <h2>Stampa biglietti</h2>
    <p class="order-print">
        <a href="<?php echo $orderTransactionUrl;?>" class="button" target="_blank"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Stampa biglietti</font></font></a>
    </p>
    <div class="spettacolo-cart-wrapper wc-spettacolo-cart-wrapper ticket-order-table">
        <div class="container">
            <div class="spettacolo-cart-inner">
                <div class="spettacolo-tickets">
                    <?php
                    if( ! empty ( $tickets_array ) ) {
                        $counter = 1;
                        foreach($tickets_array as $tickets_array_key => $tickets_array_value){
                            $extra_class = $counter < count($tickets_array) ? 'border-bot' : '' ;
                            $counter++;
                            $ticketName = $tickets_array_key;
                            ?>
                            <div class="ticket-datails-wrap <?php echo $extra_class; ?>">
                                <div class="ticket-title" data-name="<?php echo $ticketName; ?>">
                                    <h2><?php echo $ticketName; ?></h2>
                                </div>
                                <?php
                                if( ! empty ( $tickets_array_value ) ) {
                                    foreach ( $tickets_array_value as $tickets_array_value_k => $tickets_array_value_v ) {
                                        $zoneName   = $tickets_array_value_v[ 'zoneName' ];
                                        $zoneId     = $tickets_array_value_v[ 'zoneId' ];
                                        $seatObject = $tickets_array_value_v[ 'seatObject' ];
                                        ?>
                                        <div class="ticket-zone">
                                            <div class="zone-title" data-zoneId="<?php echo $zoneId; ?>">
                                                <h4><?php echo $zoneName; ?></h4>
                                            </div>
                                            <?php
                                            if(!empty($seatObject)){
                                                if( array_key_first( $seatObject ) == '0' ) {
                                                    $seatObject_new = $seatObject;
                                                } else {
                                                    $seatObject_new = array($seatObject);            
                                                }
                                            }
                                            foreach ( $seatObject_new as $seatObject_key => $seatObject_value ) {
                                                $seat_desc      = $seatObject_value[ 'description' ];
                                                $seat_price     = $seatObject_value[ 'price' ];
                                                $seat_price = !empty($seat_price) ? (float)$seat_price/100  : 0 ;
                                                $seat_reduction = $seatObject_value[ 'reduction' ];
                                                $reduction_id   = $seat_reduction[ '@attributes' ][ 'id' ];
                                                $reduction_name = $seat_reduction[ 'description' ];
                                                $reductionQuantity = 1;
                                                $totalPrice     = $totalPrice + ((int) $reductionPrice * (int) $reductionQuantity);
                                                $totalQty       = $totalQty + (int) $reductionQuantity;
                                                ?>
                                                <div class="zone-reductions">
                                                    <div class="seat-title" data-reductionId="<?php echo $reduction_id; ?>">
                                                        <p><?php echo preg_replace('/' . preg_quote($zoneName, '/') . '/', '', $seat_desc, 1); ?></p>
                                                    </div>
                                                    <div class="tipoticketeprezzo">
                                                    <div class="reduction-title-wrap">
                                                        <div class="reduction-title">
                                                            <p><?php echo $reduction_name; ?></p>
                                                        </div>
                                                        <div class="reduction-qty">
                                                            <p><?php echo " X " . $reductionQuantity; ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="reduction-price">
                                                        <p><?php echo "- " . $seat_price . " &euro;"; ?></p>
                                                    </div>
                                                </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>    
                        <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    $html = ob_get_clean ();
    echo $html;
}

*/
function woocommerce_order_print_fun($order){
    ob_start ();
//    echo "<pre>";
//    print_r($order);
//    echo "</pre>";
    $order_id = $order->ID;    
    $user_id = $order->get_user_id();
    $orderTransactionCodeArr = get_post_meta( $order->get_id(), 'orderTransactionCodeArr', true );
    $transactionIds = get_post_meta( $order->get_id(), 'transactionIds', true );   
    if(!empty($transactionIds)) {        
    ?>
    <h2>Stampa biglietti</h2>
    <table style="text-align:center;">
        <thead>
           <tr>
                <th>
                    Spettacolo
                </th>
                <th>
                    Zone
                </th>
                <th>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php        
        foreach($transactionIds as $transactionIds_key => $transactionIds_value){
            $ticketName = '';
            $zoneName = '';
            $orderTransactionCode = '';
            if(in_array($transactionIds_value['transaction_id'],$orderTransactionCodeArr)){
                $ticketName = $transactionIds_value['ticketName'];
                $zoneName = $transactionIds_value['zoneName'];
                $orderTransactionCode = $transactionIds_value['transaction_id'];
                $orderTransactionUrl = API_HOST."/backend/backend.php?id=".APIKEY."&cmd=printAtHome&trcode=".$orderTransactionCode;
            }
            if(!empty($ticketName) && !empty($orderTransactionCode) && !empty($zoneName)){
            ?>
                    <tr>
                        <td>
                            <?php echo $ticketName;?>
                        </td>
                        <td>
                            <?php echo $zoneName;?>
                        </td>
                        <td>
                            <p class="order-print">
                                <a href="javascript:;" class="button order-print-btn" data-order-id='<?php echo $orderTransactionCode;?>' target="_blank"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Stampa biglietti</font></font></a>
                            </p>
                        </td>
                    </tr>
            <?php
            }
        } 
        ?>
        </tbody>
    </table>
    <?php
    }
    $html = ob_get_clean ();
    echo $html;
}
 
add_action( 'woocommerce_before_cart', 'woocommerce_before_cart_fun' , 10);

function woocommerce_before_cart_fun(){
    ob_start ();
    global $woocommerce;
    $cart = WC()->cart->cart_contents;
    $totalPrice     = 0;
    $totalQty       = 0;
    if(!empty($cart)){
        foreach ( $cart as $cart_item_key => $cart_item ) {
           $selected_seat_price = $cart_item['selected_seat_price'][0];
           $transaction_ids = $cart_item['transaction_ids'][0];
            foreach($transaction_ids as $transaction_ids_key => $transaction_ids_value){
                $ticketName = $transaction_ids_value['ticketName'];
                $zoneName = $transaction_ids_value['zoneName'];
                $zoneId = $transaction_ids_value['zoneId'];
                $timestamp = $transaction_ids_value['timestamp'];
                if($timestamp < time()){
                    foreach($selected_seat_price[$ticketName] as $selected_seat_key => $selected_seat_value){
                        if($selected_seat_value['zoneName'] == $zoneName){
                            unset($selected_seat_price[$ticketName][$selected_seat_key]);
                        }
                    }
                }
            }
            if( ! empty ( $selected_seat_price ) ) {
                foreach ( $selected_seat_price as $meta_key => $meta_value ) {
                    if( ! empty ( $meta_value ) ) {
                        foreach ( $meta_value as $meta_k => $meta_v ) {
                            $reductions = $meta_v[ 'reductions' ];
                            if( ! empty ( $reductions ) ) {
                                foreach ( $reductions as $reductions_key => $reductions_value ) {
                                    $reductionQuantity = $reductions_value[ 'reductionQuantity' ];
                                    $reductionPrice    = $reductions_value[ 'reductionPrice' ];
                                    $totalPrice        = $totalPrice + ((int) $reductionPrice * (int) $reductionQuantity);
                                    $totalQty          = $totalQty + (int) $reductionQuantity;
                                }
                            }
                        }
                    }
                }
            }
            if( ! empty ( $totalPrice ) && $totalPrice != 0 ) {
                $cart_item[ 'data' ]->set_price ( $totalPrice );
                $cart_item['selected_seat_price'][0] = $selected_seat_price;
                WC()->cart->cart_contents[$cart_item_key] = $cart_item;
                WC()->cart->calculate_totals();
            }else{
                WC()->cart->empty_cart();
                $user_id = get_current_user_id();
                update_user_meta($user_id,'addToCartObject',array());
                update_user_meta($user_id,'transactionIds',array());
            }
        }
    } else {
        $user_id = get_current_user_id();
        update_user_meta($user_id,'addToCartObject',array());
        update_user_meta($user_id,'transactionIds',array());
    }
    WC()->cart->set_session();
    ?>
    
    <?php
    $html = ob_get_clean ();
    echo $html;
}
/*
// change hook regarding multiple email issue 13/12/2023.
//add_action( 'woocommerce_email_after_order_table', 'add_order_instruction_email', 10, 2 );
add_action( 'woocommerce_email_recipient_customer_completed_order', 'add_order_instruction_email', 10, 2 );


function add_order_instruction_email( $recipient, $order ) {
  ob_start ();
    $order_id = $order->ID;
    $orderTransactionCodeArr = get_post_meta( $order_id, 'orderTransactionCodeArr', true );
    $transaction_ids = get_post_meta( $order_id, 'transactionIds', true );
    $totalPrice     = 0;
    $totalQty       = 0;
    $tickets_array = array();
    foreach($transaction_ids as $transaction_ids_key => $transaction_ids_value){
        $ticketName = $transaction_ids_value['ticketName'];
        $zoneId = $transaction_ids_key;
        $zoneName = $transaction_ids_value['zoneName'];
        $seatObject = $transaction_ids_value['seatObject'];
        
        $tickets_array[$ticketName][] = array(
            'zoneId' => $transaction_ids_key,
            'zoneName' => $zoneName,
            'seatObject' => $seatObject,
        );
    }
    $orderTransactionUrl = site_url()."/mio-account/view-order/".$order_id."/";
    ?>
    <h2>Stampa biglietti</h2>
    <p class="order-print">
        <a href="<?php echo $orderTransactionUrl;?>" class="button" target="_blank"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Stampa biglietti</font></font></a>
    </p>
    <div class="spettacolo-cart-wrapper wc-spettacolo-cart-wrapper ticket-order-table">
        <div class="container">
            <div class="spettacolo-cart-inner">
                <div class="spettacolo-tickets">
                    <?php
                    if( ! empty ( $tickets_array ) ) {
                        $counter = 1;
                        foreach($tickets_array as $tickets_array_key => $tickets_array_value){
                            $extra_class = $counter < count($tickets_array) ? 'border-bot' : '' ;
                            $counter++;
                            $ticketName = $tickets_array_key;
                            ?>
                            <div class="ticket-datails-wrap <?php echo $extra_class; ?>">
                                <div class="ticket-title" data-name="<?php echo $ticketName; ?>">
                                    <h2><?php echo $ticketName; ?></h2>
                                </div>
                                <?php
                                if( ! empty ( $tickets_array_value ) ) {
                                    foreach ( $tickets_array_value as $tickets_array_value_k => $tickets_array_value_v ) {
                                        $zoneName   = $tickets_array_value_v[ 'zoneName' ];
                                        $zoneId     = $tickets_array_value_v[ 'zoneId' ];
                                        $seatObject = $tickets_array_value_v[ 'seatObject' ];
                                        ?>
                                        <div class="ticket-zone">
                                            <div class="zone-title" data-zoneId="<?php echo $zoneId; ?>">
                                                <h4><?php echo $zoneName; ?></h4>
                                            </div>
                                            <?php
                                            if(!empty($seatObject)){
                                                if( array_key_first( $seatObject ) == '0' ) {
                                                    $seatObject_new = $seatObject;
                                                } else {
                                                    $seatObject_new = array($seatObject);            
                                                }
                                            }
                                            foreach ( $seatObject_new as $seatObject_key => $seatObject_value ) {
                                                $seat_desc      = $seatObject_value[ 'description' ];
                                                $seat_price     = $seatObject_value[ 'price' ];
                                                $seat_price = !empty($seat_price) ? (float)$seat_price/100  : 0 ;
                                                $seat_reduction = $seatObject_value[ 'reduction' ];
                                                $reduction_id   = $seat_reduction[ '@attributes' ][ 'id' ];
                                                $reduction_name = $seat_reduction[ 'description' ];
                                                $reductionQuantity = 1;
                                                $totalPrice     = $totalPrice + ((int) $reductionPrice * (int) $reductionQuantity);
                                                $totalQty       = $totalQty + (int) $reductionQuantity;
                                                ?>
                                                <div class="zone-reductions">
                                                    <div class="seat-title" data-reductionId="<?php echo $reduction_id; ?>">
                                                        <p><?php echo preg_replace('/' . preg_quote($zoneName, '/') . '/', '', $seat_desc, 1); ?></p>
                                                    </div>
                                                    <div class="tipoticketeprezzo">
                                                    <div class="reduction-title-wrap">
                                                        <div class="reduction-title">
                                                            <p><?php echo $reduction_name; ?></p>
                                                        </div>
                                                        <div class="reduction-qty">
                                                            <p><?php echo " X " . $reductionQuantity; ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="reduction-price">
                                                        <p><?php echo "- " . $seat_price . " &euro;"; ?></p>
                                                    </div>
                                                </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>    
                        <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    $html = ob_get_clean ();
    echo $html;
}



//function add_order_instruction_email( $order, $sent_to_admin ) {
function add_order_instruction_email( $recipient, $order ) {
  ob_start ();
    $order_id = $order->ID;
    $orderTransactionCodeArr = get_post_meta( $order_id, 'orderTransactionCodeArr', true );
    $transactionIds = get_post_meta( $order_id, 'transactionIds', true );
    ?>
    <h2>Stampa biglietti</h2>
    <table style="text-align:center;">
        <thead>
           <tr>
                <th>
                    Spettacolo
                </th>
                <th>
                    Zone
                </th>
                <th>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach($transactionIds as $transactionIds_key => $transactionIds_value){
            $ticketName = '';
            $zoneName = '';
            $orderTransactionCode = '';
            if(in_array($transactionIds_value['transaction_id'],$orderTransactionCodeArr)){
                $ticketName = $transactionIds_value['ticketName'];
                $zoneName = $transactionIds_value['zoneName'];
                $orderTransactionCode = $transactionIds_value['transaction_id'];
//                $orderTransactionUrl = API_HOST."/backend/backend.php?id=".APIKEY."&cmd=printAtHome&trcode=".$orderTransactionCode;
                $orderTransactionUrl = site_url()."/mio-account/view-order/".$order_id."/";
            }
            if(!empty($ticketName) && !empty($orderTransactionCode) && !empty($zoneName)){
            ?>
                <tr>
                    <td>
                        <?php echo $ticketName;?>
                    </td>
                    <td>
                        <?php echo $zoneName;?>
                    </td>
                    <td>
                        <p class="order-print">
                            <a href="<?php echo $orderTransactionUrl;?>" class="button" target="_blank"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Stampa biglietti</font></font></a>
                        </p>
                    </td>
                </tr>
            <?php
            }
        }
        ?>
        </tbody>
    </table>
    <?php
    $html = ob_get_clean ();
    echo $html;
}
*/
add_action( 'woocommerce_before_cart_table', 'woocommerce_before_cart_table_fun', 10);
 
function woocommerce_before_cart_table_fun() {
    ob_start();
    global $woocommerce;
    $cart = WC()->cart->cart_contents;
    if(!empty($cart)){
        foreach ( $cart as $cart_item_key => $cart_item ) {
           $selected_seat_price = $cart_item['selected_seat_price'][0];
           $transaction_ids = $cart_item['transaction_ids'][0];
            foreach($transaction_ids as $transaction_ids_key => $transaction_ids_value){
                $timestamp = $transaction_ids_value['timestamp'];
                if($timestamp > time()){
                    $finaltimestamp = $timestamp;
                }
            }
        }
    }
    if(!empty($finaltimestamp)){   
    ?>
        <div class="timer">
            <span id='timer_count' data-time='<?php echo $finaltimestamp; ?>' class="timetext"></span>
        </div>
    <?php
    }
    $html = ob_get_clean ();
    echo $html;
}

add_filter( 'woocommerce_cart_item_removed_title', 'removed_from_cart_title', 12, 2);
function removed_from_cart_title( $message, $cart_item ) {
    $items_title_str = "";
    if(!empty($cart_item['selected_seat_price']) ){
        foreach($cart_item['selected_seat_price'][0] as $cart_item_key => $cart_item_value){
            $items_title_str .= $cart_item_key.", ";
        }
        $items_title_str = rtrim($items_title_str, ", ");
    }

    if( !empty($items_title_str)){
        $message = $items_title_str;
    }
    return $message;
}

//function custom_mini_cart_product_count($count, $cart) {
//    // Set your custom count here
//    $custom_count = 5; // Replace with your desired count
//    
//    return $custom_count;
//}
//
//add_filter('woocommerce_mini_cart_product_count', 'custom_mini_cart_product_count', 10, 2);

//function filter_woocommerce_get_endpoint_url( $url, $endpoint, $value, $permalink ) {
//    // Specific endpoint
//    if ( $endpoint === 'view-order' ) {
//        // New URL
////        if($_GET['print'] == '1'){
////            echo "<pre>";
////            print_r($url);
////            echo "</pre>";
////            echo "<pre>";
////            print_r($value);
////            echo "</pre>";
////            echo "<pre>";
////            print_r($permalink);
////            echo "</pre>";
////            echo "<pre>";
////            print_r($endpoint);
////            echo "</pre>";
////            
////        }
//    }
//
//    return $url;
//}
//add_filter( 'woocommerce_get_endpoint_url', 'filter_woocommerce_get_endpoint_url', 10, 4 );

// Change View Order URL in WooCommerce
function modify_view_order_url($view_order_url) {
//    if($_GET['print'] == '1'){
//        echo "<pre>";
//        print_r($view_order_url);
//        echo "</pre>";
//    }

    return $view_order_url;
}
//add_filter('woocommerce_get_view_order_url', 'modify_view_order_url', 10, 1);

// Modify View Order button HTML
//function modify_view_order_button_html($button_html, $order) {
//    $order_id = $order->get_id();
//
//    // Customize the button HTML as needed
//    $button_html = sprintf(
//        '<a class="button test" href="%s">%s</a>',
//        esc_url($order->get_view_order_url()),
//        __('View Order', 'stc-tickets') // Replace with your desired text
//    );
//
//    return $button_html;
//}
//add_filter('woocommerce_order_button_html', 'modify_view_order_button_html', 10, 2);
add_filter('woocommerce_my_account_my_orders_actions', 'modify_view_order_button_url', 999999999999999, 2);
function modify_view_order_button_url($actions, $order) {
//    if($_GET['print'] == 1) {
//        echo "<pre>";
//        print_r($actions);
//        echo "</pre>";
//        die();
//    }
    $actions['view']['url'] = $order->get_view_order_url();
    return $actions;
}
