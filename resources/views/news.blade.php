{{--
  Template Name: Pagina Archivio News
--}}

@set($featured, array())
@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @include('partials.page-header')
    
	@isfield('slider_featured', true)
	<div class="vc_row wpb_row vc_row-fluid vc_row-bf-stretch-bg vc_row-no-padding --light-gray">

		@php(
			// query per i post in evidenza
			$query1 = new WP_Query([
				'post_type' => 'post',
				'ordreby' => 'date',
				'order' => 'DESC',
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
	@endfield

	{{-- News --}}
	<div class="row-news --light-gray">

		@set($query2, ['post_type' => 'post'])
		@if (get_field('includi_featured') == false )
			@set($query2['post__not_in'], $featured)
			@set($query2['ignore_sticky_posts'], 1)
		@endif

		@set($query2, new WP_Query($query2))

		@posts($query2)
		
		@set($thumb, get_the_post_thumbnail_url(get_the_ID(),'large') ? get_the_post_thumbnail_url(get_the_ID(),'large') : get_field('post_placeholder', 'options'))

			<div class="single-article"> 
				<div class="img" style="background-image: url({{$thumb}});"></div>
				<div class="inner">
					<div>
						<div class="meta">
							<span class="data">{{ get_the_date() }}</span>
							<h2>@title</h2>
						</div>
						<div class="cat">@category</div>
					</div>
					{{-- @excerpt --}}
					<a class="bf-link titles icon-arrow-right icon-arrow-right-primary" role="button" href="@permalink">{{__('Read', 'san-carlo-theme')}}</a>
				</div>
			</div>
		@endposts
	</div>

  @endwhile
@endsection
