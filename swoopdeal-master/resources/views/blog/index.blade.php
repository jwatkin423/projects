@extends('layouts.base')

@section('content')

  <div class="row grid row-no-padding">
    @foreach($blogs as $blog)
      @include('blog.blog', $blog)
    @endforeach
  </div>
  <div class="pull-right">
    <ul class="pagination">
      {!! $blogs->render() !!}
    </ul>
  </div>

@endsection