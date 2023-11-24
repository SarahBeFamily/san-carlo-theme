@extends('layouts.app')

@section('content')

@include('partials.page-header')

@php($calendar = new Calendar())

{{-- <pre>
	{{var_dump(json_decode(do_shortcode( '[events_en categoria_spettacoli="52"]')))}}
</pre> --}}

<div class="bf-calendar-choice">{!! $calendar->show() !!}</div>

	<div id="archivio" class="vc_row wpb_row vc_row-fluid vc_row-bf-default flex fwrap">
		
		<div class="meta">
			<ul id="filtri">
				<li class="cat"><span>@php(_e('Categories', 'san-carlo-theme'))</span>
					<ul>
						<li v-on:click="avviaFiltri('cat', 'all')">@php(_e('All', 'san-carlo-theme'))</li>
						<li v-for="cat, i in eventiCats" :class="{ active: currentCat.cat == cat.term_id ? currentCat.isActive : false }" v-model="currentCat" :id="cat.term_id" v-on:click="avviaFiltri('cat', cat.term_id)">${cat.name}</li>
					</ul>
				</li>
				<li class="calendar"><span>@php(_e('Calendar', 'san-carlo-theme'))</span></li>
				<input type="hidden" name="date_choice" value="" v-on:click="getDate">
			</ul>
	
			<p>${nEventi} @php(_e('Results', 'san-carlo-theme'))</p>
		</div>

		<div class="ev-container flex fwrap">
			<div v-for="evento, i in eventi" class="single-event" v-model="eventi" :style="{ 'background-image': 'linear-gradient(180deg, rgba(0, 0, 0, 0.0) 0%, rgba(0, 0, 0, 0.8) 100%),url(' + evento.acf.immagine_verticale + ')' }">
				<div class="inner">
					<div class="logo"></div>
					<h2> ${evento.title.rendered} </h2>
					<p>${evento.acf.breve_descrizione} </p>
					<a :href="evento.link" class="bf-link white icon-arrow-right icon-arrow-right-white" role="button">@php(_e('Discover more', 'san-carlo-theme'))</a>
				</div>
			</div>
		</div>

		<div id="pagination">
			<ul>
				<li v-for="page in pagination" :class="{ current: currentPage.id == page.id ? currentPage.isActive : false }" :key="page.id" v-model="currentPage">
      				<button type="button" :disabled="page.isDisabled" v-on:click="onClickPage(page.id)">${page.id}</button>
    			</li>
			</ul>
		</div>
	</div>

@endsection