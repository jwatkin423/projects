<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Adrenalads Tools: {{ isset($title) ? $title : ' Login' }} </title>

    <!-- CSS -->
    {!! HTML::style(mix('css/app.css')) !!}

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

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">

    <!-- navigation -->
    @if(Auth::check())
        @include('layouts.navbar')
    @endif
    <div class="app-body">
        @if(Auth::check())
            @include('layouts.sidebar')
        @endif
        <!-- Main content -->
        <main class="main">
            <div id="content" class="container-fluid pt-2">
                <div class="campaign-notifications">
                    @include('layouts.notifications')
                </div>

                @yield('content')
            </div>
            <!-- /.conainer-fluid -->
        </main>
    </div>

    <!-- Footer -->
    @include('layouts.footer')

    <!-- JS -->
    {!! HTML::script(mix('js/app.js')) !!}
    {!! HTML::script(mix('js/legacy.js')) !!}

    <script type="text/javascript">
        @if(!Auth::check())
            $('.container-fluid').css('background', '#383e4b');
        @endif
        @yield('inline-js-main')
        @yield('inline-js')
    </script>

</body>
</html>
