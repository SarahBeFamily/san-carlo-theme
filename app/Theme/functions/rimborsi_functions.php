<?php
/**
 * Function to send email with refund request
 *
 * @return void
 */
function invia_mail_rimborso() {
    $data = $_POST['formData'];
    $post = array();

    if(isset($data)) :
        foreach ($data as $object) {
            $obj_name = $object['name'];
            $obj_val = $object['value'];

            if (stripos($obj_name, 'file-') !== false) {
                if ($obj_val !== '') {
					$file = array();
                    // $tokens = explode('/rimborsi/', $obj_val);
                    $tokens = explode('/uploads/', $obj_val);
                    $str = trim(end($tokens));
                    $index = intval(str_replace('file-', '', $obj_name));
                    // $post['uploads'][]['path'] = WP_CONTENT_DIR . '/uploads/rimborsi/'.$str;
                    $file['path'] = WP_CONTENT_DIR . '/uploads/'.$str;
					$file['url'] = $obj_val;
					$file['src'] = $str;
					$post['uploads'][$index] = $file;
                }
            } else if (stripos($obj_name, 'fileRemove') !== false) {
                $post['file_remove'][$index] = $obj_val;
            } else if (stripos($obj_name, 'qty-') !== false) {
                $index = intval(str_replace('qty-', '', $obj_name));
                $post['tickets'][$index]['qty'] = $obj_val;
            } else if (stripos( $obj_name, 'sector-' ) !== false) {
                $index = intval(str_replace('sector-', '', $obj_name));
                $post['tickets'][$index]['sector'] = $obj_val;
            } else {
                $post[$obj_name] = $obj_val;
            }
        }
    endif;

    if ( isset($post['nonce']) && ! wp_verify_nonce( $post['nonce'], 'invia_form_rimborso' ) ) {
        exit();
    }

    if (!empty($post)) {
        // Save data to database
        $rimborso_id = wp_insert_post(array(
            'post_title' => 'Rimborso '.$post['first-name'].' '.$post['last-name'],
            'post_type' => 'rimborsi',
            'post_status' => 'draft',
        ));

        // add mail content to post meta
        foreach ($post as $key => $value) {
			if ($key == 'uploads') {
				$value = serialize($value);
			} else {
				$value = sanitize_text_field($value);
			}
            update_post_meta($rimborso_id, $key, $value);
        }

        // Recipient
        $destinatari = get_field('form_rimborsi', 'option') ? explode(',', get_field('form_rimborsi', 'option')) : array('teamweb@kidea.net');
        $to = $destinatari;
        
        // Sender 
        $from = $post['email']; 
        $fromName = $post['first-name'].' '.$post['last-name']; 
        
        // Headers
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'From: '.$fromName.' <'.$from.'>';
        // $headers[] = 'Cc: John Q Codex <jqc@wordpress.org>';
        // $headers[] = 'Cc: iluvwp@wordpress.org';


        // Email subject 
        $id = isset($rimborso_id) ? ' #'.$rimborso_id : '';
        $subject = 'Richiesta di rimborso'.$rimborso_id;
        
        // Attachment file
        $attachments = array();
		$uploads = array();

        $content = array();
        $content[] = 'Nome: '.$post['first-name'];
        $content[] = 'Cognome: '.$post['last-name'];
        $content[] = 'Email: '.$post['email'];
        $content[] = 'Indirizzo: '.$post['address'];
        $content[] = 'Tel.: '.$post['phone'];
        $content[] = 'Spettacolo: '.$post['show-name'];
        $content[] = 'Data: '.$post['show-date'];

        foreach ($post['tickets'] as $key => $value) {
            $content[] = 'Quantità biglietti: '.$value['qty'].' x '.$value['sector'];
        }

        $content[] = 'Importo: '.$post['importo'].'€';
        $content[] = 'Banca: '.$post['bank-name'];
        $content[] = 'IBAN: '.$post['iban'];

        if (isset($post['swift']))
            $content[] = 'SWIFT/BIC: '.$post['swift'];

        if (isset($post['bsb']))
            $content[] = 'BSB: '.$post['bsb'];
		
		$files = array();
        if (isset($post['uploads']) && !empty($post['uploads'])) {
			
            foreach ($post['uploads'] as $n => $file) {
				$attachments[] = $file['path'];
				$files[] = $file;
            }
        }

        $message = implode('<br>', $content);

        // Add content to post meta
        update_post_meta($rimborso_id, 'mail_content', $message);
		// Add content to post content
		wp_update_post(array(
			'ID' => $rimborso_id,
			'post_content' => $message
		));
        // File attachments
        update_post_meta($rimborso_id, 'attachments', $files);
        
        // Send email 
        $mail = wp_mail( $to, $subject, $message, $headers, $attachments );

        $response_ok = array(
            'message_ok' => __('We received your request, thank you', 'san-carlo-theme'),
            'uploads' => isset($post['file_remove']) ? $post['file_remove'] : ''
        );

        $response_ko = array(
            'message_ko' => __('Error sending email', 'san-carlo-theme'),
            'uploads' => isset($post['file_remove']) ? $post['file_remove'] : ''
        );

        // wp_send_json_success( $data, 200);

        if ($mail) {
            wp_send_json_success($response_ok, 200);
        } else {
            // if error delete rimborso post if it exists
            if(isset($rimborso_id)) {
                wp_delete_post($rimborso_id, true);
            }

            wp_send_json_error($response_ko, 002 );
        }

    } else {
        wp_send_json_error( __('Error with data', 'san-carlo-theme'), 001 );
    }
}
add_action ( 'wp_ajax_nopriv_invia_mail_rimborso', 'invia_mail_rimborso', 10 );
add_action ( 'wp_ajax_invia_mail_rimborso', 'invia_mail_rimborso', 10 );

