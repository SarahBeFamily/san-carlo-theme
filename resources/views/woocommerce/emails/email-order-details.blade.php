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
$seatObject = array();
$abbonamento = false;
$pdf_abbonamento = get_field('istruzioni_abbonamento', 'option');
// echo "<pre>";
// print_r($transaction_ids);
// echo "</pre>";

if (is_array($transaction_ids) && !empty($transaction_ids)) {
    foreach($transaction_ids as $transaction_ids_key => $transaction_ids_value){
        $zoneId = $transaction_ids_key;
        if( ! isset( $transaction_ids_value[ 'subscription_seat' ] ) ) {
            $ticketName = $transaction_ids_value['ticketName'];
            $zoneName = $transaction_ids_value['zoneName'];
            $seatObject = $transaction_ids_value['seatObject'];
			$showDate = $transaction_ids_value['showDate'];

			if ( $transaction_ids_value['regData'] == 0 ) {
				$abbonamento = true;
			}

            if(!empty($ticketName)){
                $tickets_name_array[] = $ticketName;
            }
            $tickets_array[] = array(
				'ticketName' => $ticketName,
				'zoneId' => $transaction_ids_key,
				'zoneName' => $zoneName,
				'seatObject' => $seatObject,
				'showDate' => $showDate,
            );
        } else {
			// $abbonamento = true;
            foreach ( $transaction_ids_value[ 'subscription_seat' ] as $subscription_seat_key => $subscription_seat_value ) {
                $ticketName     = $subscription_seat_value[ 'ticketName' ];
                $zoneName       = $subscription_seat_value[ 'zoneName' ];
                $seatObject     = $subscription_seat_value[ 'seatObject' ];
                $subscription   = $subscription_seat_value[ 'subscription' ];
                $transaction_id = $subscription_seat_value[ 'transaction_id' ];
				$showDate       = $subscription_seat_value[ 'showDate' ];

                if(!empty($ticketName)){
                    $tickets_name_array[] = $ticketName;
                }
                $tickets_array[] = array(
					'ticketName' => $ticketName,
					'zoneId' => $transaction_ids_key,
					'zoneName' => $zoneName,
					'seatObject' => $seatObject,
					'showDate' => $showDate,
                );
            }
        }     
    }
}
$tickets_name_list = implode(", ",array_unique($tickets_name_array));

// echo "<pre>";
// print_r($tickets_array);
// echo "</pre>";
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
			{{-- <tr>
				<td style="padding-bottom: 0;">
					<img src="@asset('images/calendar.png')" alt="Data evento" width="24" height="24" class="icona"> 
					<!--[if (gte mso 9)|(IE)]><span style="color: white">s</span><![endif]-->
					<span>{{ $order->get_date_created()->format( 'd-m-Y' ) }}</span>
				</td>
			</tr> --}}

            
			@foreach($tickets_array as $ticket)

			<hr>
				<tr>
					<td style="padding-bottom: 0;">
						<img src="@asset('images/calendar.png')" alt="Data evento" width="24" height="24" class="icona"> 
						<!--[if (gte mso 9)|(IE)]><span style="color: white">s</span><![endif]-->
						<span>{{ $ticket['ticketName'] }} {{ $ticket['showDate'] }}</span>
					</td>
				</tr>

				{{-- @foreach ( $ticket as $ticketArray_key => $ticketArray_value ) --}}
					@set($zoneName, $ticket[ 'zoneName' ])
					@set($ticketSeatObject, $ticket[ 'seatObject' ])
					@set($seatObject_new, [])

					@if(is_array($ticketSeatObject) && !empty($ticketSeatObject))
						@if( array_key_first( $ticketSeatObject ) == '0' )
							@set($seatObject_new, $ticketSeatObject)
						@else
							@set($seatObject_new, array($ticketSeatObject))            
						@endif
					@endif
					
					@if(!empty($seatObject_new))
					@foreach ( $seatObject_new as $seatObject_key => $seatObject_value )
						@set($seat_desc, 	  $seatObject_value[ 'description' ])
						@set($reduction_name, $seatObject_value[ 'reduction' ][ 'description' ])
						@set($ticket_string , trim($seat_desc).' ('.trim($reduction_name).') ')
						
						<tr>
							<td style="padding-bottom: 0;">
								<img src="@asset('images/ticket.png')" alt="Biglietto" width="24" height="24" class="icona"> 
								<!--[if (gte mso 9)|(IE)]><span style="color: white">s</span><![endif]-->
								<span>{{ $ticket_string }}</span>
							</td>
						</tr>
						
					@endforeach
					@endif

				{{-- @endforeach --}}
			@endforeach
                
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
						{{-- @if (is_array($tiket_name_array) && !empty($tiket_name_array))
							@foreach ($tiket_name_array as $tiket_name_array_key => $tiket_name_array_value)
								<tr>
									<td style="padding-left: 0; padding-bottom: 0;">
										<img src="@asset('images/bag.png')" alt="Show" width="24" height="24" class="icona"> 
										<!--[if (gte mso 9)|(IE)]><span style="color: white">s</span><![endif]-->
										<span>{{ $tiket_name_array_value }}</span>
									</td>
								</tr>
							@endforeach
						@else --}}
							<tr>
								<td style="padding-left: 0; padding-bottom: 0;">
									<img src="@asset('images/bag.png')" alt="Show" width="24" height="24" class="icona"> 
									<!--[if (gte mso 9)|(IE)]><span style="color: white">s</span><![endif]-->
									<span>{{ $tickets_name_list }}</span>
								</td>
							</tr>
						{{-- @endif --}}
						
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
						@if($abbonamento)
						<tr>
							<td style="padding-left: 0; padding-top: 0;">
								<a href="{{ $pdf_abbonamento }}" style="font-size: 12px;  font-weight: 500;  letter-spacing: 0;  padding: 15px 0;  text-decoration: none;  text-transform: uppercase;  background: #df3b3c;  color: white;  width: 94%;   display: block !important;  text-align: center;">{{ _e('Download instructions', 'san-carlo-theme') }}</a>
							</td>
						</tr>
						@endif
						<tr>
							<td style="padding-left: 0;">
								@if(!$abbonamento)
								<a href="{{ site_url() }}/mio-account/view-order/{{ $order_id }}">
									@if (ICL_LANGUAGE_CODE == 'it')
										<img src="@asset('images/stampa.png')" alt="Stampa biglietti" style="max-width: 200px">
									@else
										<img src="@asset('images/print.png')" alt="Print Tickets" style="max-width: 200px">
									@endif
								</a>
								@else
								<a href="{{ site_url() }}/mio-account/view-order/{{ $order_id }}" style="font-size: 12px;  font-weight: 500;  letter-spacing: 0;  padding: 15px 0;  text-decoration: none;  text-transform: uppercase;  background: #df3b3c;  color: white;  width: 94%;  display: block !important;  text-align: center;">{{ _e('More details', 'san-carlo-theme') }}</a>
								@endif
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table>

</div>

@action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email )
