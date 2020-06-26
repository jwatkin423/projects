@extends('layouts.base')

@section('content')

  <div class="row" style="margin-top: 100px;">
    {{ d($products) }}
    {{ d($provider) }}
    {{ d($categories) }}

  </div>

@endsection