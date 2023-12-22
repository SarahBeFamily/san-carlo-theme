@php
/**
 * Customer completed order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-completed-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */

 @endphp

@action( 'woocommerce_email_header', $email_heading, $email )

@set($utente, $order->get_billing_first_name() ? $order->get_billing_first_name() : $order->get_user()->user_login)

<div class="intro sezione">
	<h1>
		{{-- translators: %s è il nome utente --}}
		{!! sprintf(__( 'Thank you for shopping at teatrosancarlo.it, %s.', 'san-carlo-theme' ), esc_html( $utente ) ) !!}
	</h1>

	<p>
		{{-- translators: %s è il numero di ordine --}}
		{!! sprintf(__( 'Your order #%s is confirmed! Here are the details of your purchase.', 'san-carlo-theme' ), $order->get_order_number() ) !!}
	</p>
</div>


{{-- /*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */ --}}
@action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email )

{{-- /*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */ --}}
@action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email )

{{-- /*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */ --}}
{{-- @action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email ) --}}

{{-- /**
 * Show user-defined additional content - this is set in each email's settings.
 */ --}}
{{-- @if ( $additional_content )
	{!! wp_kses_post( wpautop( wptexturize( $additional_content ) ) ) !!}
@endif --}}

{{-- /*
 * @hooked WC_Emails::email_footer() Output the email footer
 */ --}}
@action( 'woocommerce_email_footer', $email )
