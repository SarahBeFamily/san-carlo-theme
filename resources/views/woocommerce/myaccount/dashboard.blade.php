@php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
)
@endphp

<p>
	{{-- translators: %s è il nome utente --}}
	{!! wp_kses_post( sprintf(__( 'Hello %s,', 'san-carlo-theme' ), $current_user->display_name ) ) !!}
</p>

<p>
	{{__( 'From your account dashboard you can', 'san-carlo-theme')}}
</p>

@set($endpoint_url, get_permalink( get_option('woocommerce_myaccount_page_id') ))

<ul id="intro-dashboard">
	<li>
		<a href="{{$endpoint_url}}?orders">{{__( 'view your recent orders', 'san-carlo-theme' )}}</a>
	</li>
	<li>
		<a href="{{$endpoint_url}}?edit-account">{{__( 'edit your password and account details', 'san-carlo-theme' )}}</a>
	</li>
</ul>

<p>
	<a href="{{esc_url( wc_logout_url() )}}">{{__( 'Logout', 'san-carlo-theme' )}}</a>
</p>

	{{-- My Account dashboard. @since 2.6.0  --}}
	@action( 'woocommerce_account_dashboard' )


{{-- Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. --}}