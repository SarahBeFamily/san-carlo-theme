
	<div class="vc_row wpb_row vc_row-fluid vc_row-bf-stretch-bg vc_row-no-padding --light-gray">

		@php(
			// query per i post in evidenza
			$query1 = new WP_Query([
				'post_type' => 'post',
				'ordreby' => 'date',
				'order' => 'DESC',
				'ignore_sticky_posts' => 1, // 'sticky' => '1
				'meta_query' => [
					[
						'key' => 'articolo_in_evidenza',
						'value' => '1',
						'compare' => '='
					]
				]
			])
  			
		)

		@hasposts($query1)
			<div class="bf-carousel -news">
				@posts($query1)
					@set($thumb, get_the_post_thumbnail_url(get_the_ID(),'full') ? get_the_post_thumbnail_url(get_the_ID(),'full') : get_field('post_placeholder', 'options'))
					@php($featured[] = get_the_ID())
					<div class="bf-carousel-single-slide -articolo">
						<div style="background-image: url({{$thumb}});">
							<div class="inner">
								<div>
									<div class="meta">
										<span class="data">{{ get_the_date() }}</span>
										<h2>@title</h2>
									</div>
									<div class="cat">@category</div>
								</div>
								{{-- <div class="desk">@excerpt</div>
								<p class="mobile">@filter('shorten_excerpt', 'shorten_excerpt')</p> --}}
								<a class="bf-link titles icon-arrow-right icon-arrow-right-primary" role="button" href="@permalink">{{__('Read', 'san-carlo-theme')}}</a>
							</div>
						</div>
					</div>
				@endposts
			</div>
		@endhasposts
	</div>
