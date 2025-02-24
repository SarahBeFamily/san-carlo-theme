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

do_action( 'woocommerce_before_account_orders', $has_orders );

if ( $has_orders ) : @endphp

	<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		<thead>
			<tr>
				<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : 
				 	if ($column_id == 'order-date') {
						$column_name = is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) && ICL_LANGUAGE_CODE == 'it' ? 'Data Ordine' : 'Order Date';
					}
				?>
					<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<?php
			// test
			if( isset($_GET['print']) && $_GET['print'] == '1' ) {
				echo '<pre>';
					print_r(get_user_meta( get_current_user_id(  ), 'finalConfirmedOrder', true ));
				echo '</pre>';
			}

			foreach ( $customer_orders->orders as $customer_order ) {
				$order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				$item_count = $order->get_item_count() - $order->get_item_count_refunded();
				$order_id 	= $order->get_id();

				// test
				if( isset($_GET['print']) && $_GET['print'] == '2' ) {
					echo '<pre>';
						print_r(get_post_meta( $order_id, 'preOrderObject', true ));
					echo '</pre>';
				}
				?>
				<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
					<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
						<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

							<?php elseif ( 'order-number' === $column_id ) : ?>
								<!--<a href="<?php ///echo esc_url( $order->get_view_order_url() ); ?>">-->
									<?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
								

							<?php elseif ( 'order-date' === $column_id ) : ?>
								<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

							<?php elseif ( 'order-status' === $column_id ) : ?>
								<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

							<?php elseif ( 'order-total' === $column_id ) : ?>
								<?php
								/* translators: 1: formatted order total 2: total order items */
								echo wp_kses_post( sprintf( _n( '%1$s for %2$s show', '%1$s for %2$s shows', $item_count, 'san-carlo-theme' ), $order->get_formatted_order_total(), $item_count ) );
								?>

							<?php elseif ( 'order-actions' === $column_id ) : ?>
								<?php
								$actions = wc_get_account_orders_actions( $order );
								$endpoint_url = get_permalink( get_option('woocommerce_myaccount_page_id') );

								if ( ! empty( $actions ) ) {
									foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
										
										$action_url = esc_url($action['url']);

										if ($key == 'view') {
											if (!$order_id) continue;
											$action_url = $endpoint_url.'view-order/'.$order->get_id();
										}

										// hide action pay
										if ($key == 'pay' || $key == 'cancel') {
											$key = 'hidden';
										}
										
										echo '<a href="' . $action_url . '" data-url="'.$action_url.'" class="woocommerce-button' . esc_attr( $wp_button_class ) . ' button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
									}
								}
								?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>

	@action( 'woocommerce_before_account_orders_pagination' )

	@if ( 1 < $customer_orders->max_num_pages )
		@php
		$endpoint_url = get_permalink( get_option('woocommerce_myaccount_page_id') );
		$current_url = $_SERVER['REQUEST_URI'];
		$current_page = explode('/', $current_url);
		// remove last element
		array_pop($current_page);
		$current_page = (int)end($current_page) == 0 ? 1 : (int)end($current_page);
		$current_url = $endpoint_url.'?orders/';
		@endphp
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			@if ( 1 < $current_page  )
				@php
				$page = $current_page - 1;
				$previous_url = $current_url.$page.'/';
				$classes = 'pag-button woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button';
				@endphp
				<button class="{{ $classes }}" data-url="{{ $previous_url }}">{{ esc_html__( 'Previous', 'woocommerce' ) }}</button>
				{{-- <a class="{{ $classes }}" href="{{ $previous_url }}" data-url="{{ $previous_url }}">{{ esc_html__( 'Previous', 'woocommerce' ) }}</a> --}}
			@endif
				
			@if ( intval( $customer_orders->max_num_pages ) !== $current_page )
				@php
				$page = $current_page + 1;
				$next_url = $current_url.$page.'/';
				$classes = 'pag-button woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button';
				@endphp
				<button class="{{ $classes }}" data-url="{{ $next_url }}">{{ esc_html__( 'Next', 'woocommerce' ) }}</button>
				{{-- <a class="{{ $classes }}" href="{{ esc_url($next_url) }}">{{ esc_html__( 'Next', 'woocommerce' ) }}</a> --}}
			@endif
		</div>
	@endif

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse products', 'woocommerce' ); ?></a>
		<?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>

@action( 'woocommerce_after_account_orders', $has_orders )

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const buttons = document.querySelectorAll('.pag-button');
		buttons.forEach(button => {
			button.addEventListener('click', function() {
				const url = button.getAttribute('data-url');
				window.location.href = url;
			});
		});
	});
</script>
