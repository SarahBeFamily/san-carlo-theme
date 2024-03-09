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
			  '_builtin' => false
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
		  $result->set_headers(array('Cache-Control' => 'max-age=3600'));
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
				'suppress_filters' => true,
			);
			$posts = get_posts($args);
			$eventi_arr['date'] = array();
			$pair = array();

			foreach ($posts as $post) {
				$prodotto_id = get_field('prodotto_relazionato', $post);
				$spettacolo_data = stcticket_spettacolo_data($prodotto_id);
				$evento_date = array();

				$cats = '';
				foreach (get_the_terms( $post->ID, 'categoria-spettacoli' ) as $cat) {
					if (is_object($cat))
						$cats = $cat->name;
				}

				if (is_array($spettacolo_data['date'])) :
				foreach ($spettacolo_data['date'] as $dettaglio) {
					$data_ora_array = explode(' ', $dettaglio['date']);
					$data = str_replace('-', '/', $data_ora_array[0]); // 16/09/2023
					$ora = $data_ora_array[1]; // 19:30
					$today = date('Ymd');
					$data_array = explode('/', $data);
					$data_reale = date('Ymd', strtotime($data_array[2].$data_array[1].$data_array[0]));

					if ($data_reale >= $today) {
						$evento_date[$data][$post->ID]['ID'] = $post->ID;
						$evento_date[$data][$post->ID]['titolo'] = get_the_title( $post );
						$evento_date[$data][$post->ID]['cat'] = $cats;
						$evento_date[$data][$post->ID]['permalink'] = get_permalink( $post );
						$evento_date[$data][$post->ID]['featured_image'] = get_the_post_thumbnail_url( $post, 'large' );
						$evento_date[$data][$post->ID]['featured_vertical'] = get_field( 'immagine_verticale', $post );
						$evento_date[$data][$post->ID]['data'] = $data;
						$evento_date[$data][$post->ID]['orario'] = $ora;
						$evento_date[$data][$post->ID]['location'] = $spettacolo_data['location'];
						$evento_date[$data][$post->ID]['ticket_link'] = $dettaglio['url'];
					}

					foreach ($evento_date as $data => $evento) {
						$datachange = explode('/', $data);
						$datadef = $datachange[2].'/'.$datachange[1].'/'.$datachange[0];
						$pair[$datadef] = $evento;
					}
				}

				$eventi_arr['date'] = $pair;
				ksort($eventi_arr['date']);

				endif;
			}

			$result = new WP_REST_Response($eventi_arr, 200);
			$result->set_headers(array('Cache-Control' => 'max-age=3600'));
		  	return $result;
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
				$spettacolo_data = stcticket_spettacolo_data(get_field('prodotto_relazionato', $post));
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
				$evento_date['featured_vertical'] = get_field( 'immagine_verticale', $post );
				$evento_date['date'] = $options;
				$evento_date['location'] = $spettacolo_data['location'];

				$eventi[] = $evento_date;
			}

			$result = new WP_REST_Response($eventi, 200);
			$result->set_headers(array('Cache-Control' => 'max-age=3600'));
		  	return $result;
	  }
	 
  }

  class Get_Events_En {
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
						'data_inizio' => get_field('data_inizio', $id),
						'data_fine' => get_field('data_fine', $id),
						'immagine_verticale' => get_field('immagine_verticale', $id),
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
        $data = new WP_REST_Response( $output, 200 );   
        $data->header( 'X-WP-Total', $total );   
        $data->header( 'X-WP-TotalPages', $totalPages);
		$data->set_headers(array('Cache-Control' => 'max-age=3600'));
         
        return $data;
	  }
  }
  
  add_action('rest_api_init', function () {
	  $all_terms = new all_terms;
	  $events_datetime = new Array_Events_By_Datetime;
	  $vivaticket_events = new Array_Vivaticket_Events;
	  $events_en = new Get_Events_En;
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