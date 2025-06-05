<?php
  class all_terms
  {
	  public function __construct()
	  {
		  $version = '2';
		  $namespace = 'wp/v' . $version;
		  $base = 'all-terms';
		  register_rest_route($namespace, '/' . $base, array(
			  'methods' => 'GET',
			  'callback' => array($this, 'get_all_terms'),
			  'permission_callback' => '__return_true',
		  ));
	  }

	  public function get_all_terms($object)
	  {
		  $return = array();
		  // $return['categories'] = get_terms('category');
   //        $return['tags'] = get_terms('post_tag');
		  // Get taxonomies
		  $args = array(
			  'public' => true,
			  '_builtin' => false,
		  );
		  $output = 'names'; // or objects
		  $operator = 'and'; // 'and' or 'or'
		  $taxonomies = get_taxonomies($args, $output, $operator);
		  foreach ($taxonomies as $key => $taxonomy_name) {
			  if($taxonomy_name = $_GET['term']){
				  $return = get_terms($taxonomy_name);
			  }
		  }

		  $result = new WP_REST_Response($return, 200);
		//   $result->set_headers(array('Cache-Control' => 'max-age=3600'));
		  return $result;
	  }
  }


  class Array_Events_By_Datetime
  {
	  public function __construct()
	  {
		  $version = '2';
		  $namespace = 'wp/v' . $version;
		  $base = 'events-datetime';
		  register_rest_route($namespace, '/' . $base, array(
			  'methods' => 'GET',
			  'callback' => array($this, 'get_events_by_datetime'),
			  'permission_callback' => '__return_true',
		  ));
	  }

	  public function get_events_by_datetime($object)
	  {
			$args = array(
				'public' => true,
				'_builtin' => false,
				'post_status' => 'publish',
				'post_type' => 'spettacoli',
				'numberposts' => -1,
				'suppress_filters' => false,
			);
			$posts = get_posts($args);
			$pair = array();
			$earr = array();
			$past = array();
			$manual = array();

			foreach ($posts as $post) {
				$prodotto_id = is_plugin_active('advanced-custom-fields-pro/acf.php') && function_exists('get_field') ? get_field('prodotto_relazionato', $post) : '';
				$spettacolo_data = is_plugin_active('stc-tickets/stc-tickets.php') && function_exists('stcticket_spettacolo_data') ? stcticket_spettacolo_data($prodotto_id) : [];

				$location = '';
				$fetured_image = get_the_post_thumbnail_url( $post->ID, 'large' );
				$fetured_vertical = is_plugin_active('advanced-custom-fields-pro/acf.php') ? get_field( 'immagine_verticale', $post->ID ) : '';

				$cats = '';
				$terms = get_the_terms( $post->ID, 'categoria-spettacoli' );
				if ( $terms && ! is_wp_error( $terms ) ) {
					$cats = join( ", ", array_map( function( $t ) { return $t->name; }, $terms ) );
				}
				// Adding past dates to the array if any
				$past_dates = get_post_meta($post->ID, 'spettacolo_date', true);
				if (isset($past_dates) && is_array($past_dates) && !empty($past_dates)) {
					foreach ($past_dates as $key => $value) {
						// Check if value and post_id match
						$id_evento = key($value);
						if ((int)$id_evento !== $post->ID) continue;
						
						$evento = isset($value[$post->ID]) ? $value[$post->ID] : null;
						// Check if the event exists
						if ($evento == null) return;
						$location = $evento['location'];
						$data = date('Y/m/d', strtotime($key));
						$past['date'][$data][$post->ID]['ID'] = $post->ID;
						$past['date'][$data][$post->ID]['titolo'] = get_the_title( $post );
						$past['date'][$data][$post->ID]['cat'] = $cats;
						$past['date'][$data][$post->ID]['permalink'] = get_permalink( $post );
						$past['date'][$data][$post->ID]['featured_image'] = get_the_post_thumbnail_url( $post, 'large' );
						$past['date'][$data][$post->ID]['featured_vertical'] = is_plugin_active('advanced-custom-fields-pro/acf.php') ? get_field( 'immagine_verticale', $post ) : '';
						$past['date'][$data][$post->ID]['data'] = $evento['data'];
						$past['date'][$data][$post->ID]['orario'] = $evento['orario'];
						$past['date'][$data][$post->ID]['location'] = $evento['location'];
						$past['date'][$data][$post->ID]['ticket_link'] = $evento['ticket_link'];
					}
				}

				// Add manual past dates from date_passate ACF field
				$manual_past_dates = get_post_meta($post->ID, 'date_passate_cal', true);
				if (isset($manual_past_dates) && is_array($manual_past_dates) && !empty($manual_past_dates)) {
					foreach ($manual_past_dates as $key => $value) {
						// Check if the event exists
						if ((int)$key !== $post->ID) return;

						if( isset($value['date']) && is_array($value['date'])){
							foreach ($value['date'] as $data) {
								$data_array = explode(' ', $data);
								$data_manual = date('Y/m/d', strtotime($data_array[0]));
								$dataa = str_replace('-', '/', $data_array[0]); // 16/09/2023

								$past['date'][$data_manual][$post->ID]['ID'] = $post->ID;
								$past['date'][$data_manual][$post->ID]['titolo'] = get_the_title( $post );
								$past['date'][$data_manual][$post->ID]['cat'] = $cats;
								$past['date'][$data_manual][$post->ID]['permalink'] = get_permalink( $post );
								$past['date'][$data_manual][$post->ID]['featured_image'] = $fetured_image;
								$past['date'][$data_manual][$post->ID]['featured_vertical'] = $fetured_vertical;
								$past['date'][$data_manual][$post->ID]['data'] = $dataa;
								$past['date'][$data_manual][$post->ID]['dataa'] = $data_manual;
								$past['date'][$data_manual][$post->ID]['orario'] = isset($data_array[1]) ? $data_array[1] : '';
								$past['date'][$data_manual][$post->ID]['location'] = $location;
								$past['date'][$data_manual][$post->ID]['ticket_link'] = '';
							}
						}
					}
				}

				if (isset($spettacolo_data['date']) && is_array($spettacolo_data['date'])) :
				foreach ($spettacolo_data['date'] as $dettaglio) {
					$data_ora_array = explode(' ', $dettaglio['date']);
					$data = str_replace('-', '/', $data_ora_array[0]); // 16/09/2023
					$datadef = date('Y/m/d', strtotime($data_ora_array[0]));
					$ora = $data_ora_array[1]; // 19:30

					$pair['date'][$datadef][$post->ID]['ID'] = $post->ID;
					$pair['date'][$datadef][$post->ID]['titolo'] = get_the_title( $post );
					$pair['date'][$datadef][$post->ID]['cat'] = $cats;
					$pair['date'][$datadef][$post->ID]['permalink'] = get_permalink( $post );
					$pair['date'][$datadef][$post->ID]['featured_image'] = get_the_post_thumbnail_url( $post, 'large' );
					$pair['date'][$datadef][$post->ID]['featured_vertical'] = is_plugin_active('advanced-custom-fields-pro/acf.php') ? get_field( 'immagine_verticale', $post ) : '';
					$pair['date'][$datadef][$post->ID]['data'] = $data;
					$pair['date'][$datadef][$post->ID]['dataa'] = $datadef;
					$pair['date'][$datadef][$post->ID]['orario'] = $ora;
					$pair['date'][$datadef][$post->ID]['location'] = $spettacolo_data['location'];
					$pair['date'][$datadef][$post->ID]['ticket_link'] = $dettaglio['url'];
				}
				endif;
			}

			// merge past and future events and sort by date
			$output = array_combine(array_keys($pair), array_map(function($a, $b) {
				return array_merge($a, $b);
			}, $pair, $past));
			if(is_array($output) && isset($output['date']))
				ksort($output['date']);

			$result = new WP_REST_Response($output, 200);
		  	return $result;
	  }

  }

  class Array_Spettacoli
  {
	  public function __construct()
	  {
		  $version = '2';
		  $namespace = 'wp/v' . $version;
		  $base = 'spettacoli';
		  register_rest_route($namespace, '/' . $base, array(
			  'methods' => 'GET',
			  'callback' => array($this, 'get_spettacoli'),
			  'permission_callback' => '__return_true',
		  ));
	  }

	  public function get_spettacoli($object)
	  {
		$ids 	  = $object->get_param( 'id' );
		$cat 	  = $object->get_param( 'categoria_spettacoli' );
		$per_page = $object->get_param( 'per_page' );
		$page 	  = $object->get_param( 'page' );
		$date 	  = $object->get_param( 'data_inizio' ) ? $object->get_param( 'data_inizio' ) : date('Y') . '0101';

		//data 1 gennaio di quest'anno
		// $today = date('Y') . '0101';

		$tax_query = array();
		if (isset($cat)) {
			$tax_query[] = array(
				'taxonomy' => 'categoria-spettacoli',
				'field' => 'term_id',
				'terms' => $cat,
				'operator'   => 'IN',
			);
			$tax_query['relation'] = 'AND';
		}

		$args = array(
			'post_type' => 'spettacoli',
			'post_status' => 'publish',
			'numberposts' => -1,
			'suppress_filters' => false,
			'meta_query' => array(
				'data_inizio__order_by' => array(
					'key' => 'data_inizio',
					'value' => date('Ymd', strtotime($date)),
					'compare' => '>=',
					'type' => 'DATE',
				),
				'relation' => 'AND',
				'data_fine__order_by' => array(
					'key' => 'data_fine',
					'value' => date('Ymd', strtotime('now')),
					'compare' => '>=',
					'type' => 'DATE',
				)
			),
			'orderby' => array( 'data_inizio__order_by' => 'ASC' ),
			'tax_query' => $tax_query
		);

		if (isset($ids)) {
			$ids = explode(',', $ids);
			$args['post__in'] = $ids;
		}

		$query = get_posts($args);

		$output = array();

		foreach ($query as $post) {
				// Aggiungo i capi acf
				$acf_fields = get_fields($post->ID);
				$id = $post->ID;
				$cats = array();
				$terms = get_the_terms( $id, 'categoria-spettacoli' );
				foreach($terms as $term) {
					$cats[] = $term->term_id;
				}

				$output[] = (object) [
					'id' => $id,
					'slug' => basename(get_permalink($id)),
					'link' => get_permalink($id),
					'post_status' => $post->post_status,
					'type' => $post->post_type,
					'featured_media_url' => get_the_post_thumbnail_url($id, 'full'),
					// featured media id
					'featured_media' => get_post_thumbnail_id($id),
					'title' => [
						'rendered' => get_the_title($id)
					],
					'excerpt' => get_the_excerpt($id),
					'categoria-spettacoli' => $cats,
					'acf' => $acf_fields,
				];
			}

			$page = $page ? (int) $page : 1;
			$total = count( $output ); //total items in array
			$limit = $per_page ? (int) $per_page : 12; //per page
			$totalPages = ceil( $total/ $limit ); //calculate total pages
			$page = max($page, 1); //get 1 page when $_GET['page'] <= 0
			$page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
			$offset = ($page - 1) * $limit;
			if( $offset < 0 ) $offset = 0;

			$output = array_slice( $output, $offset, $limit );
			$data = new WP_REST_Response( $output, 200 );
			$data->set_headers(array(
				'X-WP-Total' => $total,
				'X-WP-TotalPages' => $totalPages,
				'Cache-Control' => 'max-age=3600'
			));

			return $data;
	  }

  }

  class Array_Vivaticket_Events
  {
	  public function __construct()
	  {
		  $version = '2';
		  $namespace = 'wp/v' . $version;
		  $base = 'vivaticket-events';
		  register_rest_route($namespace, '/' . $base, array(
			  'methods' => 'GET',
			  'callback' => array($this, 'vivaticket_events'),
			  'permission_callback' => '__return_true',
		  ));
	  }

	  public function vivaticket_events($object)
	  {
			$args = array(
				'public' => true,
				'_builtin' => false,
				'post_status' => 'publish',
				'post_type' => 'spettacoli',
				'numberposts' => -1,
				'suppress_filters' => false,
			);
			$posts = get_posts($args);
			$eventi = array();

			foreach ($posts as $post) {
				$spettacolo_id = is_plugin_active('advanced-custom-fields-pro/acf.php') ? get_field('prodotto_relazionato', $post) : '';
				$spettacolo_data = is_plugin_active('stc-tickets/stc-tickets.php') ? stcticket_spettacolo_data($spettacolo_id) : [];
				$day_field = is_array($spettacolo_data['date']) ? $spettacolo_data['date'] : '';
				$options = array();

				if (isset($day_field) && is_array($day_field) && !empty($day_field)) {
					foreach ($day_field as $row) {
						$dett_array = explode(' ', $row['date']);
						$data = wp_date('j F Y', strtotime($dett_array[0]));
						$options[$row['date']] = $data.' h'.$dett_array[1];
					}
				}

				$evento_date = array();

				$evento_date['ID'] = $post->ID;
				$evento_date['titolo'] = get_the_title( $post );
				$evento_date['permalink'] = get_permalink( $post );
				$evento_date['featured_image'] = get_the_post_thumbnail_url( $post, 'medium' );
				$evento_date['featured_vertical'] = is_plugin_active('advanced-custom-fields-pro/acf.php') ? get_field( 'immagine_verticale', $post ) : '';
				$evento_date['date'] = $options;
				$evento_date['location'] = $spettacolo_data['location'];

				$eventi[] = $evento_date;
			}

			$result = new WP_REST_Response($eventi, 200);
			$result->set_headers(array('Cache-Control' => 'max-age=3600'));
		  	return $result;
	  }

  }

  /**
   * Get all published events in english in date order
   *
   * @param array $object
   * @return WP_REST_Response
   */
  class Get_Events_En
  {
	public function __construct()
	  {
		  $version = '2';
		  $namespace = 'wp/v' . $version;
		  $base = 'events_en';
		  register_rest_route($namespace, '/' . $base, array(
			  'methods' => 'GET',
			  'callback' => array($this, 'events_en'),
			  'permission_callback' => '__return_true',
		  ));
	  }

	public function events_en($object) {
		$cat = $object->get_param( 'categoria_spettacoli' );
		$per_page = $object->get_param( 'per_page' );
		$page = $object->get_param( 'page' );
		$date = $object->get_param( 'data_inizio' ) ? $object->get_param( 'data_inizio' ) : 'now';

		// $array = json_decode(do_shortcode( '[events_en categoria_spettacoli="'.$cat.'" data_inizio="'.$date.'"]' ));
		$tax_query = array();
		if (isset($cat)) {
			$tax_query[] = array(
				'taxonomy' => 'categoria-spettacoli',
				'field' => 'term_id',
				'terms' => $cat,
				'operator'   => 'IN',
			);
			$tax_query['relation'] = 'AND';
		}

		$args = array(
			'post_type' => 'spettacoli',
			'post_status' => 'publish',
			'numberposts' => -1,
			'suppress_filters' => false,
			'meta_query' => array(
				'data_inizio__order_by' => array(
					'key' => 'data_inizio',
					'value' => date('Ymd', strtotime($date)),
					'compare' => '>=',
					'type' => 'DATE',
				)
			),
			'orderby' => array( 'data_inizio__order_by' => 'ASC' ),
			'tax_query' => $tax_query
		);

		$query = get_posts($args);

		$output = array();

		foreach ($query as $post) {
			$id = $post->ID;
			$cats = array();
			$terms = get_the_terms( $id, 'categoria-spettacoli' );
			foreach($terms as $term) {
				$cats[] = $term->term_id;
			}

			$eng_id = apply_filters( 'wpml_object_id', $id, 'spettacoli', FALSE, 'en' );

			if (! is_null($eng_id)) {
				$eng_post = get_post($eng_id);
				$output[] = (object) [
					'id' => $id,
					'eng_id' => $eng_id,
					'slug' => $eng_post->post_name,
					'link' => get_permalink($eng_id),
					'title' => [
						'rendered' => get_the_title($eng_id)
					],
					'excerpt' => get_the_excerpt( $eng_id ),
					'categoria-spettacoli' => $cats,
					'acf' => [
						'data_inizio' => is_plugin_active('advanced-custom-fields-pro/acf.php') ? get_field('data_inizio', $id) : '',
						'data_fine' => is_plugin_active('advanced-custom-fields-pro/acf.php') ? get_field('data_fine', $id) : '',
						'immagine_verticale' => is_plugin_active('advanced-custom-fields-pro/acf.php') ? get_field('immagine_verticale', $id) : '',
					],
				];
			}
		}

		$page = $page ? (int) $page : 1;
		$total = count( $output ); //total items in array
		$limit = $per_page ? (int) $per_page : 12; //per page
		$totalPages = ceil( $total/ $limit ); //calculate total pages
		$page = max($page, 1); //get 1 page when $_GET['page'] <= 0
		$page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
		$offset = ($page - 1) * $limit;
		if( $offset < 0 ) $offset = 0;

		$output = array_slice( $output, $offset, $limit );
        // aggiungo gli headers
		$data = new WP_REST_Response( $output, 200 );
		$data->set_headers(array(
			'X-WP-Total' => $total,
			'X-WP-TotalPages' => $totalPages,
			'Cache-Control' => 'max-age=3600'
		));

        return $data;
	  }
  }

  /**
   * Get all published events which are cancelled
   *
   * @param array $object
   * @return WP_REST_Response
   */
  class Get_Annullati {
	public function __construct()
	  {
		  $version = '2';
		  $namespace = 'wp/v' . $version;
		  $base = 'annullati';
		  register_rest_route($namespace, '/' . $base, array(
			  'methods' => 'GET',
			  'callback' => array($this, 'annullati'),
			  'permission_callback' => '__return_true',
		  ));
	  }

	public function annullati($object) {
		$per_page = $object->get_param( 'per_page' );
		$page = $object->get_param( 'page' );
		$date = $object->get_param( 'data_inizio' ) ? $object->get_param( 'data_inizio' ) : 'now';

		$args = array(
			'post_type' => 'spettacoli',
			'post_status' => 'publish',
			'numberposts' => -1,
			'suppress_filters' => false,
			'meta_query' => array(
				'annullato' => array(
					'key' => 'annullato',
					'value' => '1',
					'compare' => '=',
				)
			),
			'orderby' => array( 'data_inizio__order_by' => 'ASC' ),
		);

		$query = get_posts($args);
		$output = array();
		foreach ($query as $post) {
			// Aggiungo i capi acf
			$acf_fields = get_fields($post->ID);
			$id = $post->ID;

			$output[] = (object) [
				'id' => $id,
				'slug' => basename(get_permalink($id)),
				'link' => get_permalink($id),
				'post_status' => $post->post_status,
				'type' => $post->post_type,
				'featured_media_url' => get_the_post_thumbnail_url($id, 'full'),
				// featured media id
				'featured_media' => get_post_thumbnail_id($id),
				'title' => [
					'rendered' => get_the_title($id)
				],
				'excerpt' => get_the_excerpt($id),
				'acf' => $acf_fields,
			];
		}

		return new WP_REST_Response( $output, 200 );
	  }
  }

  /**
   * Get all published events despite the date
   *
   * @param array $object
   * @return WP_REST_Response
   */
  class Get_All_Events {
	public function __construct()
	  {
		  $version = '2';
		  $namespace = 'wp/v' . $version;
		  $base = 'all-events';
		  register_rest_route($namespace, '/' . $base, array(
			  'methods' => 'GET',
			  'callback' => array($this, 'all_events'),
			  'permission_callback' => '__return_true',
		  ));
	  }

	public function all_events($object) {
		$args = array(
			'post_type' => 'spettacoli',
			'post_status' => 'publish',
			'numberposts' => -1,
			'suppress_filters' => false,
			'orderby' => array( 'data_inizio__order_by' => 'ASC' ),
		);

		$query = get_posts($args);
		$output = array();
		foreach ($query as $post) {
			// Aggiungo i capi acf
			$acf_fields = get_fields($post->ID);
			$id = $post->ID;

			$output[] = (object) [
				'id' => $id,
				'slug' => basename(get_permalink($id)),
				'link' => get_permalink($id),
				'post_status' => $post->post_status,
				'type' => $post->post_type,
				'featured_media_url' => get_the_post_thumbnail_url($id, 'full'),
				// featured media id
				'featured_media' => get_post_thumbnail_id($id),
				'title' => [
					'rendered' => get_the_title($id)
				],
				'excerpt' => get_the_excerpt($id),
				'acf' => $acf_fields,
			];
		}

		if (null !== ($object->get_param('id'))) {
			$ids = explode(',', $object->get_param('id'));
			$output = array_filter($output, function($item) use ($ids) {
				return in_array($item->id, $ids);
			});
		}

		return new WP_REST_Response( $output, 200 );
	  }
  }


  add_action('rest_api_init', function () {
	  $all_terms = new all_terms;
	  $events_datetime = new Array_Events_By_Datetime;
	  $vivaticket_events = new Array_Vivaticket_Events;
	  $events_en = new Get_Events_En;
	  $spettacoli = new Array_Spettacoli;
	  $annullati = new Get_Annullati;
	  $all_events = new Get_All_Events;
  });


   /**
	 * Filter spettacoli post type by id
	 *
	 * @param array $args
	 * @param WP_Rest_Rquest $request
	 * @return array $args
	 */
	function filter_rest_spettacoli_query( $args, $request ) {
		$params = $request->get_params();
		if(isset($params['id'])){
			$args['post__in'] = explode(',', $params['id']);
		}
		return $args;
	}
	// add the filter
	add_filter( "rest_spettacoli_query", 'filter_rest_spettacoli_query', 10, 2 );
