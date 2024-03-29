@php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
@endphp

@action( 'woocommerce_before_customer_login_form' )


		<h2>@php esc_html_e( 'Login', 'woocommerce' ); @endphp</h2>

		<p>@php esc_html_e( 'Fill the form with your personal details and create your account. You can modify or cancel the provided information whenever you want entering your personal area.', 'san-carlo-theme' ); @endphp</p>

		<form class="woocommerce-form woocommerce-form-login login" method="post">

			@action( 'woocommerce_login_form_start' )

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="username">@php esc_html_e( 'Username or email address', 'woocommerce' ); @endphp &nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="@php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; @endphp" />
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password">@php esc_html_e( 'Password', 'woocommerce' ); @endphp &nbsp;<span class="required">*</span></label>
				<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" /><i onclick="showPsw()" class="bf-icon icon-hide-password toggle-password"></i>
			</p>

			@php do_action( 'woocommerce_login_form' ); @endphp

			<p class="form-row">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span>@php esc_html_e( 'Remember me', 'woocommerce' ); @endphp</span>
				</label>
				@php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); @endphp
				<button type="submit" class="woocommerce-button button woocommerce-form-login__submit @php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); @endphp" name="login" value="@php esc_attr_e( 'Log in', 'woocommerce' ); @endphp">@php esc_html_e( 'Log in', 'woocommerce' ); @endphp</button>
			</p>
			<p class="woocommerce-LostPassword lost_password">
				<a href="@php echo esc_url( wp_lostpassword_url() ); @endphp">@php esc_html_e( 'Lost your password?', 'woocommerce' ); @endphp</a>
			</p>

			<p>
				{{-- translators: %s è il link di registrazione utente --}}
				@php sprintf( __('Don\'t you have an account yet? <a href="%s">Register</a>', 'san-carlo-theme'),
					home_url('/user-registration');
			 	 ); @endphp
			</p>

			@action( 'woocommerce_login_form_end' )

		</form>
		<script>
			function showPsw() {
				var x = document.getElementById("user_pass");
				if (x.type === "password") {
					x.type = "text";
				} else {
					x.type = "password";
				}
			} 
		</script>
	</div>

</div>

@action( 'woocommerce_after_customer_login_form' )
