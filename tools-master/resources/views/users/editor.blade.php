@extends('layouts.base')

@section('content')
    @if(isset($edit))
        {!! Form::model($user, ['route' => ['users.update', $user->user_id], 'class' => 'form-horizontal']) !!}
        {!! Form::hidden('_method', 'PUT') !!}
    @else
        {!! Form::model($user, ['route' => 'users.store', 'class' => 'form-horizontal', 'method' => 'POST']) !!}
    @endif
    <div class="row">
        <div class="col-xs-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <h2><i class="fa fa-user"></i>User Identity</h2>
                </div>
                <div class="box-content">
                    @if(isset($edit))
                    <div class="form-group">
                        {!! Form::label('User ID') !!}
                        {!! Form::text('user_id', $user->user_id, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                        {!! Form::hidden('hidden_user_id', $user->user_id) !!}
                    </div>
                    @endif
                    <div class="form-group">
                        {!! Form::Label('Email') !!}
                        {!! Form::email('email', $user->email, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::Label('Password') !!}
                        {!! Form::password('pwd', ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::Label('First Name') !!}
                        {!! Form::text('fname', $user->fname, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::Label('First Name') !!}
                        {!! Form::text('lname', $user->lname, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <h2><i class="fa fa-cog"></i>User Settings</h2>
                </div>
                <div class="box-content">
                    <div class="form-group">
                        {!! Form::select('role', $roles, $user->role, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::Label('Avatar') !!}
                        <div class="">
                            {!! Form::hidden("avatar") !!}
                            <img id="user-avatar" src="{{ isset($user) ? $avatarHelper->avatar_url($user->avatar) : "//placehold.it/100x100?text=select%20avatar" }}" alt=""/>
                        </div>
                        <a id="select-avatar" class="btn btn-info" style="margin-top: 10px">Select an avatar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        {!! Form::submit(isset($edit) ? 'Update User' : 'Create New User', ['class' => 'btn btn-lg btn-block btn-success']) !!}
    </div>
        {!! Form::close() !!}


    <!-- Modal -->
    <div class="modal fade" id="avatarPicker" tabindex="-1" role="dialog" aria-labelledby="avatarPickerLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="avatarPickerLabel">Pick an avatar</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach($avatars as $avatar)
                            @php $avatarPortrait = $avatarHelper->avatar_url($user->avatar); @endphp
                            <img  class="avatar col-sm-2 col-xs-6 {{ isset($user) && $avatar == $user->avatar ? "selected":""}}"
                                  style="margin-bottom: 10px" src="{{$avatarHelper->avatar_url($avatar)}}"
                                  alt="" data-name="{{$avatar}}"/>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('inline-js')

$('#select-avatar').click(function () {
    $('#avatarPicker').modal();
    $('.avatar').click(function () {
        src = $(this).attr("src");
        name = $(this).attr("data-name");
        $('#user-avatar').attr("src", src);
        $('[name="avatar"]').attr('value', name);
        $('.avatar.selected').removeClass('selected');
        $(this).addClass('selected');
        $('#avatarPicker').modal('hide');
    });
});

@endsection