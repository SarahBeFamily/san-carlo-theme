@php
/**
 * The template for displaying cart content in the navigation.php template
 *
 * This is an Override done by VV for Raccoon theme
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

 if ( ! defined( 'ABSPATH' ) ) 
	exit;

$user_id = get_current_user_id();
$current_user = wp_get_current_user();
$roles = ( array ) $current_user->roles;
$nome = $current_user->user_firstname && $current_user->user_lastname ? $current_user->user_firstname.' '.$current_user->user_lastname : $current_user->user_login;

do_action( 'woocommerce_before_account_navigation' );
@endphp
<div class="account-navigation-wrap">
	<nav class="woocommerce-MyAccount-navigation">

		<div class="account-avatar">
			<div class="customer-image">
				@php echo get_avatar( get_the_author_meta( $current_user->ID ), 40 ) @endphp
			</div>
			<div class="customer-name">@php echo $nome @endphp</div>
		</div>
		
		<ul>
			@foreach ( wc_get_account_menu_items() as $endpoint => $label )
				{{-- @php var_dump($endpoint) @endphp --}}
				@php
					$endpoint_url = get_permalink( get_option('woocommerce_myaccount_page_id') );
					//wc_get_account_endpoint_url( $endpoint )
				@endphp
				<li class="@php echo wc_get_account_menu_item_classes( $endpoint ) @endphp">
					<a href="@php echo $endpoint_url.'?'.$endpoint @endphp">@php echo esc_html( $label ) @endphp</a>
				</li>
			@endforeach
		</ul>
	</nav>
</div>

@php do_action( 'woocommerce_after_account_navigation' ); @endphp
