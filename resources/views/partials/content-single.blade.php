@set($cat, get_the_category())

@if ( !empty($cat) && $cat[0]->slug != 'non-categorizzato' )

@set($img_bg, get_the_post_thumbnail_url( get_the_ID(), 'full' ) ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : get_field('post_placeholder', 'options'))

<div class="article-title" style="background-image: linear-gradient(-90deg,rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.5) 140%), url( {{ $img_bg }} );">

	<div class="bf-title-block color-white big">
    <p class="pre-title-text uppercase white">@category</p>
		<h1 class="bf-title">{!! $title !!}</h1>
    <div class="descr-text color-white">{{ get_the_date() }}</div>
	</div>
</div>

<div class="vc_row wpb_row vc_row-fluid vc_row-bf-default flex fwrap --light-gray">

  <div class="spacer-80"></div>

  <div class="bf-col-8">
    @php(the_content())
  </div>

  <div class="bf-col-4">
    @hasfields('files')
      @fields('files')
        @hassub('link_file')
        <a class="bf-link titles icon-download" role="button" href="@sub('link_file')">@sub('nome_file')</a>
        @endsub
        @hassub('file')
        <a class="bf-link titles icon-download" role="button" href="@sub('file')">@sub('nome_file')</a>
        @endsub
      @endfields
    @endhasfields
  </div>
</div>

@query([
  'public' => true,
	'_builtin' => false,
  'post_type' => 'post',
  'posts_per_page' => 3,
  'category__in' => wp_get_post_categories(get_the_ID()),
])

@hasposts
<div class="related-posts --light-gray">
  <div class="separatore"></div>

  <div class="bf-title-block color-titles">
		<h1 class="bf-title">{{ __('You may also be interested in', 'san-carlo-theme') }}</h1>
	</div>

  <div class="posts">
    @posts
    @set($img_bg_rel, get_the_post_thumbnail_url( get_the_ID(), 'full' ) ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : get_field('post_placeholder', 'options'))

    <div class="single-article">
      <div class="img" style="background-image: url({{$img_bg_rel}});"></div>
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
</div>
@endhasposts

@else 


{{-- Creo il layout --}}
@layouts('layout')

    @layout('titolo')
    <div class="title-row full-vw">
        <div class="col">

            @set($titolo, get_sub_field('titolo') ? '<h1 class="entry-title">'.get_sub_field('titolo').'</h1>' : the_title('<h1 class="entry-title">', '</h1>'))
                
            {!! $titolo !!}

        </div>

        <div class="col">
            @issub('elemento_accanto_al_titolo', 'testo')
                @sub('testo_titolo')
            @endsub

            @issub('elemento_accanto_al_titolo', 'foto')
            <img class="animate fadeinup" src="@sub('foto_titolo', 'url')" alt="@sub('foto_titolo', 'alt')" loading="lazy">
            @endsub
        </div>
    
    </div>
    @endlayout

    @layout('due_colonne')
    <div class="two-col-row full-vw">

        @hassub('col')
        @foreach (get_sub_field('col') as $col)
            <div class="col">
                @if ($col['elemento'] == 'testo')
                    {!! $col['testo'] !!}
                @endif

                @if ($col['elemento'] == 'foto')
                    <img class="animate fadeinup" src="{!! $col['foto']['url'] !!}" alt="{!! $col['foto']['alt'] !!}" loading="lazy">
                @endif

                @if ($col['elemento'] == 'video')
                    @set($link_video, $col['video'])
                    @set($video_expl, explode(".", $link_video))
                    @set($ext, strtolower(end($video_expl)))
                    <video crossorigin="anonymous" autoplay loop muted defaultMuted playsinline width="1920">
                        <source src="{{ $link_video }}" type="video/{{ $ext }}">
                        {{-- Your browser does not support the video tag. --}}
                    </video>
                @endif
            </div>
        @endforeach
        @endsub
    
    </div>
    @endlayout

    @layout('colonna_unica')
    <div class="two-col-row full-vw">

        @hassub('elemento_colonna_unica')
        @foreach (get_sub_field('elemento_colonna_unica') as $el)
            <div class="elemento {{$el['scegli_elemento']}}">
                @if ($el['scegli_elemento'] == 'testo')
                    {!! $el['testo'] !!}
                @endif

                @if ($el['scegli_elemento'] == 'frase')
                    <span class="frase-evidenza">{!! $el['frase_in_evidenza'] !!}</span>
                @endif

                @if ($el['scegli_elemento'] == 'foto')
                    <img class="animate fadeinup" src="{!! $el['foto']['url'] !!}" alt="{!! $el['foto']['alt'] !!}" loading="lazy">
                @endif

                @if ($el['scegli_elemento'] == 'video')
                    @set($link_video, $el['video'])
                    @set($video_expl, explode(".", $link_video))
                    @set($ext, strtolower(end($video_expl)))
                    <video crossorigin="anonymous" autoplay loop muted defaultMuted playsinline width="1920">
                        <source src="{{ $link_video }}" type="video/{{ $ext }}">
                        {{-- Your browser does not support the video tag. --}}
                    </video>
                @endif
            </div>
        @endforeach
        @endsub
    
    </div>
    @endlayout

@endlayouts

@endif