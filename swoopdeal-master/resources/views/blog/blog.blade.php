<article class="post">
  <div class="post-left">
    <div class="author">
      <span>Posted by</span>
      <span class="name">{{ $author['display_name'] }}</span>
    </div>
  </div>
  <h3><a href="{{ url($guid) }}">{{ $title }}</a></h3>
  <div class="meta">
    <div class="date">{{ date('m/d/Y', strtotime($post_date)) }}</div>
    <div class="date visible-xs-inline-block">{{ $author['display_name'] }}</div>
  </div>
  <div class="attachment">
    <img src="" class="img-responsive" alt="">
  </div>
  {!! substr($post_content, 0, 355) . "... " !!}
  <div class="post-bottom clearfix">
    <div class="tags">
    </div>
    <div class="read-more pull-right">
    {!! link_to_route('show-blog' , 'Read More', ['slug' => $slug], ['class' => 'btn btn-primary']) !!}
    </div>
  </div>
</article>