/**
 * Show rimborso post data on backend
 * 
 * @param [type] $columns
 * @return void
 */
function add_rimborso_columns ( $columns ) {
	$columns['ID'] = 'ID';
	$columns['title'] = 'Titolo';
    $columns['email'] = 'Utente';
    $columns['show_name'] = 'Spettacolo';
    $columns['show_date'] = 'Data Spettacolo';
    $columns['attachments'] = 'Allegati';
	$columns['date'] = 'Data';
	
    return $columns;
}
add_filter ( 'manage_rimborsi_posts_columns', 'add_rimborso_columns' );

function rimborso_custom_column ( $column, $post_id ) {
    switch ( $column ) {
		case 'ID':
			echo $post_id;
			break;
        case 'email':
            $email = get_post_meta($post_id, 'email', true);
            echo $email;
            break;
        case 'show_name':
            $show_name = get_post_meta($post_id, 'show-name', true);
            echo $show_name;
            break;
        case 'show_date':
            $show_date = get_post_meta($post_id, 'show-date', true);
            echo $show_date;
            break;
        case 'attachments':
            $attachments = get_post_meta($post_id, 'attachments', true);
            if (isset($attachments) && !empty($attachments)) {
                echo 'Si';
            } else {
				echo 'No';
			}
            break;
    }
}
add_action ( 'manage_rimborsi_posts_custom_column', 'rimborso_custom_column', 10, 2 );

/**
 * Add button to export data in csv in admin columns
 * 
 * @param [type] $post_type
 * @return void
 */
function add_export_button_rimborsi($which) {
	$post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';
	if ($which == 'top' && $post_type == 'rimborsi') {
		$nonce = wp_create_nonce( 'dnonce' );

        echo "<a href='".esc_html($_SERVER['REQUEST_URI'])."&rimborsicsv=true&nonce=".$nonce."' style='float:right; margin-left:8px;' class='button button-primary'>";
        echo "Esporta in CSV";
        echo '</a>';
	}
}
add_action('manage_posts_extra_tablenav', 'add_export_button_rimborsi', 20, 1);

