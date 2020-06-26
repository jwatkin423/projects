<div class="col-sm-9">

        <!-- POST - START -->
        <article class="post">
          <div class="post-left">
            <div class="author">
              <span>Posted by</span>
              <span class="name">{{ $article['display_name'] }}</span>
            </div>
          </div>
          <h3>{{ $article['title'] }}</h3>
          <div class="meta">
            <div class="date">{{ $article['post_date'] }}</div>
            <div class="date visible-xs-inline-block">{{ $article['display_name'] }}</div>
            <div class="category">Uncategorized</div>
          </div>
          <div class="attachment">
            <img src="" class="img-responsive" alt="">
          </div>
          {!! $article['content'] !!}
          <div class="post-bottom clearfix">

          </div>
        </article>
        <!-- POST - END -->

        <!-- COMMENTS - START -->
        <div class="comments">

        </div>


        <!-- COMMENTS - END -->

      </div>
