@extends('layouts.base')

@section('content')
    <div class="row">
        @include('layouts.notifications')
    </div>
<div class="row">
    <div class="col-lg-12">
    <table class="table datatable">
        <thead>
        <tr>
            <th>User ID</th>
            <th>Login E-mail</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Avatar</th>
            <th>Role</th>
            <th>Edit</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{$user->user_id}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->fname}}</td>
            <td>{{$user->lname}}</td>
            <td><img src="{{$avatarHelper->avatar_url($user->avatar)}}" alt="" height="25px"/></td>
            <td>{{$user->role}}</td>
            <td><a href="{{url("users/$user->user_id")}}" class="btn btn-primary">Edit</a></td>
        </tr>
            @endforeach
        </tbody>
    </table>
    <a class="btn btn-lg btn-block btn-success" href="{{url('users/create')}}">Create New Tools User</a>
    </div>
</div>
@endsection