{{--
  Template Name: Educational
--}}
@set($titolo, get_field('title') ? get_field('title') : get_the_title())
@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())

  <div class="article-title" style="background-image: linear-gradient(-90deg,rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.5) 140%), url( {{ get_the_post_thumbnail_url( get_the_ID(), 'full' ) }} );">

	<div class="bf-title-block color-white   big ">
		<h1 class="bf-title">{!! $titolo !!}</h1>
	</div>
  </div>

  @if (get_field('is_list') == false)
  	@php(the_content())

	@if (get_field('form_prenotazione') == true)

	<div id="prenota" class="vc_row wpb_row vc_row-fluid vc_row-bf-default">

		<div class="separatore" style="margin-bottom: 10%;"></div>
		<h2>@field('titolo_form')</h2>

		<form action="" id="prenotazione_scuole">
			<div class="step">
				<div class="header">
					{{__('Teacher data', 'san-carlo-theme')}}
				</div>

				<div class="fields">
					<label for="first-name"> {{__('First Name', 'san-carlo-theme')}}*
						<input type="text" name="first-name" id="first-name" required>
					</label>

					<label for="last-name"> {{__('Last Name', 'san-carlo-theme')}}*
						<input type="text" name="last-name" id="last-name" required>
					</label>

					<label for="address"> {{__('Address, City, Country', 'san-carlo-theme')}}*
						<input type="text" name="address" id="address" required>
					</label>

					<label for="phone"> {{__('Phone (without blank spaces)', 'san-carlo-theme')}}*
						<input type="text" name="phone" id="phone" required>
					</label>

					<label for="email"> {{__('Email', 'san-carlo-theme')}}*
						<input type="email" name="email" id="email" required>
					</label>

					<label for="type"> {{__('I am', 'san-carlo-theme')}}*
						<select name="type" id="type">
							<option value="{{__('Teacher', 'san-carlo-theme')}}">{{__('Teacher', 'san-carlo-theme')}}</option>
							<option value="{{__('Parent', 'san-carlo-theme')}}">{{__('Parent', 'san-carlo-theme')}}</option>
						</select>
					</label>

					<label id="interesse"> {{__('Interested in', 'san-carlo-theme')}}
						<div>
							<label for="spettacoli-scuole">
								<input type="checkbox" name="interesse" id="spettacoli-scuole" value="{{__('School performances', 'san-carlo-theme')}}"> {{__('School performances', 'san-carlo-theme')}}
							</label>

							<label for="formazione-studenti">
								<input type="checkbox" name="interesse" id="formazione-studenti" value="{{__('Student training', 'san-carlo-theme')}}"> {{__('Student training', 'san-carlo-theme')}}
							</label>

							<label for="formazione-docenti">
								<input type="checkbox" name="interesse" id="formazione-docenti" value="{{__('Teacher training', 'san-carlo-theme')}}"> {{__('Teacher training', 'san-carlo-theme')}}
							</label>

							<label for="scuola-incanto">
								<input type="checkbox" name="interesse" id="scuola-incanto" value="{{__('School InCanto', 'san-carlo-theme')}}"> {{__('School InCanto', 'san-carlo-theme')}}
							</label>

							<label for="visite-guidate">
								<input type="checkbox" name="interesse" id="visite-guidate" value="{{__('Guided tours', 'san-carlo-theme')}}"> {{__('Guided tours', 'san-carlo-theme')}}
							</label>

							<label for="memus">
								<input type="checkbox" name="interesse" id="memus" value="{{__('MeMUS', 'san-carlo-theme')}}"> {{__('MeMUS', 'san-carlo-theme')}}
							</label>

							<label for="laboratori-bambini">
								<input type="checkbox" name="interesse" id="laboratori-bambini" value="{{__('Workshops for children', 'san-carlo-theme')}}"> {{__('Workshops for children', 'san-carlo-theme')}}
							</label>
						</div>
					</label>
				</div>
			</div>

			<div class="step">
				<div class="header">
					{{__('School data', 'san-carlo-theme')}}
				</div>

				<div class="fields">
					<label for="school-name"> {{__('School Name', 'san-carlo-theme')}}*
						<input type="text" name="school-name" id="school-name" required>
					</label>

					<label for="school-address"> {{__('Address, City, Country', 'san-carlo-theme')}}*
						<input type="text" name="school-address" id="school-address" required>
					</label>

					<label for="school-phone"> {{__('Phone (without blank spaces)', 'san-carlo-theme')}}*
						<input type="text" name="school-phone" id="school-phone" required>
					</label>

					<label for="school-email"> {{__('Email', 'san-carlo-theme')}}*
						<input type="email" name="school-email" id="school-email" required>
					</label>

					<label for="school-cf"> {{__('C.F.', 'san-carlo-theme')}}*
						<input type="text" name="school-cf" id="school-cf" required>
					</label>
				</div>
			</div>

			<div class="step">
				<div class="header">
					{{__('Show Booking', 'san-carlo-theme')}}
				</div>

				<div class="fields">
					<label for="show-name"> {{__('Show Title', 'san-carlo-theme')}}*
						<select name="show-name1" id="show-name1" v-on:change="fetchShowId(1)">
							<option value="">-</option>
							<option v-for="(show, i) in shows" :id="show.ID" :value="show.titolo">${show.titolo}</option>
						</select>
					</label>

					<label for="show-date"> {{__('Performance date', 'san-carlo-theme')}}*
						<select name="show-date1" id="show-date1">
							<option value="">-</option>
							<option v-for="(data, i) in date_1" :value="data">${data}</option>
						</select>
					</label>
				</div>

				<div class="fields">
					<label for="show-name"> {{__('Optional Alternative Show', 'san-carlo-theme')}}
						<select name="show-name2" id="show-name2" v-on:change="fetchShowId(2)">
							<option value="">-</option>
							<option v-for="(show, i) in shows" :id="show.ID" :value="show.titolo">${show.titolo}</option>
						</select>
					</label>

					<label for="show-date"> {{__('Performance date', 'san-carlo-theme')}}
						<select name="show-date2" id="show-date2">
							<option value="">-</option>
							<option v-for="(data, i) in date_2" :value="data">${data}</option>
						</select>
					</label>
				</div>

				<div class="fields">
					<label for="num-studenti-paganti"> {{__('Number of paying students (net of accompanying teachers)', 'san-carlo-theme')}}*
						<input type="text" name="num-studenti-paganti" id="num-studenti-paganti" required>
					</label>

					<label for="num-docenti"> {{__('Number of accompanying teachers (1 for every 15 paying students - any excess will be automatically included in the number of paying students)', 'san-carlo-theme')}}* <br>
						<input type="text" name="num-docenti" id="num-docenti" required>
					</label>

					<label for="num-studenti-non-paganti"> {{__('Number of NON-paying students (Accompanying Teachers with certification)', 'san-carlo-theme')}} 
						<input type="text" name="num-studenti-non-paganti" id="num-studenti-non-paganti">
					</label>

					<label for="num-docenti-sostegno"> {{__('Number of support teachers', 'san-carlo-theme')}} 
						<input type="text" name="num-docenti-sostegno" id="num-docenti-sostegno">
					</label>
				</div>
			</div>

			<div class="step">
				<div class="header">
					{{__('Envoice Information', 'san-carlo-theme')}}
				</div>

				<div class="fields">
					<label for="fattura"> {{__('Need for electronic invoice', 'san-carlo-theme')}}
						<select name="fattura" id="fattura" v-on:change="changeFattura">
							<option value="no">{{__('No', 'san-carlo-theme')}}</option>
							<option value="si">{{__('Yes', 'san-carlo-theme')}}</option>
						</select>
					</label>

					<label v-if="fattura == 'si'" for="unicode"> {{__('Unique code', 'san-carlo-theme')}}* 
						<input type="text" name="unicode" id="unicode" required>
					</label>
				</div>
			</div>

			<div class="step">
				<div class="header">
					{{__('Upload Files', 'san-carlo-theme')}}
				</div>

				<div class="fields">
					<div class="files-wrap">

						<div class="file" v-for="(file, i) in files">
							<label for="file" :class="file.class"> *{{__('Each attachment must be in JPG or PDF format and should not be larger than 4MB', 'san-carlo-theme')}}
								<input type="file" class="file-type" :id="file.id" multiple="false" data-mime_types="jpg,pdf,jpeg" max-size="4000000">
								<input type="hidden" :class="file.id" :name="file.id" value="" remove="">
							</label>
						</div>
	
						<div class="controls">
							<a class="bf-btn primary icon-file" v-on:click="addFile"><span>+</span></a>
							<a class="bf-btn primary icon-file" v-on:click="removeFile"><span>-</span></a>
						</div>
					</div>
				</div>
			</div>

			<div class="finale">
				<h3>{{__('Declaration of consent', 'san-carlo-theme')}}</h3>

				@field('privacy_text')

				<div class="privacy">
					<label for="privacy">
						<input type="checkbox" name="privacy" id="privacy" required> {{__('The subscriber hereby declares the consent to the processing of this data to the purposes declared in this Refunding Request Form.', 'san-carlo-theme')}}
					</label>
				</div>

				<div class="captcha">

				</div>

				@php(wp_nonce_field('booking_nonce', 'invia_form_prenotazione'))
				<button type="submit" class="submit bf-btn">{{__('Submit', 'san-carlo-theme')}}</button>
			</div>
		</form>

	</div>

	@endif
  @else

  <div class="vc_row wpb_row vc_row-fluid vc_row-bf-default flex fwrap pad-tb-5" style="background-color: var(--light-gray)">
	@set($all_locations, get_pages( array(
                        'post_type'         => 'page', //here's my CPT
                        'post_status'       => array( 'publish' ) //my custom choice
                    ) ))
	@set($children, get_page_children(get_the_ID(), $all_locations))

	{{-- @php(var_dump($children)) --}}
	@foreach ($children as $page)
		<div class="child">
			@set($cat, get_the_terms( $page->ID, 'categoria-pagina', '<span>', ', ', '</span>' ))

			<div class="img" style="background-image:url({{ get_the_post_thumbnail_url($page->ID) }})"></div>

			<div class="inner">
				@if ($cat)
					<span>{{ $cat[0]->name }}</span>
				@endif
				<h2>{{ $page->post_title }}</h2>
	
				<div class="cont">
					<p>@if (get_field('breve_descrizione', $page->ID)) {{get_field('breve_descrizione', $page->ID)}} @endif</p>
					<a  href="@permalink($page->ID)" class="bf-btn primary icon-only"><i class="bf-icon icon-arrow-right white"></i></a>
				</div>
			</div>
			
		</div>
	@endforeach
  </div>

  @endif
 
  @endwhile
@endsection