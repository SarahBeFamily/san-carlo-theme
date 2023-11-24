@php
	$id = get_the_ID();
	$image = get_the_post_thumbnail_url( $id, 'full' ) ? get_the_post_thumbnail_url( $id, 'full' ) : get_field('show_placeholder', 'options');
	$dateStart = get_field('data_inizio');
	$dateEnd = get_field('data_fine');

	$data_end_array = explode('/', $dateEnd);
	$data_start_array = explode('/', $dateStart);
	$start = wp_date('d M', strtotime($data_start_array[2].$data_start_array[1].$data_start_array[0]));
	$end = wp_date('d M', strtotime($data_end_array[2].$data_end_array[1].$data_end_array[0]));

	$dateStart_noY = date('d/m', strtotime($dateStart));
	$dateEnd_noY = date('d/m', strtotime($data_end_array[2].$data_end_array[1].$data_end_array[0]));
	$category = get_the_terms( $id, 'categoria-spettacoli' );

	$spettacolo_data = stcticket_spettacolo_data(get_field('prodotto_relazionato'));
	$day_field = is_array($spettacolo_data['date']) ? $spettacolo_data['date'] : '';
	$options = array();

	foreach ($category as $term) {
		$cat = $term->name;
	}

	if (isset($day_field) && is_array($day_field) && !empty($day_field)) {
		foreach ($day_field as $row) {
			$dett_array = explode(' ', $row['date']);
			$data = wp_date('j F Y', strtotime($dett_array[0]));
			/* translators: %1$s è la data e %2$s è l'orario di inizio spettacolo */
			$select_datetime = sprintf(__('%1$s at %2$s', 'san-carlo-theme'), $data, $dett_array[1]);
			$options[$row['date']]['data'] = $select_datetime;
			$options[$row['date']]['link'] = $row['url'];
		}
	}

	// echo '<pre>';
	// var_dump($options);
	// echo '</pre>';
@endphp

