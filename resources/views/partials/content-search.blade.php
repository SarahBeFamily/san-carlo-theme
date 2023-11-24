@set($thumb, get_the_post_thumbnail_url(get_the_ID(),'large') ? get_the_post_thumbnail_url(get_the_ID(),'large') : get_field('post_placeholder', 'options'))
@set($post_type, get_post_type( get_the_ID() ) == 'page' ? __('Page', 'san-carlo-theme') : get_post_type( get_the_ID() ))

<article @php(post_class())>
    {{-- @includeWhen(get_post_type() === 'post', 'partials.entry-meta') --}}

  <div class="single-article">
    <div class="img" style="background-image: url({{$thumb}});"></div>
    <div class="inner">
      <div>
        <div class="meta">
          {{-- <span class="data">{{ get_the_date() }}</span> --}}
          <h2>@title</h2>
        </div>
        <div class="cat">{{$post_type == 'post' ? __('News', 'san-carlo-theme') : $post_type}}</div>
      </div>
      
      <a class="bf-link titles icon-arrow-right icon-arrow-right-primary" role="button" href="@permalink">{{__('Read', 'san-carlo-theme')}}</a>
    </div>
  </div>
</article>
