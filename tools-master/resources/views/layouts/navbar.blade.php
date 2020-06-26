<header class="app-header navbar">
  <a class="navbar-brand" href="{!! route('home') !!}"></a>
  <button class="navbar-toggler sidebar-toggler mobile-sidebar-toggler pull-left" type="button">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="ml-auto" id="navbarNavDropdown">
    <ul class="navbar-nav ml-auto">
      @if(Auth::check())

        <li class="nav-item dropdown important-alerts">
          <a class="nav-link dropdown-toggle notifications" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

            <div class="avatar"><img src="{{ $avatarHelper->avatar_url(Auth::user()->avatar) }}"></div>
            <div class="user">
              <span class="hello">&nbsp;</span>
              <span class="name">{{Auth::user()->full_name}}</span>
            </div>
          </a>
          <div class="dropdown-menu notifications-pane dropdown-pull-right" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="{{url("users/".Auth::user()->user_id)}}"><i class="fa fa-user"></i> Profile</a>
            {!! Form::open(['route' => 'logout']) !!}
            <button class="dropdown-item" type="submit"><i class="fa fa-off"></i> Logout</button>
            {!! Form::close() !!}
          </div>
        </li>
      @endif
    </ul>
  </div>
</header>
