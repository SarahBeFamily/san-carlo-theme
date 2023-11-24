@php
/**
 * The template for displaying cart content in the my-account.php template
 *
 * This is an Override done by VV for Raccoon theme
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;
@endphp

<div class="woocommerce-account">

	<div class="woo-wrap">
		@php 
		/**
		 * My Account navigation.
		 *
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_account_navigation' )
		@endphp


		<div class="woocommerce-MyAccount-content">
			@php
				/**
				 * My Account content.
				 *
				 * @since 2.6.0
				 */
				do_action( 'woocommerce_account_content' );
			@endphp
		</div>
	</div>
</div>