<article @php(post_class('h-entry'))>

	<div class="article-title" style="background-image: linear-gradient(-90deg,rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.5) 140%), url( {{ $image }} );">

		@if($dateStart == $dateEnd)
			<p class="date color-white">{{wp_date('j M Y', strtotime(str_replace('/','-',$dateStart)))}}</p>
		@else
			<p class="date color-white">{{wp_date('j M', strtotime(str_replace('/','-',$dateStart)))}} - {{wp_date('j M Y', strtotime(str_replace('/','-',$dateEnd)))}}</p>
		@endif
		<h1 class="spettacolo-title">{!! $title !!}</h1>

	</div>
  
	<div class="e-content">
		<div class="meta vc_row wpb_row vc_row-fluid vc_row-bf-default">
			<div class="inner">
				@if($start == $end)
				<span class="cat">{{$cat}}</span> <i class="bf-icon icon-calendar"></i> {{wp_date('j M Y', strtotime(str_replace('/','-',$dateStart)))}}
				@else
				{{-- translators: %1$s è la data di inizio e %2$s è la data di fine spettacolo --}}
				<span class="cat">{{$cat}}</span> <i class="bf-icon icon-calendar"></i> @php(printf(__('From %1$s to %2$s', 'san-carlo-theme'), $start, $end))
				@endif
			</div>
			<div class="separatore"></div>
		</div>

		{{-- Creo il layout --}}
		@layouts('layout')

			@layout('testo_semplice')
			<div class="row-text vc_row wpb_row vc_row-fluid vc_row-bf-default">
				@hassub('titolo')
					<h2>@sub('titolo')</h2>
				@endsub

				@hassub('testo')
					@sub('testo')
				@endsub

				@hassub('bottone_link')
					<a class="bf-link titles" href="@sub('bottone_link')" title="">{{ __('Discover more', 'san-carlo-theme') }} <i class="bf-icon right icon-arrow-right"></i></a>
				@endsub
			</div>
			@endlayout

			@layout('video')
				<div class="row-video vc_row wpb_row vc_row-fluid vc_row-bf-stretch-bg vc_row-no-padding">
					@set($link_video, get_sub_field('file_video'))
					@set($video_expl, explode(".", $link_video))
					@set($ext, strtolower(end($video_expl)))
					<video crossorigin="anonymous" width="1920">
						<source src="{{ $link_video }}" type="video/{{ $ext }}">
						{{-- Your browser does not support the video tag. --}}
					</video>
				</div>
			@endlayout

			@layout('lista_casting')
				<div class="row-casting vc_row wpb_row vc_row-fluid vc_row-bf-default flex">
					<div class="col-8">
		
						@hassub('titolo')
							<h2>@sub('titolo')</h2>
						@endsub
					</div>

					<div class="col-4">
						@hassub('descrizione_sezione')
							@sub('descrizione_sezione')
						@endsub
					</div>
				</div>

				<div class="row-casting vc_row wpb_row vc_row-fluid vc_row-bf-stretch-bg vc_row-no-padding">

					<div class="casting @sub('modalita')">
						@set($casting_els, get_sub_field('membri_cast'))
					
						@if (is_array($casting_els))
						@foreach ($casting_els as $i => $cast)
							@set($min, $i <= 3 ? 'min' : '')
							@set($lt_visible, get_sub_field('modalita') != 'slide' && $i > 3 ? ' hidden' : '')
							@set($l_visible, get_sub_field('modalita') == 'list' ? ' hidden' : '')
							@set($foto, $cast['foto'] ? $cast['foto'] : get_sub_field('cast_placeholder', 'options'))
							@set($bg_style, get_sub_field('modalita') != 'toggle' ? 'background-image:url('.$foto.')' : '')
							@set($class_toggle, get_sub_field('modalita') == 'toggle' ? ' closed' : '')
			
							{{-- @php(var_dump($cast)) --}}
							<div class="cast{{$lt_visible}} {{$min}}">
								<div class="el{{$class_toggle}}" style="{{$bg_style}}">
									<div class="inner">
										<div class="nome-wrap">
											<span class="nome">{{$cast['nome']}}</span>
											<span class="nome">{{$cast['cognome']}}</span>
										</div>

										@if ($cast['ruolo'] && get_sub_field('modalita') == 'slide')
										<span class="ruolo">{{$cast['ruolo']}}</span>
										@endif
										
										@if ($cast['ruolo'] && get_sub_field('modalita') == 'toggle' && !$cast['bio'])
										<div class="bio{{$l_visible}}">
											<p>{{$cast['ruolo']}}</p>
										</div>
										@endif

										@if ($cast['bio'])
										<a id="showBio" class="bf-link titles icon-arrow-right icon-arrow-right-primary " role="button" href="#">{{__('Read Bio', 'san-carlo-theme')}}</a>
										<div class="bio{{$l_visible}}">
											
											<p>
												@if ($cast['ruolo'] && get_sub_field('modalita') == 'toggle')
													<span class="ruolo">{{$cast['ruolo']}}</span><br><br>
												@endif
												{{$cast['bio']}}
											</p>
											@if (is_array($cast['social']) && count($cast['social']) > 0)
											<div class="social-wrap">
												<span>{{ __('Follow on', 'san-carlo-theme') }}</span>
												<div class="social flex item-center">
													@foreach ($cast['social'] as $social)
													<a href="{{$social['link_social']}}"><i class="bf-icon primary {{$social['social']}}"></i></a>
													@endforeach
												</div>
											</div>
											@endif
										</div>
										@endif
									</div>
								</div>
							</div>
						@endforeach
			
						@if (count($casting_els) > 4 && get_sub_field('modalita') != 'slide')
							<div class="show-cast">
								<a id="showMoreCast" class="bf-link pointer titles close" role="button">{!! __('Show all Cast', 'san-carlo-theme') !!} <i class="bf-icon icon-chevron-down"></i> </a>
							</div>
						@endif
						@endif
					</div>
			
				</div>
			@endlayout

			@layout('gallery')
			<div class="row-gallery vc_row wpb_row vc_row-fluid vc_row-bf-stretch-bg vc_row-no-padding">
				@hassub('photogallery')
					<div class="bf-carousel -immagini">
						@set($imgs, get_sub_field('photogallery'))

						@foreach ($imgs as $img)
							
							<div class="bf-carousel-single-slide -immagini">
								<div style="background-image: url({{ $img }});">
									<img src="{{$img}}" alt="" width="200" height="400">
								</div>
							</div>
						@endforeach
					</div>
				@endsub
			</div>
			@endlayout

			@layout('testo_incolonnato_a_dx')
				<div class="row-text-col vc_row wpb_row vc_row-fluid vc_row-bf-default">

					@issub('separatore', true)
						<div class="separatore"></div>
					@endsub

					<div class="flex fwrap">
						<div class="col-6"></div>
						<div class="col-6">
							@sub('testo')
						</div>
					</div>
				</div>
			@endlayout

		@endlayouts


		<div class="row-text-col vc_row wpb_row vc_row-fluid vc_row-bf-default pad-t-5">
			
			<div class="flex fwrap">
				<div class="col-6">
					@if (!empty($options))
					<div class="choose-event">
						<select name="date_evento" id="date_evento">
							<option value="">{{ __('Choose a date', 'san-carlo-theme') }}</option>
							@foreach ($options as $data => $dettagli)
							<option value="{{$dettagli['link']}}">{{$dettagli['data']}}</option>
							@endforeach
						</select>
						<a id="buyTicket" class="bf-btn primary icon-ticket" href="#">{{ __('Buy tickets', 'san-carlo-theme') }}</a>
					</div>
					@endif
				</div>
				<div class="col-6"></div>
			</div>

			<div class="pad-t-5">
				@set($lang_obj, apply_filters( 'wpml_object_id', get_the_ID(), 'spettacoli' ))

				@set($tariffe_tab, get_field('tariffe'))
				@set($tariffe_eng, get_field('tariffe', $lang_obj))

				{{-- @php(var_dump($tariffe_tab)) --}}

				@if( is_array($tariffe_tab) && !empty($tariffe_tab) )

					@foreach($tariffe_tab as $tabella)

					{{-- <pre>
						@php(var_dump($tabella))
					</pre> --}}
					
						@if( is_array($tabella) && !empty($tabella) )
						
							@set($array_prezzi, array())
							@set($posti, array())
							@set($tariffe, $tabella['tariffe_tab'])
							<pre>{{var_dump($tariffe)}}</pre>
							<table class="acf-dynamic-table no-more-tables">
								@foreach($tariffe as $i => $fee)
									
								{{-- @php(var_dump($fee['posto']['label'])) --}}
									@set($tipo_fee, $fee['tipo_tariffa'])
									@set($tipo_prezzo, ICL_LANGUAGE_CODE == 'en' ? $fee['tipo_prezzo']['value'] : $fee['tipo_prezzo']['label'])
									@set($posto, ICL_LANGUAGE_CODE == 'en' ? $fee['posto']['value'] : $fee['posto']['label'])
									@set($prezzo, $fee['prezzo'])
									{{-- @php($posti[$posto] = array('label' => $fee['posto']['label'], 'value' => $fee['posto']['value'])) --}}
									@php($array_prezzi[$posto][$tipo_fee.' '.$tipo_prezzo] = $prezzo)
								@endforeach

								{{-- costruisco la tabella responsive --}}
								@if(!empty($array_prezzi))

									
									{{-- <pre>{{var_dump($array_prezzi)}}</pre> --}}
								
									@set($count, 0)
									<thead>
										<tr>
											<td>{{__('Fees', 'san-carlo-theme')}}</td>
											@foreach($array_prezzi[array_key_first($array_prezzi)] as $tariffa => $prezzo)
												@php($count++)
												@set($tariffa_html, preg_replace('/\ /', '<br />', $tariffa, 1))
												<td id="{{$count}}" class="{{$tariffa}}">{!! $tariffa_html !!}</td>
											@endforeach
										</tr>
									</thead>
									<tbody class="list">
										@foreach($array_prezzi as $posto => $tariffa)
										<tr>
											<td class="posto" data-title="{{__('Fees', 'san-carlo-theme')}}">{{$posto}}</td>
											@foreach($tariffa as $tipo => $prezzo)
												<td class="prezzo {{str_replace(' ','-', sanitize_text_field($tipo))}}" data-title="{{$tipo}}">{{$prezzo}}</td>
											@endforeach
										</tr>
										@endforeach
									</tbody>
								@endif
							</table>
						@endif
						<div class="separatore"></div>
					@endforeach
				@endif

				<div class="note">
					@field('note')
				</div>
				
			</div>

			<div class="text">
				@option('testi_modalita_dacquisto')
				{{-- <div class="col-6"></div>
				<div class="col-6">
					
				</div> --}}
			</div>

			@shortcode('[events_by_datetime]')
			
		</div>

		@include('sections.row-buy')
	</div>
  
</article>