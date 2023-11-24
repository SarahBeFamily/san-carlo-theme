{{--
  Template Name: Form Rimborsi
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @include('partials.page-header')
    

	<div class="vc_row wpb_row vc_row-fluid vc_row-bf-default flex fwrap" style="background-color: var(--light-gray)">

		<form action="" id="rimborso">
			<div class="step">
				<div class="header">
					{{__('Customer data', 'san-carlo-theme')}}
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
				</div>
			</div>

			<div class="step">
				<div class="header">
					{{__('Show data', 'san-carlo-theme')}}
				</div>

				<div class="fields">
					<label for="show-name"> {{__('Show Title', 'san-carlo-theme')}}*
						<select name="show-name" id="show-name" :show-id="show_id" v-on:change="fetchShowId">
							<option value="">-</option>
							<option v-for="(show, i) in shows" :id="show.id" :value="show.title.rendered">${show.title.rendered}</option>
						</select>
					</label>

					<label for="show-date"> {{__('Performance date', 'san-carlo-theme')}}*
						<select name="show-date" id="show-date">
							<option value="">-</option>
							<option v-for="(data, i) in date" :value="data.data_annullata">${data.data_annullata}</option>
						</select>
					</label>

					<div class="tickets-wrap">
						<div class="ticket" v-for="(tik, i) in tickets">
							<label for="qty"> {{__('Ticket\'s quantity', 'san-carlo-theme')}}*
								<input type="text" :name="tik.qty" :id="tik.qty" required>
							</label>

							<label for="sector"> {{__('Sector', 'san-carlo-theme')}}*
								<select :name="tik.sector" :id="tik.sector">
									<option value="{{__('Top Orchestra Stalls GOLD', 'san-carlo-theme')}}">{{__('Top Orchestra Stalls GOLD', 'san-carlo-theme')}}</option>
									<option value="{{__('Top Orchestra Stalls', 'san-carlo-theme')}}">{{__('Top Orchestra Stalls', 'san-carlo-theme')}}</option>
									<option value="{{__('Orchestra Stalls GOLD', 'san-carlo-theme')}}">{{__('Orchestra Stalls GOLD', 'san-carlo-theme')}}</option>
									<option value="{{__('Orchestra Stalls', 'san-carlo-theme')}}">{{__('Orchestra Stalls', 'san-carlo-theme')}}</option>
									<option value="{{__('I, II tier central boxes parapet', 'san-carlo-theme')}}">{{__('I, II tier central boxes parapet', 'san-carlo-theme')}}</option>
									<option value="{{__('I, II tier central boxes behind', 'san-carlo-theme')}}">{{__('I, II tier central boxes behind', 'san-carlo-theme')}}</option>
									<option value="{{__('Royal Box', 'san-carlo-theme')}}">{{__('Royal Box', 'san-carlo-theme')}}</option>
									<option value="{{__('I, II tier side boxes parapet', 'san-carlo-theme')}}">{{__('I, II tier side boxes parapet', 'san-carlo-theme')}}</option>
									<option value="{{__('I, II tier side boxes behind', 'san-carlo-theme')}}">{{__('I, II tier side boxes behind', 'san-carlo-theme')}}</option>
									<option value="{{__('III, IV tier central boxes parapet', 'san-carlo-theme')}}">{{__('III, IV tier central boxes parapet', 'san-carlo-theme')}}</option>
									<option value="{{__('III, IV tier central boxes behind', 'san-carlo-theme')}}">{{__('III, IV tier central boxes behind', 'san-carlo-theme')}}</option>
									<option value="{{__('III, IV tier side boxes parapet', 'san-carlo-theme')}}">{{__('III, IV tier side boxes parapet', 'san-carlo-theme')}}</option>
									<option value="{{__('III, IV tier side boxes behind', 'san-carlo-theme')}}">{{__('III, IV tier side boxes behind', 'san-carlo-theme')}}</option>
									<option value="{{__('V, VI tier balconies', 'san-carlo-theme')}}">{{__('V, VI tier balconies', 'san-carlo-theme')}}</option>
									<option value="{{__('Listening only seats', 'san-carlo-theme')}}">{{__('Listening only seats', 'san-carlo-theme')}}</option>
								</select>
							</label>
						</div>

						<div class="controls">
							<a class="bf-btn primary icon-ticket" v-on:click="addTicket"><span>+</span></a>
							<a class="bf-btn primary icon-ticket" v-on:click="removeTicket"><span>-</span></a>
						</div>
					</div>

					{{-- <label for="importo"> {{__('Grand Total (commissions excluded)', 'san-carlo-theme')}}*
						<input type="text" name="importo" id="importo" required> <span>€</span>
					</label> --}}
				</div>
			</div>

			<div class="step">
				<div class="header">
					{{__('Bank Information', 'san-carlo-theme')}}
				</div>

				<div class="fields">
					<label for="bank-name"> {{__('Bank name', 'san-carlo-theme')}}*
						<input type="text" name="bank-name" id="bank-name" required>
					</label>

					<label for="iban"> {{__('IBAN code (without blank spaces)', 'san-carlo-theme')}}* 
						<input type="text" name="iban" id="iban" required>
					</label>

					<label for="swift"> {{__('SWIFT/BIC code (mandatory for international banktransfers)', 'san-carlo-theme')}} 
						<input type="text" name="swift" id="swift">
					</label>

					<label for="bsb"> {{__('BSB code (mandatory for international banktransfers)', 'san-carlo-theme')}} 
						<input type="text" name="bsb" id="bsb">
					</label>

					<label for="importo"> {{__('Grand Total (commissions excluded)', 'san-carlo-theme')}}*
						<input type="text" name="importo" id="importo" required> <span>€</span>
					</label>
				</div>
			</div>

			<div class="step">
				<div class="header">
					{{__('Upload Tickets', 'san-carlo-theme')}}
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

				@php(wp_nonce_field('nonce', 'invia_form_rimborso'))
				<button type="submit" class="submit bf-btn">{{__('Submit', 'san-carlo-theme')}}</button>
			</div>
		</form>

		<input type="hidden" id="refund-message-ok" name="message-ok" value="{{ __('The refund request was successfully submitted.', 'san-carlo-theme') }}">
		<input type="hidden" id="refund-message-ko" name="message-ko" value="{{ __('There was a problem submitting the form, please try again.', 'san-carlo-theme') }}">

	</div>

  @endwhile

@endsection