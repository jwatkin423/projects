@extends('layouts.base')


@section('content')

  <h3>{{ $path }}</h3>

  <div class="col-6">
    <p>(folder and excel counts here)</p>
  </div>

  <div class="col-6">
    <h3 class="card-header">Folders</h3>
    @if(!empty($contents))
      <ul class="list-group">
          @include('excel.partials.folderlist', ['folders' => $contents])
      </ul>
    @else
      <ul class="list-group">
        <li class="list-group-item">- NA -</li>
      </ul>
    @endif
    <ul class="list-group">

    </ul>
  </div>
  <div class="col-6">
    <h3 class="card-header">Excel Files</h3>
    @if (!empty($files))
      <ul class="list-group">
        @include('excel.partials.excellist', ['files' => $files, 'path' => $path])
      </ul>
    @endif
  </div>

@endsection