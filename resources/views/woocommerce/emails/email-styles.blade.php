@php
/**
 * Email Styles
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-styles.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load colors.
$bg        = get_option( 'woocommerce_email_background_color' );
$body      = get_option( 'woocommerce_email_body_background_color' );
$base      = get_option( 'woocommerce_email_base_color' );
$base_text = wc_light_or_dark( $base, '#202020', '#ffffff' );
$text      = get_option( 'woocommerce_email_text_color' );

// Pick a contrasting color for links.
$link_color = wc_hex_is_light( $base ) ? $base : $base_text;

if ( wc_hex_is_light( $body ) ) {
	$link_color = wc_hex_is_light( $base ) ? $base_text : $base;
}

$bg_darker_10    = wc_hex_darker( $bg, 10 );
$body_darker_10  = wc_hex_darker( $body, 10 );
$base_lighter_20 = wc_hex_lighter( $base, 20 );
$base_lighter_40 = wc_hex_lighter( $base, 40 );
$text_lighter_20 = wc_hex_lighter( $text, 20 );
$text_lighter_40 = wc_hex_lighter( $text, 40 );

// !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
// body{padding: 0;} ensures proper scale/positioning of the email in the iOS native email app.
@endphp

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

body {
	padding: 0;
	margin: 0;
}

h1,h2,h3,h4, p, .text, body {
	font-family: 'Inter', sans-serif;
}

h1 {
	color: <?php echo esc_attr( $base ); ?>;
	font-size: 32px;
	font-weight: 700;
	line-height: 120%;
	margin: 0;
	text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
	text-shadow: 0 1px 0 <?php echo esc_attr( $base_lighter_20 ); ?>;
}

h2 {
	color: <?php echo esc_attr( $base ); ?>;
	display: block;
	font-size: 22px;
	text-transform: uppercase;
	font-weight: 600;
	line-height: 120%;
	margin: 0 0 18px;
	text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

h3 {
	color: white;
	display: block;
	font-size: 28px;
	font-weight: bold;
	line-height: 130%;
	margin: 0 0 80px;
	text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

h4 {
	color: white;
	display: block;
	font-size: 24px;
	font-weight: bold;
	line-height: 130%;
	margin: 0 0 20px;
	text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}


{{-- #hero {
  height: 300px;
  background-size: cover;
  background-position: center;
} --}}

.sezione {
	padding: 3% 5%;
	border-bottom: 1px solid;
	display: grid;
}

.bottone {
	color: white;
	background-color: #df3b3c;
	padding: 12px 20px 11px;
  text-decoration: none;
  text-transform: uppercase;
  font-weight: 500;
  display: inline-block;
  width: 180px;
  text-align: center;
  margin-top: 20px;
}

span {
	font-weight: 500;
}

.carrello {
	margin-bottom: 27px;
}

.dettagli-biglietti {
	display: grid;
}

.dettagli-ordine > div {
	width: 50%;
	display: grid;
}

.dettagli-biglietti span,
.dettagli-ordine span {
	line-height: 2;
}

#footer {
	background-color: #1c1c1c;
	padding: 3% 5%;
	display: flex;
	flex-direction: row;
	align-items: flex-start;
	justify-content: space-between;
	color: white;
	clear:both;
	width: 100%;
	max-width: 1000px;
}

{{-- #footer .flex-col.flex-end {
	display: grid;
	min-height: 230px;
	float: left;
	width: 50%;
} --}}

{{-- #footer .info {
	float: right;
	width: 50%;
} --}}

#footer .flex-col.flex-end > div {
	flex-basis: 100%;
}

#footer a {
	color: #878787 !important;
	text-decoration: none !important;
}

#footer .social-wrap {
	margin-top: 40px;
}

{{-- #outer_wrapper {
	background-color: <?php echo esc_attr( $bg ); ?>;
} --}}

#wrapper {
	margin: 0 auto;
	padding: 0;
	-webkit-text-size-adjust: none !important;
	width: 100%;
	max-width: 1000px;
}

.text {
	color: <?php echo esc_attr( $text ); ?>;
}


#hero img {
	max-width: 1000px;
	margin: 0;
}

img {
	border: none;
	display: inline-block;
	font-size: 14px;
	font-weight: bold;
	height: auto;
	outline: none;
	text-decoration: none;
	text-transform: capitalize;
	vertical-align: middle;
	margin-<?php echo is_rtl() ? 'left' : 'right'; ?>: 2px;
}

img.icona {
	float: left;
	margin-right:5px;
}

/**
 * Media queries are not supported by all email clients, however they do work on modern mobile
 * Gmail clients and can help us achieve better consistency there.
 */
@media screen and (max-width: 600px) {
	.sezione {
		padding: 8% 30px;
		border-bottom: 1px solid;
		display: grid;
	}
	
	.dettagli-ordine > div {
		width: 100%;
	}

	#footer .flex-col.flex-end {
		float: none;
		width: 100%;
	}
	
	#footer .info {
		float: none;
		width: 100%;
	}
}
<?php
