@php $ticket = get_field('ticket_link'); @endphp

<div class="row-buy" style="background-image: linear-gradient(0deg,rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.5) 100%), url(@option('spettacoli_cta_bg'));">
	<div class="vc_row wpb_row vc_row-fluid vc_row-bf-default">
		<div class="bf-title-block">
			<p class="pre-title-text standard">@option('spettacoli_cta_titoletto')</p>
			
			<p class="bf-title">@option('spettacoli_cta_titolo')</p>
		</div>
		
		<a class="bf-btn icon-arrow-right white " role="button" href="{{$ticket}}">{{ __('Buy now', 'san-carlo-theme') }}</a>
	</div>
</div>