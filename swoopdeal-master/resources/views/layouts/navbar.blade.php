<!-- ==========================
      HEADER - START
    =========================== -->
<header class="navbar navbar-default navbar-static-top">
  <div class="container">
    <div class="navbar-header">
      <a href="{!! URL::route('home') !!}" class="navbar-brand">
        <img src="{{ asset('images/swoopdeal.png') }}" alt="Swoopdeal"></a>
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <i class="fa fa-bars"></i></button>
    </div>
    <div class="navbar-collapse collapse">
      <div class="row">
        <div class="col-sm-6">
          {!! Form::open(['route' => 'search', 'id' => 'search-main-all', 'data-url' => URL::to('search'), 'method' => 'GET']) !!}
            <div class="input-group input-group-lg">
              @if ($section !== 'products')
                {!! Form::text('q', null, ['class' => 'form-control', 'placeholder' => trans('messages.enter_search_terms'), 'autocomplete' => "off", 'id' =>  "search-all"]) !!}
              @else
                {!! Form::text('q', $q, ['class' => 'form-control', 'placeholder' => trans('messages.enter_search_terms'), 'autocomplete' => "off", 'id' =>  "search-all"]) !!}
              @endif
              <span class="input-group-btn">
							<button class="btn btn-primary" id="main-submit-bar" type="submit">{{ trans('messages.search') }}</button>
						</span>
            </div>

          {!! Form::close() !!}

        </div>

      </div>
    </div>
  </div>
</header>
<!-- ==========================
  HEADER - END
=========================== -->

<div class="top-header hidden-xs">
  <div class="container">
    <div class="row">
      <div class="col-sm-3">
        <ul class="list-inline contacts">
          <li>{!! link_to_route('home', trans('messages.home')) !!}</li>
          <li>{!! link_to_route('about', trans('messages.about')) !!}</li>
        </ul>
      </div>
    </div>
  </div>
</div>