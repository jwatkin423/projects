<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
  <title>{{ $title }}</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- ==========================
    Fonts
  =========================== -->
  <link
    href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,200,200italic,300,300italic,400italic,600,600italic,700,700italic,900,900italic&amp;subset=latin,latin-ext'
    rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600,700,900,800' rel='stylesheet' type='text/css'>
  <pre class="anticlick" lang="javascript">
          <style id="antiClickjack">body{display:none !important;}</style>
          <script type="text/javascript">
              if (self === top) {
                  var antiClickjack = document.getElementById("antiClickjack");
                  antiClickjack.parentNode.removeChild(antiClickjack);
              } else {
                  top.location = self.location;
              }
          </script>
      </pre>

  <!-- ==========================
    JS
  =========================== -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->

    <!-- CSS -->
    {!! HTML::style('css/bootstrap.min.css') !!}
    {!! HTML::style('css/jquery-ui.css') !!}
    {!! HTML::style('css/tmpl.css') !!}
    {!! HTML::style('css/font-awesome.min.css') !!}
    {!! HTML::style('css/dragtable.css') !!}
    {!! HTML::style('css/owl.carousel.css') !!}
    {!! HTML::style('css/animate.css') !!}
    {!! HTML::style('css/color-switcher.css') !!}
    {!! HTML::style('css/custom.css') !!}
    {!! HTML::style('css/red.css') !!}
    {!! HTML::style('css/nouislider.css') !!}
    {!! HTML::style('css/swoopdeal.css') !!}

  <!-- ==========================
        JS
      =========================== -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->


    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" >
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-107133471-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments)};
        gtag('js', new Date());

        gtag('config', '{{ env('GOOGLE_ANALYTICS_ID') }}');
    </script>

</head>
<body>

<div> <!-- PAGE - START -->
  @php
    $class = 'col-sm-9 col-xs-12';
    if(isset($aboutPage) && $aboutPage) {
      $class = "col-sm-12 col-xs-12";
    }
  @endphp
  @include('layouts.navbar')
  <section class="content {{ $section }}-container">
    <div class="container container-{{ $section }}">
      <div class="row row-no-padding">
        @if(!isset($aboutPage))
        <div class="col-sm-3 col-xs-12 side-bar-div">
          @include('partials.sidebar.widget')
        </div>
        @endif
        <div class="{{ $class }}">
          @yield('content')
        </div>
      </div> <!-- row row-no-padding -->

    </div> <!-- container-products -->
  </section>
{{--  @if(count($featuredProducts) > 0)
  @if($section == 'products' || $section == 'about')
  <div class="row row-no-padding">
  <section class="content recent-blog-posts-container">
    <div class="container container-recent-blog-posts">
    @include('partials.featured', $featuredArticles)
    </div>
  </section>
  </div>
  @endif
  @endif--}}
  {{--<div style="clear: both;"></div>--}}
</div>
@include('layouts.footer')
<!-- JS -->

<script src="https://code.jquery.com/jquery-latest.js"></script>
<script src="https://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
{!! HTML::script('js/bootstrap.min.js') !!}
{!! HTML::script('js/bootstrap-hover-dropdown.min.js') !!}
{!! HTML::script('js/jquery.dragtable.js') !!}
{!! HTML::script('js/owl.carousel.min.js') !!}
{!! HTML::script('js/jquery.mb.YTPlayer.min.js') !!}
{!! HTML::script('js/wNumb.js') !!}
@if((isset($search) && $search) || (isset($catSearch) && $catSearch == true) && $featuredProducts)
  {!! HTML::script('js/nouislider.js') !!}
  {!! HTML::script('js/search_filter.js') !!}
  @php $Products = new \App\Http\Controllers\ProductsController(); @endphp
@endif
<script type="text/javascript">
  var minPrice = '{{ $minPrice }}';
  var maxPrice = '{{ $maxPrice }}';

  var sel_max_price = maxPrice;
  var sel_min_price = minPrice;

  @if((isset($search) && $search) || (isset($catSearch) && $catSearch == true))
    var pre = "{{ $Products->getLocalePrefix()}} ";
  @endif

  // set selected min price
  @if(isset($selectedMinPrice))
    sel_min_price = '{{ $selectedMinPrice }}';
    sel_min_price = parseInt(sel_min_price);
  @endif

  // set selected max price
  @if(isset($selectedMaxPrice))
    sel_max_price = '{{ $selectedMaxPrice }}';
    sel_max_price = parseInt(sel_max_price);
  @endif
  @yield('inline-js')
</script>
</body>
</html>
