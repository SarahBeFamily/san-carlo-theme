<?php

namespace App;

/*
 *
 * Add woocommerce hooks here
 *
*/

// add wrap before cart
add_action('woocommerce_before_cart', function() {
    echo '<div class="woo-wrap">';
});

// close wrap after cart
add_action('woocommerce_after_cart', function() {
    echo '</div>';
});

// add wrap before checkout form
add_action('woocommerce_before_checkout_form', function() {
    echo '<div class="woo-wrap">';
});

// close wrap after cart
add_action('woocommerce_after_checkout_form', function() {
    echo '</div>';
});

// remove order notes
add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );

