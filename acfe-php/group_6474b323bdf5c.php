<?php 

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_6474b323bdf5c',
	'title' => 'Campi Articoli',
	'fields' => array(
		array(
			'key' => 'field_6474b336eadda',
			'label' => 'Layout',
			'name' => 'layout',
			'type' => 'flexible_content',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'wpml_cf_preferences' => 1,
			'acfe_save_meta' => 0,
			'acfe_flexible_advanced' => 1,
			'acfe_flexible_stylised_button' => 1,
			'acfe_flexible_layouts_templates' => 0,
			'acfe_flexible_layouts_placeholder' => 0,
			'acfe_flexible_layouts_thumbnails' => 0,
			'acfe_flexible_layouts_settings' => 0,
			'acfe_flexible_async' => array(
			),
			'acfe_flexible_add_actions' => array(
				0 => 'toggle',
				1 => 'copy',
				2 => 'close',
			),
			'acfe_flexible_remove_button' => array(
			),
			'acfe_flexible_layouts_state' => 'collapse',
			'acfe_flexible_modal_edit' => array(
				'acfe_flexible_modal_edit_enabled' => '0',
				'acfe_flexible_modal_edit_size' => 'large',
			),
			'acfe_flexible_modal' => array(
				'acfe_flexible_modal_enabled' => '0',
				'acfe_flexible_modal_title' => false,
				'acfe_flexible_modal_size' => 'full',
				'acfe_flexible_modal_col' => '4',
				'acfe_flexible_modal_categories' => false,
			),
			'layouts' => array(
				'layout_6474b3411ac48' => array(
					'key' => 'layout_6474b3411ac48',
					'name' => 'titolo',
					'label' => 'Titolo',
					'display' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_6474b3e2eaddc',
							'label' => 'Titolo',
							'name' => 'titolo',
							'type' => 'textarea',
							'instructions' => 'inserisci un titolo custom per l\'articolo (per esempio con accapo definiti - con tag br), se questo campo è vuoto verrà inserito il titolo standard',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'wpml_cf_preferences' => 2,
							'acfe_save_meta' => 0,
							'default_value' => '',
							'placeholder' => '',
							'maxlength' => '',
							'rows' => '',
							'new_lines' => 'br',
							'acfe_textarea_code' => 0,
						),
						array(
							'key' => 'field_6474b390eaddb',
							'label' => 'Elemento accanto al titolo',
							'name' => 'elemento_accanto_al_titolo',
							'type' => 'select',
							'instructions' => 'Scegli il tipo di elemento da visualizzare accanto al titolo',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'choices' => array(
								'foto' => 'foto',
								'testo' => 'testo',
							),
							'default_value' => false,
							'allow_null' => 0,
							'multiple' => 0,
							'ui' => 0,
							'return_format' => 'value',
							'ajax' => 0,
							'placeholder' => '',
							'allow_custom' => 0,
							'search_placeholder' => '',
						),
						array(
							'key' => 'field_6474b42deaddd',
							'label' => 'Foto titolo',
							'name' => 'foto_titolo',
							'type' => 'image',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => array(
								array(
									array(
										'field' => 'field_6474b390eaddb',
										'operator' => '==',
										'value' => 'foto',
									),
								),
							),
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'uploader' => '',
							'acfe_thumbnail' => 0,
							'return_format' => 'array',
							'preview_size' => 'medium',
							'min_width' => '',
							'min_height' => '',
							'min_size' => '',
							'max_width' => '',
							'max_height' => '',
							'max_size' => '',
							'mime_types' => '',
							'library' => 'all',
						),
						array(
							'key' => 'field_6474b450eadde',
							'label' => 'Testo titolo',
							'name' => 'testo_titolo',
							'type' => 'wysiwyg',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => array(
								array(
									array(
										'field' => 'field_6474b390eaddb',
										'operator' => '==',
										'value' => 'testo',
									),
								),
							),
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'wpml_cf_preferences' => 2,
							'acfe_save_meta' => 0,
							'default_value' => '',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 0,
							'delay' => 0,
						),
					),
					'min' => '1',
					'max' => '1',
					'acfe_flexible_render_template' => false,
					'acfe_flexible_render_style' => false,
					'acfe_flexible_render_script' => false,
					'acfe_flexible_thumbnail' => false,
					'acfe_flexible_settings' => false,
					'acfe_flexible_settings_size' => 'medium',
					'acfe_flexible_modal_edit_size' => false,
					'acfe_flexible_category' => false,
				),
				'layout_6474b47deaddf' => array(
					'key' => 'layout_6474b47deaddf',
					'name' => 'due_colonne',
					'label' => 'Due Colonne',
					'display' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_6474b4a4eade0',
							'label' => 'Colonna',
							'name' => 'col',
							'type' => 'repeater',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'wpml_cf_preferences' => 1,
							'acfe_save_meta' => 0,
							'acfe_repeater_stylised_button' => 0,
							'collapsed' => '',
							'min' => 0,
							'max' => 2,
							'layout' => 'block',
							'button_label' => '',
							'sub_fields' => array(
								array(
									'key' => 'field_6474b524eade5',
									'label' => 'Elemento',
									'name' => 'elemento',
									'type' => 'select',
									'instructions' => 'Scegli il tipo di elemento da visualizzare',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'wpml_cf_preferences' => 0,
									'acfe_save_meta' => 0,
									'choices' => array(
										'foto' => 'foto',
										'video' => 'video',
										'testo' => 'testo',
									),
									'default_value' => false,
									'allow_null' => 0,
									'multiple' => 0,
									'ui' => 0,
									'return_format' => 'value',
									'ajax' => 0,
									'placeholder' => '',
									'allow_custom' => 0,
									'search_placeholder' => '',
								),
								array(
									'key' => 'field_6474b524eade6',
									'label' => 'Foto',
									'name' => 'foto',
									'type' => 'image',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array(
										array(
											array(
												'field' => 'field_6474b524eade5',
												'operator' => '==',
												'value' => 'foto',
											),
										),
									),
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'wpml_cf_preferences' => 0,
									'acfe_save_meta' => 0,
									'uploader' => '',
									'return_format' => 'array',
									'preview_size' => 'medium',
									'min_width' => '',
									'min_height' => '',
									'min_size' => '',
									'max_width' => '',
									'max_height' => '',
									'max_size' => '',
									'mime_types' => '',
									'acfe_thumbnail' => 0,
									'library' => 'all',
								),
								array(
									'key' => 'field_6475d8f3ba7ca',
									'label' => 'Video',
									'name' => 'video',
									'type' => 'file',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array(
										array(
											array(
												'field' => 'field_6474b524eade5',
												'operator' => '==',
												'value' => 'video',
											),
										),
									),
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'wpml_cf_preferences' => 0,
									'acfe_save_meta' => 0,
									'uploader' => '',
									'return_format' => 'url',
									'min_size' => '',
									'max_size' => '',
									'mime_types' => 'mp4, webm, flv',
									'library' => 'all',
								),
								array(
									'key' => 'field_6474b4feeade3',
									'label' => 'Testo',
									'name' => 'testo',
									'type' => 'wysiwyg',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array(
										array(
											array(
												'field' => 'field_6474b524eade5',
												'operator' => '==',
												'value' => 'testo',
											),
										),
									),
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'wpml_cf_preferences' => 2,
									'acfe_save_meta' => 0,
									'default_value' => '',
									'tabs' => 'all',
									'toolbar' => 'full',
									'media_upload' => 0,
									'delay' => 0,
								),
							),
						),
					),
					'min' => '',
					'max' => '',
					'acfe_flexible_render_template' => false,
					'acfe_flexible_render_style' => false,
					'acfe_flexible_render_script' => false,
					'acfe_flexible_thumbnail' => false,
					'acfe_flexible_settings' => false,
					'acfe_flexible_settings_size' => 'medium',
					'acfe_flexible_modal_edit_size' => false,
					'acfe_flexible_category' => false,
				),
				'layout_6474b561eade8' => array(
					'key' => 'layout_6474b561eade8',
					'name' => 'colonna_unica',
					'label' => 'Colonna unica',
					'display' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_6474b561eade9',
							'label' => 'Elemento Colonna Unica',
							'name' => 'elemento_colonna_unica',
							'type' => 'repeater',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'acfe_repeater_stylised_button' => 0,
							'collapsed' => '',
							'min' => 0,
							'max' => 0,
							'layout' => 'row',
							'button_label' => 'Aggiungi elemento',
							'sub_fields' => array(
								array(
									'key' => 'field_6474b669eadf2',
									'label' => 'Scegli elemento',
									'name' => 'scegli_elemento',
									'type' => 'select',
									'instructions' => 'Scegli il tipo di elemento da visualizzare',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'wpml_cf_preferences' => 0,
									'acfe_save_meta' => 0,
									'choices' => array(
										'frase' => 'frase in evidenza',
										'foto' => 'foto',
										'video' => 'video',
										'testo' => 'testo',
									),
									'default_value' => false,
									'allow_null' => 0,
									'multiple' => 0,
									'ui' => 0,
									'return_format' => 'value',
									'ajax' => 0,
									'placeholder' => '',
									'allow_custom' => 0,
									'search_placeholder' => '',
								),
								array(
									'key' => 'field_6474b6aceadf3',
									'label' => 'Frase in evidenza',
									'name' => 'frase_in_evidenza',
									'type' => 'textarea',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array(
										array(
											array(
												'field' => 'field_6474b669eadf2',
												'operator' => '==',
												'value' => 'frase',
											),
										),
									),
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'wpml_cf_preferences' => 2,
									'acfe_save_meta' => 0,
									'default_value' => '',
									'placeholder' => '',
									'maxlength' => '',
									'rows' => '',
									'new_lines' => '',
									'acfe_textarea_code' => 0,
								),
								array(
									'key' => 'field_6474b6e1eadf4',
									'label' => 'Foto',
									'name' => 'foto',
									'type' => 'image',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array(
										array(
											array(
												'field' => 'field_6474b669eadf2',
												'operator' => '==',
												'value' => 'foto',
											),
										),
									),
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'wpml_cf_preferences' => 0,
									'acfe_save_meta' => 0,
									'uploader' => '',
									'return_format' => 'array',
									'preview_size' => 'medium',
									'min_width' => '',
									'min_height' => '',
									'min_size' => '',
									'max_width' => '',
									'max_height' => '',
									'max_size' => '',
									'mime_types' => '',
									'acfe_thumbnail' => 0,
									'library' => 'all',
								),
								array(
									'key' => 'field_6475dc88c7381',
									'label' => 'Video',
									'name' => 'video',
									'type' => 'file',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array(
										array(
											array(
												'field' => 'field_6474b669eadf2',
												'operator' => '==',
												'value' => 'video',
											),
										),
									),
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'wpml_cf_preferences' => 0,
									'acfe_save_meta' => 0,
									'uploader' => '',
									'return_format' => 'url',
									'min_size' => '',
									'max_size' => '',
									'mime_types' => 'mp4, webm, flv',
									'library' => 'all',
								),
								array(
									'key' => 'field_6474b6f3eadf5',
									'label' => 'Testo',
									'name' => 'testo',
									'type' => 'wysiwyg',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array(
										array(
											array(
												'field' => 'field_6474b669eadf2',
												'operator' => '==',
												'value' => 'testo',
											),
										),
									),
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'wpml_cf_preferences' => 2,
									'acfe_save_meta' => 0,
									'default_value' => '',
									'tabs' => 'all',
									'toolbar' => 'full',
									'media_upload' => 0,
									'delay' => 0,
								),
							),
						),
					),
					'min' => '',
					'max' => '',
					'acfe_flexible_render_template' => false,
					'acfe_flexible_render_style' => false,
					'acfe_flexible_render_script' => false,
					'acfe_flexible_thumbnail' => false,
					'acfe_flexible_settings' => false,
					'acfe_flexible_settings_size' => 'medium',
					'acfe_flexible_modal_edit_size' => false,
					'acfe_flexible_category' => false,
				),
			),
			'button_label' => 'Aggiungi Riga',
			'min' => '',
			'max' => '',
			'acfe_flexible_hide_empty_message' => false,
			'acfe_flexible_empty_message' => '',
			'acfe_flexible_layouts_previews' => false,
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
			array(
				'param' => 'post_category',
				'operator' => '==',
				'value' => 'category:non-categorizzato',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
	'acfe_display_title' => '',
	'acfe_autosync' => array(
		0 => 'php',
	),
	'acfml_field_group_mode' => 'advanced',
	'acfe_form' => 0,
	'acfe_meta' => '',
	'acfe_note' => '',
	'acfe_categories' => array(
		'articoli' => 'Articoli',
	),
	'modified' => 1685446345,
));

endif;