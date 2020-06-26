@if (count($recentArticles) > 0 )
<div class="widget widget-posts">
  <h3><a role="button" data-toggle="collapse" href="#widget-posts-collapse" aria-expanded="false" aria-controls="widget-posts-collapse">@lang('messages.recent_posts')</a></h3>
  <div class="collapse in" id="widget-posts-collapse" aria-expanded="false" role="tabpanel">
    <div class="widget-body">
      <ul class="list-unstyled" id="posts" role="tablist" aria-multiselectable="true">
        @foreach($recentArticles as $article)
          <h5><small>{{ $article['post_date'] }}</small><br>{!! link_to_route('show-blog' , $article['title'], ['slug' => $article['slug']]) !!}</h5>
        @endforeach
      </ul>
    </div>
  </div>
</div>
@endif