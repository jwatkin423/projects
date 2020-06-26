<footer class="navbar navbar-default">
    <div class="container">
        <div class="footer">
            <div class="row">
                <div class="col-sm-6 col-xs-6">
                    <div class="footer-widget footer-widget-contacts">
                        <h4>@lang('messages.contacts')</h4>
                        <ul class="list-unstyled">
                            <li><i class="fa fa-envelope"></i> contact@swoopdeal.com</li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6 col-xs-6">
                    <div class="footer-widget footer-widget-links">
                        <h4>{{ trans('messages.information') }}</h4>
                        <ul class="list-unstyled">
                            <li>{!! link_to_route('home', trans('messages.home')) !!}</li>
{{--                            <li>{!! link_to_route('blog-home', trans('messages.blog')) !!}</li>--}}
                            <li>{!! link_to_route('privacy', trans('messages.privacy_policy')) !!}</li>
                            <li>{!! link_to_route('terms', trans('messages.terms_of_service')) !!}</li>
                        </ul>
                    </div>
                </div>
                {{--<div class="col-sm-4 col-xs-4">
                    <div class="footer-widget footer-widget-links">
                        <h4>{{ trans('messages.recent_activity') }}</h4>
                        <ul class="list-unstyled">
                            @foreach($recentArticles as $article)
                                @php $title = substr($article['title'], 0, 25) . "..."; @endphp
                                <li><small>{{ $article['post_date'] }}</small> | {!! link_to_route('show-blog' , $title, ['slug' => $article['slug']]) !!}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>--}}
            </div>
        </div>
    </div>
</footer>