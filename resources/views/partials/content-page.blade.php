@php(the_content())

@isfield('next_events_widget', true)
	<div class="vc_row wpb_row vc_row-fluid vc_row-bf-default pad-tb-5">
		@shortcode('[bf_next_events title="'.__('Upcoming shows', 'san-carlo-theme').'"]')
	</div>
@endfield

@isfield('news_widget', true)
	<div class="vc_row wpb_row vc_row-fluid vc_row-bf-default">
		<div class="first-col wpb_column vc_column_container vc_col-sm-3">
			<h3 class="bf-title">{{ __('News', 'san-carlo-theme') }}</h3>
		</div>
		<div class="news-col wpb_column vc_column_container vc_col-sm-9">
			@shortcode('[bf_latest_news]')
		</div>
	</div>
@endfield

{!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'san-carlo-theme'), 'after' => '</p></nav>']) !!}
