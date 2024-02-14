@php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
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

defined( 'ABSPATH' ) || exit;

$text_align = is_rtl() ? 'right' : 'left';
@endphp

@action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email )

@set($utente_fallback, $order->get_user()->display_name ? $order->get_user()->display_name : 'Mario Rossi')
@set($utente, $order->get_billing_first_name() && $order->get_billing_last_name() ? $order->get_billing_first_name().' '.$order->get_billing_last_name() : $utente_fallback)

{{-- Inserire qui la funzione per l'array dei biglietti --}}
@set($acquisto, array(
	'nome_spettacolo' => 'Turandot',
	'biglietti' => array('Palco | Palco Ord. | nr. 9 Posto 3 (INTERO) ', 'Palco | Palco Ord. | nr. 9 Posto 4 (RIDOTTO)')
	))

<div class="sezione">
	<h2>{{ _e('Your purchases', 'san-carlo-theme') }}</h2>

	<div class="dettagli-biglietti">
		<span><img src="@asset('images/pin.png')" alt="Location"> Teatro San Carlo</span>
		<span><img src="@asset('images/calendar.png')" alt="Data evento"> {{ $order->get_date_created()->format( 'd-m-Y' ) }}</span>

		@foreach ($acquisto['biglietti'] as $biglietto)
		<span><img src="@asset('images/ticket.png')" alt="Biglietto"> {{ $biglietto }}</span>
		@endforeach
	</div>
</div>

<div class="sezione" style="border: 0!important;">
	<h2>{{ _e('Purchase detail', 'san-carlo-theme') }}</h2>

	<div class="dettagli-ordine">
		<div id="uno">
			<span><img src="@asset('images/bag.png')" alt="Spettacolo"> {{ $acquisto['nome_spettacolo'] }}</span>
			<span><img src="@asset('images/check.png')" alt="Conferma"> {{ _e('Confirm', 'san-carlo-theme') }}</span>
			<span><img src="@asset('images/user.png')" alt="Cliente"> {{ $utente }}</span>
		</div>
		
		<div id="due" style="grid-column: 2">
			{{-- translators: %1$s: è il totale dell'ordine. %2$s: è la valuta --}}
			<span><img src="@asset('images/cart.png')" alt="Carrello"> {!! sprintf(__( 'Cart total: %1$s%2$s', 'san-carlo-theme' ), $order->get_total(), get_woocommerce_currency_symbol($order->get_currency()) ) !!}</span>
			
			{{-- Link per la stampa dei biglietti, probabile inserimento array? --}}
			<a class="bottone" href="#">{{ _e('Print Tickets', 'san-carlo-theme') }}</a>
		</div>
	</div>
</div>

	{{-- if ( $sent_to_admin ) {
		$before = '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">';
		$after  = '</a>';
	} else {
		$before = '';
		$after  = '';
	}
	/* translators: %s: Order ID. */
	echo wp_kses_post( $before . sprintf( __( '[Order #%s]', 'woocommerce' ) . $after . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ) );
	 

<div style="margin-bottom: 40px;">
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
		<thead>
			<tr>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			echo wc_get_email_order_items( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$order,
				array(
					'show_sku'      => $sent_to_admin,
					'show_image'    => false,
					'image_size'    => array( 32, 32 ),
					'plain_text'    => $plain_text,
					'sent_to_admin' => $sent_to_admin,
				)
			);
			?>
		</tbody>
		<tfoot>
			<?php
			$item_totals = $order->get_order_item_totals();

			if ( $item_totals ) {
				$i = 0;
				foreach ( $item_totals as $total ) {
					$i++;
					?>
					<tr>
						<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post( $total['label'] ); ?></th>
						<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post( $total['value'] ); ?></td>
					</tr>
					<?php
				}
			}
			if ( $order->get_customer_note() ) {
				?>
				<tr>
					<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
				</tr>
				<?php
			}
			?>
		</tfoot>
	</table>
</div>
--}}

@action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email )