/**
 * Export data in csv
 * 
 * @return void
 */
function export_rimborsi() {
	// Check if export button is clicked
	if (isset($_GET['rimborsicsv']) && $_GET['rimborsicsv'] == 'true') {
		$nonce = $_GET['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'dnonce' ) ) {
			wp_die( 'Not Valid.. Download nonce..!! ' );
		}

		$rimborsi_folder = WP_CONTENT_DIR.'/uploads/rimborsi';
		$filename = 'rimborsi-'.date('Y-m-d').'.csv';
		$df = fopen('php://output', 'w');

		//Get rimborsi post from DB
		$wpdb = $GLOBALS['wpdb'];
		$rimborsi = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'rimborsi' AND post_status = 'draft'");
		$total_rows = count($rimborsi);

		// Create metadata array
		$data = array();

		foreach ($rimborsi as $rimborso) {
			$metadata = array();
			$metadata['ID'] = $rimborso->ID;
			$metadata['Nome'] = get_post_meta($rimborso->ID, 'first-name', true);
			$metadata['Cognome'] = get_post_meta($rimborso->ID, 'last-name', true);
			$metadata['Email'] = get_post_meta($rimborso->ID, 'email', true);
			$metadata['Indirizzo'] = get_post_meta($rimborso->ID, 'address', true);
			$metadata['Tel'] = get_post_meta($rimborso->ID, 'phone', true);
			$metadata['Spettacolo'] = get_post_meta($rimborso->ID, 'show-name', true);
			$metadata['Data-spettacolo'] = get_post_meta($rimborso->ID, 'show-date', true);

			$content = array();
			$content[] = 'Nome: '.get_post_meta($rimborso->ID, 'first-name', true);
			$content[] = 'Cognome: '.get_post_meta($rimborso->ID, 'last-name', true);
			$content[] = 'Email: '.get_post_meta($rimborso->ID, 'email', true);
			$content[] = 'Indirizzo: '.get_post_meta($rimborso->ID, 'address', true);
			$content[] = 'Tel.: '.get_post_meta($rimborso->ID, 'phone', true);
			$content[] = 'Spettacolo: '.get_post_meta($rimborso->ID, 'show-name', true);
			$content[] = 'Data: '.get_post_meta($rimborso->ID, 'show-date', true);

			$tickets = get_post_meta($rimborso->ID, 'tickets', true);
			$metadata_tickets = array();

			if(isset($tickets) && !empty($tickets)):
				if (is_array($tickets)) {
					$tickets = array_map(function($ticket) {
						$ticket['qty'] = isset($ticket['qty']) ? $ticket['qty'] : '';
						$ticket['sector'] = isset($ticket['sector']) ? $ticket['sector'] : '';
						return $ticket;
					}, $tickets);
				} else {
					// Check if a ( is present in the string
					// dd($tickets);
					$tickets = strpos($tickets, '(') !== false ? unserialize($tickets) : $tickets;
					$tickets = explode(';', $tickets);
					$tickets = array_map(function($ticket) {
						$ticket = explode('x', $ticket);
						$ticket = array_map(function($t) {
							return trim($t);
						}, $ticket);
						return array('qty' => $ticket[0], 'sector' => $ticket[1]);
					}, $tickets);
				}

				foreach ($tickets as $key => $value) {
					$content[] = 'Quantità biglietti: '.$value['qty'].' x '.$value['sector'];
					$metadata_tickets[] = $value['qty'].' x '.$value['sector'];
				}
			endif;
	
			$content[] = 'Importo: '.get_post_meta($rimborso->ID, 'importo', true).'€';
			$content[] = 'Banca: '.get_post_meta($rimborso->ID, 'bank-name', true);
			$content[] = 'IBAN: '.get_post_meta($rimborso->ID, 'iban', true);
			$content[] = 'SWIFT/BIC: '.get_post_meta($rimborso->ID, 'swift', true);
			$content[] = 'BSB: '.get_post_meta($rimborso->ID, 'bsb', true);

			$metadata['Biglietti'] = implode('<br>', $metadata_tickets);
			$metadata['Importo'] = get_post_meta($rimborso->ID, 'importo', true);
			$metadata['Nome-banca'] = get_post_meta($rimborso->ID, 'bank-name', true);
			$metadata['Iban'] = get_post_meta($rimborso->ID, 'iban', true);
			$metadata['Swift'] = get_post_meta($rimborso->ID, 'swift', true);
			$metadata['Bsb'] = get_post_meta($rimborso->ID, 'bsb', true);
			$metadata['Allegati'] = get_post_meta($rimborso->ID, 'attachments', true);

			$message = implode('<br>', $content);
			$metadata['Contenuto-mail'] = $message;

			$post_content = $rimborso->post_content;
			$metadata['Testo-rimborso'] = $post_content;

			// insert metadata to data
			$data[$metadata['ID']] = $metadata;
		}

		// Insert data to csv, searching for headings
		$headings = array();
		foreach ($data as $post_id => $rimborso) {
			foreach ($rimborso as $key => $value) {
				if (!in_array($key, $headings)) {
					$headings[] = $key;
				}
			}
		}

		// Insert headings to csv
		fputcsv($df, $headings, ';', '"');

		// Insert data to csv
		foreach ($data as $post_id => $rimborso) {
			$row = array();
			foreach ($headings as $heading) {
				if($heading == 'Allegati') {
					$attachments = $rimborso['Allegati'];
					$attachments = is_string($attachments) ? unserialize($attachments) : $attachments;
					if (is_array($attachments)) {
						$attachments = array_map(function($attachment) {
							$url = is_array($attachment) ? $attachment['url'] : $attachment;
						}, $attachments);
						$row[] = implode(', ', $attachments);
					} 
				} else {
					$row[] = isset($rimborso[$heading]) ? $rimborso[$heading] : '';
				}
			}
			fputcsv($df, $row, ';', '"');
		}

		fclose($df);
		rimborsi_download_send_headers($filename);
		readfile('php://output');
		exit();
	}
}
add_action('init', 'export_rimborsi');

