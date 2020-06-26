@extends('layouts.base')


@section('content')

  <h3>Welcome to the Excel Area</h3>

  <div class="col-6">
   <p>The path {{ $dropbox_path }}</p>
  </div>

  <div class="col-6">
    <h3 class="card-header">Excel Folders</h3>
    <ul class="list-group">
      @if(!empty($excel_folders))
          @include('excel.partials.folderlist', ['folders' => $excel_folders])
      @endif
    </ul>
  </div>

@endsection