@php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;
@endphp

@action( 'woocommerce_before_account_orders', $has_orders )

@if ( $has_orders )

	<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		<thead>
			<tr>
				@foreach ( wc_get_account_orders_columns() as $column_id => $column_name )
					<th class="woocommerce-orders-table__header woocommerce-orders-table__header-@php echo esc_attr( $column_id ) @endphp"><span class="nobr">@php echo esc_html( $column_name ) @endphp</span></th>
				@endforeach
			</tr>
		</thead>

		<tbody>
			
			@foreach ( $customer_orders->orders as $customer_order )
				@set($order, wc_get_order( $customer_order ))
				@set($item_count, $order->get_item_count() - $order->get_item_count_refunded())
				
				<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-@php echo esc_attr( $order->get_status() ) @endphp order">
					@foreach ( wc_get_account_orders_columns() as $column_id => $column_name )
						<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-@php echo esc_attr( $column_id ) @endphp" data-title="@php echo esc_attr( $column_name ) @endphp">
							@if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) )
								@action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order )

							@elseif ( 'order-number' === $column_id )
								<a href="@php echo esc_url( $order->get_view_order_url() ) @endphp" data-url="@php echo $order->get_view_order_url() @endphp">
									@php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ) @endphp
								</a>

							@elseif ( 'order-date' === $column_id )
								<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

							@elseif ( 'order-status' === $column_id )
								@php echo esc_html( wc_get_order_status_name( $order->get_status() ) ) @endphp

							@elseif ( 'order-total' === $column_id )
								
								{{ /* translators: 1: formatted order total 2: total order items */ }}
								@php echo wp_kses_post( sprintf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) ) @endphp
								

							@elseif ( 'order-actions' === $column_id )
								
								@set($actions, wc_get_account_orders_actions( $order ))
								{{-- @set($endpoint_url, get_permalink( get_option('woocommerce_myaccount_page_id') )) --}}

								@if ( ! empty( $actions ) )
									@foreach ( $actions as $key => $action ) 
										
										@set($action_url, esc_url($action['url']))

										@if ($key == 'view')
											@set($action_url, $order->get_view_order_url())
											{{-- $endpoint_url.'view-order/'.$order->get_id(); --}}
											<a href=" @php echo $order->get_view_order_url() @endphp " data-url="@php echo $order->get_view_order_url() @endphp " class="woocommerce-button  @php echo esc_attr( $wp_button_class )  @endphp button   @php echo sanitize_html_class( $key ) @endphp  "> @php echo esc_html( $action['name'] ) @endphp </a>

										@else
											<a href=" @php echo esc_url($action['url']) @endphp "  class="woocommerce-button  @php echo esc_attr( $wp_button_class )  @endphp button   @php echo sanitize_html_class( $key ) @endphp  "> @php echo esc_html( $action['name'] ) @endphp </a>
										@endif
										
									@endforeach
								@endif
								
							@endif
						</td>
					@endforeach
				</tr>
			@endforeach
		</tbody>
	</table>

	{{-- @action( 'woocommerce_before_account_orders_pagination' )

	@if ( 1 < $customer_orders->max_num_pages )
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			@if ( 1 !== $current_page )
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="@php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ) @endphp">@php esc_html_e( 'Previous', 'woocommerce' ) @endphp</a>
			@endif

			@if ( intval( $customer_orders->max_num_pages ) !== $current_page )
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="@php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ) @endphp">@php esc_html_e( 'Next', 'woocommerce' ) @endphp</a>
			@endif
		</div>
	@endif

@else
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="@php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) @endphp">@php esc_html_e( 'Browse products', 'woocommerce' ) @endphp</a>
		@php esc_html_e( 'No order has been made yet.', 'woocommerce' ) @endphp
	</div> --}}
@endif

{{-- @action( 'woocommerce_after_account_orders', $has_orders ) --}}
