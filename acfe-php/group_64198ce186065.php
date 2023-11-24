<?php 

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_64198ce186065',
	'title' => 'Campi Spettacoli',
	'fields' => array(
		array(
			'key' => 'field_646f36b0e476b',
			'label' => 'Titolo pagina singolo spettacolo',
			'name' => 'titolo_area',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 2,
			'acfe_save_meta' => 0,
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => '',
			'new_lines' => '',
			'acfe_textarea_code' => 0,
			'acfe_settings' => '',
			'acfe_validate' => '',
		),
		array(
			'key' => 'field_64198e240d7d1',
			'label' => 'Date e Orari',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
			'wpml_cf_preferences' => 1,
		),
		array(
			'key' => 'field_64198d030d7cf',
			'label' => 'Data inizio',
			'name' => 'data_inizio',
			'type' => 'date_picker',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'display_format' => 'd/m/Y',
			'return_format' => 'd/m/Y',
			'first_day' => 1,
			'acfe_settings' => '',
			'acfe_validate' => '',
			'wpml_cf_preferences' => 1,
		),
		array(
			'key' => 'field_64198e080d7d0',
			'label' => 'Data fine',
			'name' => 'data_fine',
			'type' => 'date_picker',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'display_format' => 'd/m/Y',
			'return_format' => 'd/m/Y',
			'first_day' => 1,
			'acfe_settings' => '',
			'acfe_validate' => '',
			'wpml_cf_preferences' => 1,
		),
		array(
			'key' => 'field_649c1cb483d3c',
			'label' => 'Annullato?',
			'name' => 'annullato',
			'type' => 'true_false',
			'instructions' => 'Impostare qui l\'annullamento dello spettacolo (necessario per il form rimborsi)',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 1,
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'acfe_settings' => '',
			'acfe_validate' => '',
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_649c1cda83d3d',
			'label' => 'Date annullate',
			'name' => 'date_annullate',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_649c1cb483d3c',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 1,
			'acfe_repeater_stylised_button' => 0,
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'block',
			'button_label' => 'Aggiungi data',
			'acfe_settings' => '',
			'sub_fields' => array(
				array(
					'key' => 'field_649c1cfe83d3e',
					'label' => 'Data annullata',
					'name' => 'data_annullata',
					'type' => 'date_picker',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'wpml_cf_preferences' => 1,
					'display_format' => 'd/m/Y',
					'return_format' => 'd/m/Y',
					'first_day' => 1,
					'acfe_settings' => '',
					'acfe_validate' => '',
				),
			),
		),
		array(
			'key' => 'field_644bcc719f0be',
			'label' => 'Prodotto relazionato',
			'name' => 'prodotto_relazionato',
			'type' => 'post_object',
			'instructions' => 'Scegli lo spettacolo corrispondente, a cui collegare date orari e link dik acquisto',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 1,
			'acfe_save_meta' => 0,
			'post_type' => array(
				0 => 'spettacolo',
			),
			'taxonomy' => '',
			'allow_null' => 1,
			'multiple' => 0,
			'return_format' => 'id',
			'save_custom' => 0,
			'save_post_status' => 'publish',
			'acfe_bidirectional' => array(
				'acfe_bidirectional_enabled' => '0',
			),
			'acfe_settings' => '',
			'acfe_validate' => '',
			'ui' => 1,
			'save_post_type' => '',
		),
		array(
			'key' => 'field_641991045be72',
			'label' => 'Altre info',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'placement' => 'top',
			'endpoint' => 0,
			'wpml_cf_preferences' => 1,
		),
		array(
			'key' => 'field_641991125be73',
			'label' => 'Location',
			'name' => 'location',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 2,
			'acfe_save_meta' => 0,
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'acfe_settings' => '',
			'acfe_validate' => '',
		),
		array(
			'key' => 'field_645e3f70dfe13',
			'label' => 'Immagine verticale',
			'name' => 'immagine_verticale',
			'type' => 'image_aspect_ratio_crop',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 2,
			'crop_type' => 'aspect_ratio',
			'aspect_ratio_width' => 530,
			'aspect_ratio_height' => 653,
			'return_format' => 'url',
			'preview_size' => 'medium',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
			'acfe_settings' => '',
			'acfe_validate' => '',
		),
		array(
			'key' => 'field_641991325be74',
			'label' => 'Breve descrizione',
			'name' => 'breve_descrizione',
			'type' => 'text',
			'instructions' => 'Inserisci una breve descrizione che andrà inserita nei vari componenti del sito (slider, calendario, ecc)',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 2,
			'acfe_save_meta' => 0,
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'acfe_settings' => '',
			'acfe_validate' => '',
		),
		array(
			'key' => 'field_643f19fecbe1a',
			'label' => 'Layout',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'placement' => 'top',
			'endpoint' => 0,
			'wpml_cf_preferences' => 1,
		),
		array(
			'key' => 'field_643f1a55cbe1b',
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
			'acfe_permissions' => '',
			'acfe_flexible_advanced' => 0,
			'layouts' => array(
				'layout_643f1a9b74591' => array(
					'key' => 'layout_643f1a9b74591',
					'name' => 'testo_semplice',
					'label' => 'Testo semplice',
					'display' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_643f1b58cbe1d',
							'label' => 'Titolo',
							'name' => 'titolo',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'acfe_settings' => '',
							'acfe_validate' => '',
							'wpml_cf_preferences' => 2,
						),
						array(
							'key' => 'field_643f1b64cbe1e',
							'label' => 'Testo',
							'name' => 'testo',
							'type' => 'wysiwyg',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 1,
							'delay' => 0,
							'acfe_settings' => '',
							'acfe_validate' => '',
							'wpml_cf_preferences' => 2,
						),
						array(
							'key' => 'field_643f1b78cbe1f',
							'label' => 'Bottone link',
							'name' => 'bottone_link',
							'type' => 'link',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'return_format' => 'url',
							'acfe_settings' => '',
							'acfe_validate' => '',
							'wpml_cf_preferences' => 1,
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
				'layout_643f1bd2cbe20' => array(
					'key' => 'layout_643f1bd2cbe20',
					'name' => 'video',
					'label' => 'Video',
					'display' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_643f1c927b24b',
							'label' => 'File Video',
							'name' => 'file_video',
							'type' => 'file',
							'instructions' => '',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'acfe_permissions' => '',
							'uploader' => '',
							'return_format' => 'url',
							'min_size' => '',
							'max_size' => '',
							'mime_types' => 'mp4, webm, flv',
							'acfe_settings' => '',
							'acfe_validate' => '',
							'library' => 'all',
							'wpml_cf_preferences' => 1,
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
				'layout_643f1ce97b24c' => array(
					'key' => 'layout_643f1ce97b24c',
					'name' => 'lista_casting',
					'label' => 'Lista Casting',
					'display' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_643f1d337b24e',
							'label' => 'Titolo',
							'name' => 'titolo',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'acfe_settings' => '',
							'acfe_validate' => '',
							'wpml_cf_preferences' => 2,
						),
						array(
							'key' => 'field_643f1d3d7b24f',
							'label' => 'Descrizione sezione',
							'name' => 'descrizione_sezione',
							'type' => 'wysiwyg',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 1,
							'delay' => 0,
							'acfe_settings' => '',
							'acfe_validate' => '',
							'wpml_cf_preferences' => 2,
						),
						array(
							'key' => 'field_6454bee0ed076',
							'label' => 'Modalità',
							'name' => 'modalita',
							'type' => 'select',
							'instructions' => 'Scegli il tipo di visualizzazione del cast',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'acfe_permissions' => '',
							'choices' => array(
								'list' => 'Lista con link "Mostra tutto"',
								'slide' => 'Slider',
								'toggle' => 'Fisarmonica',
							),
							'default_value' => false,
							'allow_null' => 0,
							'multiple' => 0,
							'ui' => 0,
							'return_format' => 'value',
							'acfe_settings' => '',
							'acfe_validate' => '',
							'ajax' => 0,
							'placeholder' => '',
							'allow_custom' => 0,
							'search_placeholder' => '',
							'wpml_cf_preferences' => 1,
						),
						array(
							'key' => 'field_643f1d027b24d',
							'label' => 'Membri Cast',
							'name' => 'membri_cast',
							'type' => 'repeater',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'acfe_permissions' => '',
							'wpml_cf_preferences' => 1,
							'acfe_save_meta' => 0,
							'acfe_repeater_stylised_button' => 0,
							'collapsed' => '',
							'min' => 0,
							'max' => 0,
							'layout' => 'table',
							'button_label' => 'Aggiungi membro',
							'acfe_settings' => '',
							'sub_fields' => array(
								array(
									'key' => 'field_643f1d727b250',
									'label' => 'Foto',
									'name' => 'foto',
									'type' => 'image',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array(
										array(
											array(
												'field' => 'field_6454bee0ed076',
												'operator' => '!=',
												'value' => 'toggle',
											),
										),
									),
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'acfe_permissions' => '',
									'acfe_save_meta' => 0,
									'uploader' => '',
									'return_format' => 'url',
									'preview_size' => 'thumbnail',
									'min_width' => '',
									'min_height' => '',
									'min_size' => '',
									'max_width' => '',
									'max_height' => '',
									'max_size' => '',
									'mime_types' => '',
									'acfe_thumbnail' => 0,
									'acfe_settings' => '',
									'acfe_validate' => '',
									'library' => 'all',
									'wpml_cf_preferences' => 1,
								),
								array(
									'key' => 'field_643f1d977b251',
									'label' => 'Nome',
									'name' => 'nome',
									'type' => 'text',
									'instructions' => '',
									'required' => 1,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'acfe_permissions' => '',
									'wpml_cf_preferences' => 1,
									'acfe_save_meta' => 0,
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
									'acfe_settings' => '',
									'acfe_validate' => '',
								),
								array(
									'key' => 'field_6465056f22f41',
									'label' => 'Cognome',
									'name' => 'cognome',
									'type' => 'text',
									'instructions' => '',
									'required' => 1,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'acfe_permissions' => '',
									'wpml_cf_preferences' => 1,
									'acfe_save_meta' => 0,
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
									'acfe_settings' => '',
									'acfe_validate' => '',
								),
								array(
									'key' => 'field_643f1dc77b252',
									'label' => 'Ruolo',
									'name' => 'ruolo',
									'type' => 'text',
									'instructions' => '',
									'required' => 1,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'acfe_permissions' => '',
									'acfe_save_meta' => 0,
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
									'acfe_settings' => '',
									'acfe_validate' => '',
									'wpml_cf_preferences' => 2,
								),
								array(
									'key' => 'field_6454bf2bed077',
									'label' => 'Bio',
									'name' => 'bio',
									'type' => 'textarea',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array(
										array(
											array(
												'field' => 'field_6454bee0ed076',
												'operator' => '!=',
												'value' => 'slide',
											),
										),
									),
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'acfe_permissions' => '',
									'default_value' => '',
									'placeholder' => '',
									'maxlength' => '',
									'rows' => '',
									'new_lines' => '',
									'acfe_textarea_code' => 0,
									'acfe_settings' => '',
									'acfe_validate' => '',
									'wpml_cf_preferences' => 2,
								),
								array(
									'key' => 'field_6454bfe1ed078',
									'label' => 'Social',
									'name' => 'social',
									'type' => 'repeater',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => array(
										array(
											array(
												'field' => 'field_6454bee0ed076',
												'operator' => '==',
												'value' => 'toggle',
											),
										),
									),
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'acfe_permissions' => '',
									'acfe_repeater_stylised_button' => 0,
									'collapsed' => '',
									'min' => 0,
									'max' => 0,
									'layout' => 'table',
									'button_label' => 'Aggiungi profilo social',
									'acfe_settings' => '',
									'wpml_cf_preferences' => 1,
									'sub_fields' => array(
										array(
											'key' => 'field_6454c006ed079',
											'label' => 'Social',
											'name' => 'social',
											'type' => 'select',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'choices' => array(
												'instagram' => 'instagram',
												'facebook' => 'facebook',
												'twitter' => 'twitter',
												'linkedin' => 'linkedin',
											),
											'default_value' => false,
											'allow_null' => 0,
											'multiple' => 0,
											'ui' => 0,
											'return_format' => 'value',
											'acfe_settings' => '',
											'acfe_validate' => '',
											'ajax' => 0,
											'placeholder' => '',
											'allow_custom' => 0,
											'search_placeholder' => '',
											'wpml_cf_preferences' => 1,
										),
										array(
											'key' => 'field_6454c028ed07a',
											'label' => 'Link Social',
											'name' => 'link_social',
											'type' => 'url',
											'instructions' => '',
											'required' => 1,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'default_value' => '',
											'placeholder' => '',
											'acfe_settings' => '',
											'acfe_validate' => '',
											'wpml_cf_preferences' => 1,
										),
									),
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
				'layout_643f1e147b253' => array(
					'key' => 'layout_643f1e147b253',
					'name' => 'gallery',
					'label' => 'Gallery',
					'display' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_643f1e217b254',
							'label' => 'Gallery',
							'name' => 'photogallery',
							'type' => 'gallery',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'acfe_permissions' => '',
							'return_format' => 'url',
							'preview_size' => 'thumbnail',
							'insert' => 'append',
							'library' => 'all',
							'min' => '',
							'max' => '',
							'min_width' => '',
							'min_height' => '',
							'min_size' => '',
							'max_width' => '',
							'max_height' => '',
							'max_size' => '',
							'mime_types' => '',
							'acfe_settings' => '',
							'acfe_validate' => '',
							'wpml_cf_preferences' => 1,
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
				'layout_643f1ed18525e' => array(
					'key' => 'layout_643f1ed18525e',
					'name' => 'testo_incolonnato_a_dx',
					'label' => 'Testo incolonnato a dx',
					'display' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_643f1f1b8525f',
							'label' => 'Separatore',
							'name' => 'separatore',
							'type' => 'true_false',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'acfe_permissions' => '',
							'message' => '',
							'default_value' => 0,
							'ui' => 0,
							'acfe_settings' => '',
							'acfe_validate' => '',
							'ui_on_text' => '',
							'ui_off_text' => '',
							'wpml_cf_preferences' => 1,
						),
						array(
							'key' => 'field_643f201485263',
							'label' => 'Testo',
							'name' => 'testo',
							'type' => 'wysiwyg',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'acfe_permissions' => '',
							'default_value' => '',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 1,
							'delay' => 0,
							'acfe_settings' => '',
							'acfe_validate' => '',
							'wpml_cf_preferences' => 2,
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
			'button_label' => 'Aggiungi Sezione',
			'min' => '',
			'max' => '',
			'acfe_settings' => '',
			'acfe_flexible_stylised_button' => false,
			'acfe_flexible_hide_empty_message' => false,
			'acfe_flexible_empty_message' => '',
			'acfe_flexible_layouts_templates' => false,
			'acfe_flexible_layouts_previews' => false,
			'acfe_flexible_layouts_placeholder' => false,
			'acfe_flexible_layouts_thumbnails' => false,
			'acfe_flexible_layouts_settings' => false,
			'acfe_flexible_async' => array(
			),
			'acfe_flexible_add_actions' => array(
			),
			'acfe_flexible_remove_button' => array(
			),
			'acfe_flexible_layouts_state' => false,
			'acfe_flexible_modal_edit' => array(
				'acfe_flexible_modal_edit_enabled' => false,
				'acfe_flexible_modal_edit_size' => 'large',
			),
			'acfe_flexible_modal' => array(
				'acfe_flexible_modal_enabled' => false,
				'acfe_flexible_modal_title' => false,
				'acfe_flexible_modal_size' => 'full',
				'acfe_flexible_modal_col' => '4',
				'acfe_flexible_modal_categories' => false,
			),
			'wpml_cf_preferences' => 1,
		),
		array(
			'key' => 'field_649ec5e26e445',
			'label' => 'Tariffe',
			'name' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 1,
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_650411a24089f',
			'label' => 'Tariffe',
			'name' => 'tariffe',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 1,
			'acfe_repeater_stylised_button' => 0,
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => '',
			'acfe_settings' => '',
			'sub_fields' => array(
				array(
					'key' => 'field_650411b4408a0',
					'label' => 'Tariffe tab',
					'name' => 'tariffe_tab',
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
					'acfe_repeater_stylised_button' => 0,
					'collapsed' => '',
					'min' => 0,
					'max' => 0,
					'layout' => 'table',
					'button_label' => '',
					'acfe_settings' => '',
					'sub_fields' => array(
						array(
							'key' => 'field_650411f7408a1',
							'label' => 'Tipo tariffa',
							'name' => 'tipo_tariffa',
							'type' => 'select',
							'instructions' => 'Es. III, IV',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'wpml_cf_preferences' => 1,
							'choices' => array(
								'I' => 'I',
								'II' => 'II',
								'III' => 'III',
								'IV' => 'IV',
								'V' => 'V',
								'VI' => 'VI',
								'VII' => 'VII',
								'VIII' => 'VIII',
								'IX' => 'IX',
								'X' => 'X',
								'XI' => 'XI',
								'XII' => 'XII',
							),
							'default_value' => false,
							'allow_null' => 0,
							'multiple' => 0,
							'ui' => 0,
							'return_format' => 'value',
							'acfe_settings' => '',
							'acfe_validate' => '',
							'ajax' => 0,
							'placeholder' => '',
							'allow_custom' => 0,
							'search_placeholder' => '',
						),
						array(
							'key' => 'field_65041263408a2',
							'label' => 'Posto',
							'name' => 'posto',
							'type' => 'select',
							'instructions' => '',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'wpml_cf_preferences' => 1,
							'choices' => array(
								'Top Orchestra Stalls GOLD' => 'Poltronissima ORO',
								'Top Orchestra Stalls' => 'Poltronissima',
								'Orchestra Stalls GOLD' => 'Poltrona ORO',
								'Orchestra Stalls' => 'Poltrona',
								'I, II tier central boxes parapet' => 'Palco I, II fila centrali parapetto',
								'I, II tier central boxes behind' => 'Palco I, II fila centrali dietro',
								'Royal Box' => 'Palco reale',
								'I, II tier side boxes parapet' => 'Palco I, II fila laterali parapetto',
								'I, II tier side boxes behind' => 'Palco I, II fila laterali dietro',
								'III, IV tier central boxes parapet' => 'Palco III, IV fila centrali parapetto',
								'III, IV tier central boxes behind' => 'Palco III, IV fila centrali dietro',
								'III, IV tier side boxes parapet' => 'Palco III, IV fila laterali parapetto',
								'III, IV tier side boxes behind' => 'Palco III, IV fila laterali dietro',
								'V, VI tier balconies' => 'Balconata V e VI fila',
								'Listening only seats' => 'Posti Solo Ascolto',
							),
							'default_value' => false,
							'allow_null' => 0,
							'multiple' => 0,
							'ui' => 0,
							'return_format' => 'array',
							'acfe_settings' => '',
							'acfe_validate' => '',
							'ajax' => 0,
							'placeholder' => '',
							'allow_custom' => 0,
							'search_placeholder' => '',
						),
						array(
							'key' => 'field_650412b5408a3',
							'label' => 'Tipo prezzo',
							'name' => 'tipo_prezzo',
							'type' => 'select',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'wpml_cf_preferences' => 1,
							'choices' => array(
								'full price' => 'intero',
								'reduced -10%*' => 'ridotto 10%*',
								'reduced -15%**' => 'ridotto 15%**',
								'under30 / over65' => 'under30 / over65',
							),
							'default_value' => false,
							'allow_null' => 0,
							'multiple' => 0,
							'ui' => 0,
							'return_format' => 'array',
							'acfe_settings' => '',
							'acfe_validate' => '',
							'ajax' => 0,
							'placeholder' => '',
							'allow_custom' => 0,
							'search_placeholder' => '',
						),
						array(
							'key' => 'field_650412d6408a4',
							'label' => 'Prezzo',
							'name' => 'prezzo',
							'type' => 'text',
							'instructions' => '',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'wpml_cf_preferences' => 1,
							'acfe_save_meta' => 0,
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'acfe_settings' => '',
							'acfe_validate' => '',
						),
					),
				),
			),
		),
		array(
			'key' => 'field_649ec68e6e44b',
			'label' => 'Note',
			'name' => 'note',
			'type' => 'wysiwyg',
			'instructions' => 'Inserisci eventuali note della tabella tariffe',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'wpml_cf_preferences' => 2,
			'default_value' => '',
			'tabs' => 'all',
			'toolbar' => 'full',
			'media_upload' => 1,
			'delay' => 0,
			'acfe_settings' => '',
			'acfe_validate' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'spettacoli',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'acf_after_title',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array(
		0 => 'the_content',
		1 => 'discussion',
		2 => 'comments',
	),
	'active' => true,
	'description' => '',
	'show_in_rest' => 1,
	'acfe_display_title' => '',
	'acfe_autosync' => array(
		0 => 'php',
	),
	'acfe_permissions' => '',
	'acfml_field_group_mode' => 'advanced',
	'acfe_form' => 1,
	'acfe_meta' => array(
		'6504110e92056' => array(
			'acfe_meta_key' => 'tabelle',
			'acfe_meta_value' => 'tariffe',
		),
	),
	'acfe_note' => '',
	'modified' => 1694765813,
));

endif;