function rimborsi_download_send_headers( $filename ) {
	// disable caching
	$now = gmdate("D, d M Y H:i:s");
	header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
	header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
	header("Last-Modified: {$now} GMT");
	
	// force download
	header("Content-Description: File Transfer");
	header("Content-Encoding: UTF-8");
	header("Content-Type: text/csv; charset=UTF-8");
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");

	// disposition / encoding on response body
	header("Content-Disposition: attachment;filename={$filename}");
	header("Content-Transfer-Encoding: binary");

}

/**
 * Add custom fields to rimborso post
 * 
 * @return void
 */
function add_custom_fields_rimborsi() {
	add_meta_box(
		'custom_fields_rimborsi',
		'Dati richiesta',
		'custom_fields_rimborsi_callback',
		'rimborsi',
		'normal',
		'high'
	);

	// add files to rimborsi post
	add_meta_box(
		'uploads_rimborsi',
		'Allegati',
		'uploads_rimborsi_callback',
		'rimborsi',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'add_custom_fields_rimborsi' );

/**
 * Show post meta fields on rimborso post
 * 
 * @param [type] $post
 * @return void
 */
function custom_fields_rimborsi_callback( $post ) {
	$first_name = get_post_meta($post->ID, 'first-name', true);
	$last_name = get_post_meta($post->ID, 'last-name', true);
	$email = get_post_meta($post->ID, 'email', true);
	$address = get_post_meta($post->ID, 'address', true);
	$phone = get_post_meta($post->ID, 'phone', true);
	$show_name = get_post_meta($post->ID, 'show-name', true);
	$show_date = get_post_meta($post->ID, 'show-date', true);
	$tickets = get_post_meta($post->ID, 'tickets', true);
	$importo = get_post_meta($post->ID, 'importo', true);
	$bank_name = get_post_meta($post->ID, 'bank-name', true);
	$iban = get_post_meta($post->ID, 'iban', true);
	$swift = get_post_meta($post->ID, 'swift', true);
	$bsb = get_post_meta($post->ID, 'bsb', true);
	$uploads = get_post_meta($post->ID, 'uploads', true);
	$mail_content = get_post_meta($post->ID, 'mail_content', true);
	$attachments = get_post_meta($post->ID, 'attachments', true);

	// test
	if (isset($_GET['print']) && $_GET['print'] == '1') {
		echo '<pre>';
		print_r(get_post_meta($post->ID));
		echo '</pre>';
	}
	?>
	<table class="form-table">
		<tr>
			<th><label for="first-name">Nome</label></th>
			<td><input type="text" id="first-name" name="first-name" value="<?php echo $first_name; ?>" ></td>
		</tr>
		<tr>
			<th><label for="last-name">Cognome</label></th>
			<td><input type="text" id="last-name" name="last-name" value="<?php echo $last_name; ?>" ></td>
		</tr>
		<tr>
			<th><label for="email">Email</label></th>
			<td><input type="text" id="email" name="email" value="<?php echo $email; ?>" ></td>
		</tr>
		<tr>
			<th><label for="address">Indirizzo</label></th>
			<td><input type="text" id="address" name="address" value="<?php echo $address; ?>" ></td>
		</tr>
		<tr>
			<th><label for="phone">Tel.</label></th>
			<td><input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" ></td>
		</tr>
		<tr>
			<th><label for="show-name">Spettacolo</label></th>
			<td><input type="text" id="show-name" name="show-name" value="<?php echo $show_name; ?>" ></td>
		</tr>
		<tr>
			<th><label for="show-date">Data Spettacolo</label></th>
			<td><input type="text" id="show-date" name="show-date" value="<?php echo $show_date; ?>" ></td>
		</tr>
		<tr>
			<th><label for="tickets">Biglietti</label></th>
			<td><input type="text" id="tickets" name="tickets" value="<?php echo $tickets; ?>"></td>
		</tr>
		<tr>
			<th><label for="importo">Importo</label></th>
			<td><input type="text" id="importo" name="importo" value="<?php echo $importo; ?>" ></td>
		</tr>
		<tr>
			<th><label for="bank-name">Banca</label></th>
			<td><input type="text" id="bank-name" name="bank-name" value="<?php echo $bank_name; ?>" ></td>
		</tr>
		<tr>
			<th><label for="iban">IBAN</label></th>
			<td><input type="text" id="iban" name="iban" value="<?php echo $iban; ?>" ></td>
		</tr>
		<tr>
			<th><label for="swift">SWIFT/BIC</label></th>
			<td><input type="text" id="swift" name="swift" value="<?php echo $swift; ?>" ></td>
		</tr>
		<tr>
			<th><label for="bsb">BSB</label></th>
			<td><input type="text" id="bsb" name="bsb" value="<?php echo $bsb; ?>" ></td>
		</tr>
		<tr>
			<th><label for="mail-content">Contenuto email</label></th>
			<td><textarea id="mail-content" name="mail-content" rows="10" cols="50"><?php echo $mail_content; ?></textarea></td>
		</tr>
	</table>
	<?php
}

/**
 * Show uploads on rimborso post
 * 
 * @param [type] $post
 */
function uploads_rimborsi_callback($post) {
	$uploads = get_post_meta($post->ID, 'attachments', true);
	?>
	<table class="form-table">
		<tr>
			<th><label for="uploads">Allegati</label></th>
			<td>
				<?php if (isset($uploads) && !empty($uploads)) : 

				if(!is_array($uploads))
					$uploads = unserialize($uploads);

					if (is_array($uploads)) :
						foreach ($uploads as $upload) : 
							if (isset($upload['url']) && $upload['url'] != '') : ?>
								<p>
									<a href="<?php echo $upload['url']; ?>" target="_blank">Download</a>
								</p>
							<?php endif; 
						endforeach;
					endif;
				endif; ?>
			</td>
			<!-- Uploads -->
			 <td>
				<label>Inserisci gli url degli allegati da aggiungere uno per riga</label><br>
				<textarea id="adduploads" name="adduploads" rows="10" cols="50"></textarea>
			</td>
		</tr>
	</table>
	<?php
}


/**
 * Save post meta fields on rimborso post
 * 
 * @param [type] $post_id
 * @return void
 */
function save_custom_fields_rimborsi( $post_id ) {
	if (array_key_exists('first-name', $_POST)) {
		update_post_meta($post_id, 'first-name', $_POST['first-name']);
	}
	if (array_key_exists('last-name', $_POST)) {
		update_post_meta($post_id, 'last-name', $_POST['last-name']);
	}
	if (array_key_exists('email', $_POST)) {
		update_post_meta($post_id, 'email', $_POST['email']);
	}
	if (array_key_exists('address', $_POST)) {
		update_post_meta($post_id, 'address', $_POST['address']);
	}
	if (array_key_exists('phone', $_POST)) {
		update_post_meta($post_id, 'phone', $_POST['phone']);
	}
	if (array_key_exists('show-name', $_POST)) {
		update_post_meta($post_id, 'show-name', $_POST['show-name']);
	}
	if (array_key_exists('show-date', $_POST)) {
		update_post_meta($post_id, 'show-date', $_POST['show-date']);
	}
	if (array_key_exists('tickets', $_POST)) {
		update_post_meta($post_id, 'tickets', $_POST['tickets']);
	}
	if (array_key_exists('importo', $_POST)) {
		update_post_meta($post_id, 'importo', $_POST['importo']);
	}
	if (array_key_exists('bank-name', $_POST)) {
		update_post_meta($post_id, 'bank-name', $_POST['bank-name']);
	}
	if (array_key_exists('iban', $_POST)) {
		update_post_meta($post_id, 'iban', $_POST['iban']);
	}
	if (array_key_exists('swift', $_POST)) {
		update_post_meta($post_id, 'swift', $_POST['swift']);
	}
	if (array_key_exists('bsb', $_POST)) {
		update_post_meta($post_id, 'bsb', $_POST['bsb']);
	}

	// textarea add uploads
	if (array_key_exists('adduploads', $_POST)) {
		$uploads = get_post_meta($post_id, 'attachments', true);
		$adduploads = $_POST['adduploads'];
		$adduploads = explode("\n", $adduploads);
		// Add key "url" to each adduploads
		$adduploads = array_map(function($upload) {
			return array('url' => trim($upload));
		}, $adduploads);

		if(!empty($uploads) && is_string($uploads)) {
			$uploads = unserialize($uploads);
		}

		if (!empty($uploads) && is_array($uploads)) {
			$uploads = array_merge($uploads, $adduploads);
		} else {
			$uploads = $adduploads;
		}

		update_post_meta($post_id, 'attachments', serialize($uploads));
	}

	if (array_key_exists('mail-content', $_POST)) {
		update_post_meta($post_id, 'mail_content', $_POST['mail-content']);
	}
}
add_action( 'save_post', 'save_custom_fields_rimborsi' );

/**
 * Delete attachments from rimborso post
 * 
 * @return void
 */
function elimina_allegati_rimborsi() {
    $delete = $_POST['uploads'];

    foreach ($delete as $file) {
        if ($file !== '') {
            $filepath = decrypt_url(wp_unslash($file));
            return is_file($filepath) ? unlink($filepath) : 'error';
        }
    }
}
add_action ( 'wp_ajax_nopriv_elimina_allegati_rimborsi', 'elimina_allegati_rimborsi', 10 );
add_action ( 'wp_ajax_elimina_allegati_rimborsi', 'elimina_allegati_rimborsi', 10 );