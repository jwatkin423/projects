@if(count($featuredArticles) > 0)
  <div class="section-title">
    <h2>@lang('messages.latest_from_the_blog')</h2>
    <p>@lang('messages.bloggers_of_swoopdeal')</p>
  </div>

  <div class="row">
    @php
      $count = count($featuredArticles);
      $class = 12 / $count;
      $blog_pic = $class > 3 ? 'blog-pic' : null;
    @endphp
    @foreach($featuredArticles as $index => $article)
      @php $index++ @endphp
      <div class="col-xs-6 col-sm-{{ $class }}">
        <article class="post">
          <img src="{{ asset('images/blog/blog-' . $index . '.jpg')}}" class="img-responsive {{ $blog_pic }}" alt="">
          <h4 @if($class > 3)class="text-center"@endif>{!! link_to_route('show-blog' , $article['title'], ['slug' => $article['slug']]) !!}</h4>
          <div @if($class > 3)class="text-center"@endif>
            <span class="date">{{ $article['post_date'] }}</span>
          </div>
        </article>
      </div>
    @endforeach
  </div>
@endif