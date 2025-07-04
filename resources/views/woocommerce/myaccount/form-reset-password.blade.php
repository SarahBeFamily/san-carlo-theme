@php
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
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

@action( 'woocommerce_before_reset_password_form' )

<div class="woocommerce-account">

	<div class="woo-wrap">

		{{-- In case of lost password error --}}
		@if ( isset( $_GET['resetpassword'] ) && $_GET['resetpassword'] == 'failed' )
			<div class="bf_error">
				@if (isset($_GET['reason']) && $_GET['reason'] == 'invalid-recaptcha')
					<p>{{ _e( 'Warning: Invalid reCAPTCHA', 'san-carlo-theme' ) }}</p>
				@elseif (isset($_GET['reason']) && $_GET['reason'] == 'missed-recaptcha')
					<p>{{ _e( 'Warning: reCAPTCHA is required', 'san-carlo-theme' ) }}</p>
				@endif
			</div>
		@endif

		<form method="post" class="woocommerce-ResetPassword lost_reset_password">

			<p>@php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'woocommerce' ) ) @endphp</p>

			<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
				<label for="password_1">@php esc_html_e( 'New password', 'woocommerce' ) @endphp &nbsp;<span class="required">*</span></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" autocomplete="new-password" />
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
				<label for="password_2">@php esc_html_e( 'Re-enter new password', 'woocommerce' ) @endphp &nbsp;<span class="required">*</span></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" />
			</p>

			<input type="hidden" name="reset_key" value="@php echo esc_attr( $args['key'] ) @endphp" />
			<input type="hidden" name="reset_login" value="@php echo esc_attr( $args['login'] ) @endphp" />

			<div class="clear"></div>

			@action( 'woocommerce_resetpassword_form' )

			<div id="ts-container" class="cf-turnstile" data-sitekey="<?php echo TS_CAPTCHA_DEV_SITE_KEY; ?>"></div>

			<p class="woocommerce-form-row form-row">
				<input type="hidden" name="wc_reset_password" value="true" />
				<button type="submit" class="woocommerce-Button button @php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) @endphp" value="@php esc_attr_e( 'Save', 'woocommerce' ) @endphp">@php esc_html_e( 'Save', 'woocommerce' ) @endphp</button>
			</p>

			@php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ) @endphp

		</form>
		@action( 'woocommerce_after_reset_password_form' )

	</div>
</div>

