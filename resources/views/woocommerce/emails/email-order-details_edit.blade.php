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

----

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

@php
$order_id = $order->get_id();
$transaction_ids = $order->get_meta('transactionIds',true,'view'); // Get order item meta data (array)
$totalPrice     = 0;
$totalQty       = 0;
$tickets_array = array();
$tickets_name_array = array();
if (is_array($transaction_ids) && !empty($transaction_ids)) {
    foreach($transaction_ids as $transaction_ids_key => $transaction_ids_value){
        $zoneId = $transaction_ids_key;
        if( ! isset( $transaction_ids_value[ 'subscription_seat' ] ) ) {
            $ticketName = $transaction_ids_value['ticketName'];
            $zoneName = $transaction_ids_value['zoneName'];
            $seatObject = $transaction_ids_value['seatObject'];

            if(!empty($ticketName)){
                $tickets_name_array[] = $ticketName;
            }
            $tickets_array[$ticketName][] = array(
                    'zoneId' => $transaction_ids_key,
                    'zoneName' => $zoneName,
                    'seatObject' => $seatObject,
            );
        }else{
            foreach ( $transaction_ids_value[ 'subscription_seat' ] as $subscription_seat_key => $subscription_seat_value ) {
                $ticketName     = $subscription_seat_value[ 'ticketName' ];
                $zoneName       = $subscription_seat_value[ 'zoneName' ];
                $seatObject     = $subscription_seat_value[ 'seatObject' ];
                $subscription   = $subscription_seat_value[ 'subscription' ];
                $transaction_id = $subscription_seat_value[ 'transaction_id' ];

                if(!empty($ticketName)){
                    $tickets_name_array[] = $ticketName;
                }
                $tickets_array[$ticketName][] = array(
                        'zoneId' => $transaction_ids_key,
                        'zoneName' => $zoneName,
                        'seatObject' => $seatObject,
                );
            }
        }     
    }
}
$tickets_name_list = implode(",",$tickets_name_array);

@endphp
<div class="sezione">

	<table border="0" cellpadding="10" cellspacing="0" width="100%" class="dettagli-biglietti">
		<thead>
			<tr>
				<th>
					<h2>{{ _e('Your purchases', 'san-carlo-theme') }}</h2>
				</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td style="padding-bottom: 0;">
					<img src="@asset('images/pin.png')" alt="Location" width="24" height="24" class="icona"> 
					<!--[if (gte mso 9)|(IE)]><span style="color: white">s</span><![endif]-->
					<span>Teatro San Carlo</span>
				</td>
			</tr>
			<tr>
				<td style="padding-bottom: 0;">
					<img src="@asset('images/calendar.png')" alt="Data evento" width="24" height="24" class="icona"> 
					<!--[if (gte mso 9)|(IE)]><span style="color: white">s</span><![endif]-->
					<span>{{ $order->get_date_created()->format( 'd-m-Y' ) }}</span>
				</td>
			</tr>

            <?php
                foreach($tickets_array as $tickets_array_key => $tickets_array_value){
                    foreach ( $tickets_array_value as $tickets_array_value_k => $tickets_array_value_v ) {
                        $zoneName   = $tickets_array_value_v[ 'zoneName' ];
                        if(!empty($seatObject)){
                            if( array_key_first( $seatObject ) == '0' ) {
                                $seatObject_new = $seatObject;
                            } else {
                                $seatObject_new = array($seatObject);            
                            }
                        }
                        foreach ( $seatObject_new as $seatObject_key => $seatObject_value ) {
                            $seat_desc      = $seatObject_value[ 'description' ];
                            $seat_reduction = $seatObject_value[ 'reduction' ];
                            $reduction_name = $seat_reduction[ 'description' ];
                            $ticket_string = trim($zoneName).' | '.trim($seat_desc).' ('.trim($reduction_name).') ';
                            ?>
                                <tr>
									<td style="padding-bottom: 0;">
										<img src="@asset('images/ticket.png')" alt="Biglietto" width="24" height="24" class="icona"> 
										<!--[if (gte mso 9)|(IE)]><span style="color: white">s</span><![endif]-->
										<span>{{ $ticket_string }}</span>
									</td>
								</tr>
                            <?php
                        }
                    }
                }
            ?>
                
		</tbody>
	</table>
	<!--[if (gte mso 9)|(IE)]><hr><![endif]-->
