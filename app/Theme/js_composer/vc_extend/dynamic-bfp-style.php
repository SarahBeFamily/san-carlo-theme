<?php

if (! function_exists('custom_vc_style') ) {

	function custom_vc_style() {
		$atts = $_POST['atts'] ? (array) $_POST['atts'] : array();

		$css  = '';

		if (is_array($atts)) {
			foreach ( $atts as $class => $el ) {

				if ( array_key_exists('padding_desktop_wide', $el ) ) {
					$css .= '@media screen and (min-width: 1920.5px) {';
					$css .= '.vc_row.'.$class.' > .vc_column_container {';
					$css .= 'padding: '. $el['padding_desktop_wide'] .';';
					$css .= '}';
					$css .= '.vc_row.'.$class.' > .vc_column_container > .vc_column-inner {';
						$css .= 'padding: 0;';
						$css .= '}';
					$css .= '}';
				}

				if ( array_key_exists('padding_desktop', $el ) ) {
					$css .= '@media screen and (min-width: 1440px) and (max-width: 1920px) {';
					$css .= '.vc_row.'.$class.' > .vc_column_container {';
					$css .= 'padding: '. $el['padding_desktop'] .';';
					$css .= '}';
					$css .= '.vc_row.'.$class.' > .vc_column_container > .vc_column-inner {';
						$css .= 'padding: 0;';
						$css .= '}';
					$css .= '}';
				}

				if ( array_key_exists('padding_small_desktop', $el ) ) {
					$css .= '@media screen and (min-width: 1280px) and (max-width: 1439px) {';
					$css .= '.vc_row.'.$class.' > .vc_column_container {';
					$css .= 'padding: '. $el['padding_small_desktop'] .';';
					$css .= '}';
					$css .= '.vc_row.'.$class.' > .vc_column_container > .vc_column-inner {';
						$css .= 'padding: 0;';
						$css .= '}';
					$css .= '}';
				}

				if ( array_key_exists('padding_mini', $el ) ) {
					$css .= '@media screen and (min-width: 1024px) and (max-width: 1279px) {';
					$css .= '.vc_row.'.$class.' > .vc_column_container {';
					$css .= 'padding: '. $el['padding_mini'] .';';
					$css .= '}';
					$css .= '.vc_row.'.$class.' > .vc_column_container > .vc_column-inner {';
						$css .= 'padding: 0;';
						$css .= '}';
					$css .= '}';
				}

				if ( array_key_exists('padding_tablet', $el ) ) {
					$css .= '@media screen and (min-width: 768px) and (max-width: 1023px) {';
					$css .= '.vc_row.'.$class.' > .vc_column_container {';
					$css .= 'padding: '. $el['padding_tablet'] .';';
					$css .= '}';
					$css .= '.vc_row.'.$class.' > .vc_column_container > .vc_column-inner {';
						$css .= 'padding: 0;';
						$css .= '}';
					$css .= '}';
				}

				if ( array_key_exists('padding_small_tablet', $el ) ) {
					$css .= '@media screen and (min-width: 640.5px) and (max-width: 767px) {';
					$css .= '.vc_row.'.$class.' > .vc_column_container {';
					$css .= 'padding: '. $el['padding_small_tablet'] .';';
					$css .= '}';
					$css .= '.vc_row.'.$class.' > .vc_column_container > .vc_column-inner {';
					$css .= 'padding: 0;';
					$css .= '}';
					$css .= '}';
				}

				if ( array_key_exists('padding_mobile', $el ) ) {
					$css .= '@media screen and (max-width: 640px) {';
					$css .= '.vc_row.'.$class.' > .vc_column_container {';
					$css .= 'padding: '. $el['padding_mobile'] .';';
					$css .= '}';
					$css .= '.vc_row.'.$class.' > .vc_column_container > .vc_column-inner {';
					$css .= 'padding: 0;';
					$css .= '}';
					$css .= '}';
				}
			}
		}

		// file_put_contents( TEMPLATEPATH . '/dynamic-responsive.css', $css, FILE_APPEND);
		// file_put_contents( TEMPLATEPATH . '/dynamic-responsive.css', $css);

		wp_send_json($css);
	}

}
add_action ( 'wp_ajax_nopriv_custom_vc_style', 'custom_vc_style' );
add_action ( 'wp_ajax_custom_vc_style', 'custom_vc_style' );
