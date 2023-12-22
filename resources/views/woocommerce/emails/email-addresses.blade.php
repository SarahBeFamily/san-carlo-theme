@php
/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-addresses.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 5.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$text_align = is_rtl() ? 'right' : 'left';
$address    = $order->get_formatted_billing_address();
$shipping   = $order->get_formatted_shipping_address();

@endphp
<div class="sezione" style="border-top: 1px solid">

<table border="0" cellpadding="10" cellspacing="0" width="100%" class="dettagli-biglietti">
	<thead>
		<tr>
			<th>
				<h2>{{ esc_html_e( 'Billing address', 'woocommerce' ); }}</h2>
			</th>
		</tr>
	</thead>

	<tbody>

		<tr>
			<td>
				{!! wp_kses_post( $address ? $address : esc_html__( 'N/A', 'woocommerce' ) ) !!}
				<br>
				@if ( $order->get_billing_phone() )
					{!! wc_make_phone_clickable( $order->get_billing_phone() ) !!}
					<br>
				@endif

				@if ( $order->get_billing_email() )
					{!! esc_html( $order->get_billing_email() ) !!}
				@endif
			</td>
		</tr>
			
	</tbody>
</table>

</div>


