<?php 

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_649af8f527923',
	'title' => 'Campi Form Rimborsi',
	'fields' => array(
		array(
			'key' => 'field_649af90d6cef7',
			'label' => 'Testo privacy',
			'name' => 'privacy_text',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'wpml_cf_preferences' => 0,
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => '',
			'new_lines' => 'wpautop',
			'acfe_textarea_code' => 0,
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'page_template',
				'operator' => '==',
				'value' => 'rimborsi.blade.php',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'left',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 1,
	'acfe_display_title' => '',
	'acfe_autosync' => array(
		0 => 'php',
	),
	'acfml_field_group_mode' => 'localization',
	'acfe_form' => 1,
	'acfe_meta' => '',
	'acfe_note' => '',
	'acfe_categories' => array(
		'templates' => 'Templates',
	),
	'modified' => 1687877949,
));

endif;