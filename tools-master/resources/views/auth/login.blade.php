@extends('layouts.base')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-6">
        <div class="login-box">
          <div class="panel panel-default">
            <div class="panel-heading">
              Login
              <div class="text-center"><img src="{{ asset("images/adrenalads_logo_blue_small.png") }}" alt=""/></div>
              @if(session()->has('error'))
                <div class="alert alert-danger">
                  <p>{{ session()->get('error') }}</p>
                </div>
              @endif
              @if(session()->has('success'))
                <div class="alert alert-success">
                  <p>{{ session()->get('success') }}</p>
                </div>
              @endif
            </div>

            <div class="panel-body">

                <div class="card card-body card-center">
                  <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                      <label for="email" class="control-label">E-Mail Address</label>
                      <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"
                             required autofocus>
                      @if ($errors->has('email'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                      @endif
                    </div>
                    <div class="form-group{{ $errors->has('pwd') ? ' has-error' : '' }}">
                      <label for="pwd" class="col-md-4 control-label">Password</label>
                      <input id="pwd" type="password" class="form-control" name="pwd" required>
                      @if ($errors->has('pwd'))
                        <span class="help-block">
                                    <strong>{{ $errors->first('pwd') }}</strong>
                                </span>
                      @endif
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary btn-block">
                        Login
                      </button>
                    </div>
                  </form>
                </div>

              @include('partials.socials')
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
