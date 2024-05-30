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
    <a class="bf-link titles icon-arrow-right icon-arrow-right-primary" role="button" href="@permalink">{{__('Read', 'san-carlo-theme')}}</a>
  </div>
</div>