</div>

<div class="sezione" style="border: 0!important;">

	<table border="0" cellpadding="10" cellspacing="0" width="100%" class="dettagli-ordine">
		<thead>
			<tr>
				<th>
					<!--[if (gte mso 9)|(IE)]><br><![endif]-->
					<h2>{{ _e('Purchase detail', 'san-carlo-theme') }}</h2>
				</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td style="width: 69%;">
					<table border="0" cellpadding="10" cellspacing="0">
						@set($tiket_name_array, explode(",",$tickets_name_list))
						@if (is_array($tiket_name_array) && !empty($tiket_name_array))
							@foreach ($tiket_name_array as $tiket_name_array_key => $tiket_name_array_value)
                                                            @if (!empty($tiket_name_array_value))
								<tr>
									<td style="padding-left: 0; padding-bottom: 0;">
										<img src="@asset('images/bag.png')" alt="Show" width="24" height="24" class="icona"> 
										<!--[if (gte mso 9)|(IE)]><span style="color: white">s</span><![endif]-->
										<span>{{ $tiket_name_array_value }}</span>
									</td>
								</tr>
                                                            @endif
							@endforeach
						@else
							<tr>
								<td style="padding-left: 0; padding-bottom: 0;">
									<img src="@asset('images/bag.png')" alt="Show" width="24" height="24" class="icona"> 
									<!--[if (gte mso 9)|(IE)]><span style="color: white">s</span><![endif]-->
									<span>{{ $tickets_name_list }}</span>
								</td>
							</tr>
						@endif
						
						<tr>
							<td style="padding-left: 0; padding-bottom: 0;">
								<img src="@asset('images/check.png')" alt="Conferma" width="24" height="24" class="icona">
								<!--[if (gte mso 9)|(IE)]><span style="color: white">s</span><![endif]-->
								<span>{{ _e('Confirm', 'san-carlo-theme') }}</span>
							</td>
						</tr>
						<tr>
							<td style="padding-left: 0; padding-bottom: 0;">
								<img src="@asset('images/user.png')" alt="Cliente" width="24" height="24" class="icona"> 
								<!--[if (gte mso 9)|(IE)]><span style="color: white">s</span><![endif]-->
								<span>{{ $utente }}</span>
							</td>
						</tr>
					</table>
				</td>

				<td style="width: 30%;">
					<table border="0" cellpadding="10" cellspacing="0">
						<tr>
							<td style="padding-left: 0; padding-top: 0;">
								<img src="@asset('images/cart.png')" alt="Carrello"  width="24" height="24" class="icona"> 
								<!--[if (gte mso 9)|(IE)]><span style="color: white">s</span><![endif]-->
								{{-- translators: %1$s: è il totale dell'ordine. %2$s: è la valuta --}}
								<span>{!! sprintf(__( 'Cart total: %1$s%2$s', 'san-carlo-theme' ), $order->get_total(), get_woocommerce_currency_symbol($order->get_currency()) ) !!}</span>
							</td>
						</tr>
						<tr>
							<td style="padding-left: 0;">
								<a href="{{ site_url() }}/mio-account/view-order/{{ $order_id }}">
									@if (ICL_LANGUAGE_CODE == 'it')
										<img src="@asset('images/stampa.png')" alt="Stampa biglietti" style="max-width: 200px">
									@else
										<img src="@asset('images/print.png')" alt="Print Tickets" style="max-width: 200px">
									@endif
								</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table>

</div>

@action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email )
