<?php 

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_62cfd36354787',
	'title' => 'Opzioni Generali',
	'fields' => array(
		array(
			'key' => 'field_62de93d6fd45b',
			'label' => 'Dati azienda',
			'name' => '',
			'aria-label' => '',
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
			'key' => 'field_62de97a9fd45d',
			'label' => 'Partita IVA',
			'name' => 'p_iva',
			'aria-label' => '',
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
			'wpml_cf_preferences' => 2,
		),
		array(
			'key' => 'field_62cfd409f40a1',
			'label' => 'Social',
			'name' => '',
			'aria-label' => '',
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
			'key' => 'field_62cfd415f40a2',
			'label' => 'Social',
			'name' => 'social',
			'aria-label' => '',
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
			'button_label' => 'Aggiungi social',
			'wpml_cf_preferences' => 1,
			'sub_fields' => array(
				array(
					'key' => 'field_62cfd450f40a3',
					'label' => 'Tipo social',
					'name' => 'tipo_social',
					'aria-label' => '',
					'type' => 'select',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => array(
						'facebook' => 'Facebook',
						'instagram' => 'Instagram',
						'twitter' => 'Twitter',
						'linkedin' => 'Linkedin',
						'youtube' => 'YouTube',
						'tiktok' => 'TikTok',
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
					'wpml_cf_preferences' => 1,
					'parent_repeater' => 'field_62cfd415f40a2',
				),
				array(
					'key' => 'field_62cfd8d4f40a4',
					'label' => 'Link',
					'name' => 'link',
					'aria-label' => '',
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
					'wpml_cf_preferences' => 1,
					'parent_repeater' => 'field_62cfd415f40a2',
				),
			),
			'rows_per_page' => 20,
		),
		array(
			'key' => 'field_65117a5cf6db4',
			'label' => 'Destinatari email',
			'name' => '',
			'aria-label' => '',
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
			'wpml_cf_preferences' => 0,
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_65117bf3f6db5',
			'label' => 'Form Rimborsi',
			'name' => 'form_rimborsi',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => 'inserire gli indirizzi email separati da una virgola',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 0,
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'acfe_settings' => '',
			'acfe_validate' => '',
		),
		array(
			'key' => 'field_65117ce0f6db6',
			'label' => 'Form Prenotazione Scuole',
			'name' => 'form_prenotazione_scuole',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => 'inserire gli indirizzi email separati da una virgola',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 0,
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'acfe_settings' => '',
			'acfe_validate' => '',
		),
		array(
			'key' => 'field_62cfd393f409f',
			'label' => 'Testi Footer',
			'name' => '',
			'aria-label' => '',
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
			'key' => 'field_62cfd3b5f40a0',
			'label' => 'Testo sezione Newsletter',
			'name' => 'newsletter_text',
			'aria-label' => '',
			'type' => 'textarea',
			'instructions' => 'Inserire del testo di introduzione alla Newsletter nel footer',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => '',
			'new_lines' => '',
			'acfe_textarea_code' => 0,
			'wpml_cf_preferences' => 2,
		),
		array(
			'key' => 'field_657ade9ce084a',
			'label' => 'Info footer',
			'name' => 'info_footer',
			'aria-label' => '',
			'type' => 'wysiwyg',
			'instructions' => 'Inserire qui i testi di info da inserire nel footer (anche in quello delle email)',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 2,
			'default_value' => '',
			'tabs' => 'all',
			'toolbar' => 'full',
			'media_upload' => 1,
			'delay' => 0,
			'acfe_settings' => '',
			'acfe_validate' => '',
		),
		array(
			'key' => 'field_643fbfd1706f4',
			'label' => 'Testi Fissi Spettacoli',
			'name' => '',
			'aria-label' => '',
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
			'key' => 'field_649ec885524af',
			'label' => 'Testi modalità d\'acquisto',
			'name' => 'testi_modalita_dacquisto',
			'aria-label' => '',
			'type' => 'wysiwyg',
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
			'default_value' => '',
			'tabs' => 'all',
			'toolbar' => 'full',
			'media_upload' => 1,
			'delay' => 0,
			'acfe_settings' => '',
			'acfe_validate' => '',
		),
		array(
			'key' => 'field_643fc00a706f6',
			'label' => 'Immagine di sfondo della sezione CTA',
			'name' => 'spettacoli_cta_bg',
			'aria-label' => '',
			'type' => 'image',
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
		),
		array(
			'key' => 'field_643fbfea706f5',
			'label' => 'Titoletto CTA',
			'name' => 'spettacoli_cta_titoletto',
			'aria-label' => '',
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
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'acfe_settings' => '',
			'acfe_validate' => '',
		),
		array(
			'key' => 'field_643fc02b706f7',
			'label' => 'Titolo CTA',
			'name' => 'spettacoli_cta_titolo',
			'aria-label' => '',
			'type' => 'textarea',
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
			'key' => 'field_6449092801021',
			'label' => 'Link Pagine principali',
			'name' => '',
			'aria-label' => '',
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
			'key' => 'field_6449093d01022',
			'label' => 'Pagina tutti gli eventi',
			'name' => 'eventi_page',
			'aria-label' => '',
			'type' => 'page_link',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'post_type' => '',
			'taxonomy' => '',
			'allow_null' => 0,
			'allow_archives' => 1,
			'multiple' => 0,
			'wpml_cf_preferences' => 1,
		),
		array(
			'key' => 'field_644a56c492696',
			'label' => 'Varie',
			'name' => '',
			'aria-label' => '',
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
			'key' => 'field_649e8e4f0da49',
			'label' => 'Immagine placeholder per gli spettacoli',
			'name' => 'show_placeholder',
			'aria-label' => '',
			'type' => 'image',
			'instructions' => 'Inserire un\'immagine di riferimento per gli spettacoli senza un\'immagine in evidenza particolare (può essere foto relativa alla stagione in corso)',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '30',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 1,
			'uploader' => '',
			'return_format' => 'url',
			'preview_size' => 'medium',
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
		),
		array(
			'key' => 'field_644a56d292697',
			'label' => 'Immagine placeholder per casting',
			'name' => 'cast_placeholder',
			'aria-label' => '',
			'type' => 'image',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '30',
				'class' => '',
				'id' => '',
			),
			'uploader' => '',
			'return_format' => 'url',
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
			'wpml_cf_preferences' => 1,
		),
		array(
			'key' => 'field_6454a647d876f',
			'label' => 'Immagine placeholder per i titoli di pagina',
			'name' => 'title_placeholder',
			'aria-label' => '',
			'type' => 'image',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '30',
				'class' => '',
				'id' => '',
			),
			'uploader' => '',
			'return_format' => 'url',
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
			'wpml_cf_preferences' => 1,
		),
		array(
			'key' => 'field_64553f20e40f4',
			'label' => 'Immagine placeholder per gli articoli',
			'name' => 'post_placeholder',
			'aria-label' => '',
			'type' => 'image',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '30',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 1,
			'uploader' => '',
			'return_format' => 'url',
			'preview_size' => 'medium',
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
		),
		array(
			'key' => 'field_6733741462151',
			'label' => 'Istruzioni Conversione Abbonamento',
			'name' => 'istruzioni_abbonamento',
			'aria-label' => '',
			'type' => 'file',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'wpml_cf_preferences' => 2,
			'uploader' => '',
			'return_format' => 'url',
			'acfe_settings' => '',
			'acfe_validate' => '',
			'min_size' => '',
			'max_size' => '',
			'mime_types' => '',
			'acfe_permissions' => '',
			'library' => 'all',
		),
		array(
			'key' => 'field_646f37149bf50',
			'label' => 'Titoli pagine varie',
			'name' => '',
			'aria-label' => '',
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
			'key' => 'field_646f372a9bf51',
			'label' => 'Titolo pagina Spettacoli ed Eventi',
			'name' => 'titolo_pagina_spettacoli_ed_eventi',
			'aria-label' => '',
			'type' => 'textarea',
			'instructions' => 'inserisci il tag <br> per andare accapo',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 2,
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
			'key' => 'field_632d708ec35f2',
			'label' => 'Header & Footer Scripts',
			'name' => '',
			'aria-label' => '',
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
			'key' => 'field_632d70a9c35f3',
			'label' => 'Head scripts',
			'name' => 'head_scripts',
			'aria-label' => '',
			'type' => 'acfe_code_editor',
			'instructions' => 'Inserisci qui il codice da immettere dentro i tag "head"',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 0,
			'default_value' => '',
			'placeholder' => '',
			'mode' => 'text/html',
			'lines' => 1,
			'indent_unit' => 4,
			'maxlength' => '',
			'rows' => 4,
			'max_rows' => '',
			'return_format' => array(
			),
			'acfe_settings' => '',
			'acfe_validate' => '',
		),
		array(
			'key' => 'field_632d711ec35f4',
			'label' => 'Body scripts (start)',
			'name' => 'body_start_scripts',
			'aria-label' => '',
			'type' => 'acfe_code_editor',
			'instructions' => 'Inserisci qui il codice da immettere subito dopo il tag "body"',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 0,
			'default_value' => '',
			'placeholder' => '',
			'mode' => 'text/html',
			'lines' => 1,
			'indent_unit' => 4,
			'maxlength' => '',
			'rows' => 4,
			'max_rows' => '',
			'return_format' => array(
			),
			'acfe_settings' => '',
			'acfe_validate' => '',
		),
		array(
			'key' => 'field_632d715fc35f5',
			'label' => 'Body scripts (end)',
			'name' => 'body_end_scripts',
			'aria-label' => '',
			'type' => 'acfe_code_editor',
			'instructions' => 'Inserisci qui il codice da immettere subito prima del tag " /body"',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'wpml_cf_preferences' => 0,
			'default_value' => '',
			'placeholder' => '',
			'mode' => 'text/html',
			'lines' => 1,
			'indent_unit' => 4,
			'maxlength' => '',
			'rows' => 4,
			'max_rows' => '',
			'return_format' => array(
			),
			'acfe_settings' => '',
			'acfe_validate' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'opzioni-tema',
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
	'show_in_rest' => 1,
	'acfe_display_title' => '',
	'acfe_autosync' => array(
		0 => 'php',
	),
	'acfe_permissions' => '',
	'acfml_field_group_mode' => 'advanced',
	'acfe_form' => 1,
	'acfe_meta' => '',
	'acfe_note' => '',
	'acfe_categories' => array(
		'general' => 'General',
	),
	'modified' => 1731425422,
));

endif;