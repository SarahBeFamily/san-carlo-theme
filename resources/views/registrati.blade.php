{{--
  Template Name: Registrati
--}}
@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())

	<div class="template-container">
		<div class="inner-cont">
			@include('partials.content-page')
			
			<div class="form-registrazione vc_row wpb_row vc_row-fluid vc_row-bf-default">
				
				<div class="inner">
					@shortcode('[login_form]')
				</div>
					
			</div>
		</div>
	</div>

  @endwhile
@endsection