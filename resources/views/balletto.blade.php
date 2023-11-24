{{--
  Template Name: Balletto / Coro
--}}
@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())

  @include('partials.page-header')

  <div class="vc_row wpb_row vc_row-fluid vc_row-bf-default flex fwrap pad-tb-5">

	@hasfields('membri_staff')
	<div class="bf-col-3">
		@fields('membri_staff')
            <div class="staff">
				<span class="ruolo">@sub('ruolo')</span>
				<span class="nome">@sub('nome_cognome')</span>
			</div>
        @endfields
	</div>
	@endhasfields

	@hasfield('descrizione')
	<div class="bf-col-9">
		@field('descrizione')
	</div>
	@endfield

  </div>

  @hasfields('membri_cast')
	<div class="row-casting vc_row wpb_row vc_row-fluid vc_row-bf-default flex">
		<div class="bf-col-8">

			@hasfield('titolo_sezione')
				<h2>@field('titolo_sezione')</h2>
			@endfield
		</div>

		<div class="bf-col-4">
			@hasfield('descrizione_sezione')
				<p>@field('descrizione_sezione')</p>
			@endfield
		</div>
	</div>

	<div class="row-casting vc_row wpb_row vc_row-fluid vc_row-bf-stretch-bg vc_row-no-padding">

		<div class="casting @field('modalita')">
			@set($casting_els, get_field('membri_cast'))
		
			@foreach ($casting_els as $i => $cast)
				@set($min, $i <= 3 ? 'min' : '')
				@set($lt_visible, get_field('modalita') != 'slide' && $i > 3 ? ' hidden' : '')
				@set($l_visible, get_field('modalita') == 'list' ? ' hidden' : '')
				@set($foto, $cast['foto'] ? $cast['foto'] : get_field('cast_placeholder', 'options'))
				@set($bg_style, get_field('modalita') != 'toggle' ? 'background-image:url('.$foto.');' : '')
				@set($class_toggle, get_field('modalita') == 'toggle' ? ' closed' : '')

				{{-- @php(var_dump($cast)) --}}
				<div class="cast{{$lt_visible}} {{$min}}">
					<div class="el{{$class_toggle}}" style="{{$bg_style}}">
						<div class="inner">
							<div class="nome">
								<span class="nome">{{$cast['nome']}}</span>
								<span class="nome">{{$cast['cognome']}}</span>
							</div>
							
							@if ($cast['ruolo'] && get_field('modalita') == 'slide')
							<span class="ruolo">{{$cast['ruolo']}}</span>
							@endif
							
							@if ($cast['bio'])
							<a class="showBio bf-link titles icon-arrow-right icon-arrow-right-primary " role="button" data-open-text="{{__('Read Bio', 'san-carlo-theme')}}" data-close-text="{{__('Close Bio', 'san-carlo-theme')}}">{{__('Read Bio', 'san-carlo-theme')}}</a>
							<div class="bio{{$l_visible}}">
								<p>{{$cast['bio']}}</p>
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

			@if (count($casting_els) > 4 && get_field('modalita') != 'slide')
				<div class="show-cast">
					<a id="showMoreCast" class="bf-link pointer titles close" role="button">{{__('Show all Cast', 'san-carlo-theme')}} <i class="bf-icon icon-chevron-down"></i> </a>
				</div>
			@endif
		</div>

	</div>
@endhasfields

@hasfield('testo')
<div class="row-text vc_row wpb_row vc_row-fluid vc_row-bf-default flex fwrap pad-tb-5">
	<div class="inner">
		@field('testo')
	</div>
  </div>
  @endfield

  @php(the_content())

  @include('sections.row-buy')

  @endwhile
@endsection