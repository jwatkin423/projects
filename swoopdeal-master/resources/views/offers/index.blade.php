@extends('layouts.base')

@section('content')

  @if(!$singlePost)
    @include('offers.products')
  @else
    @include('offers.singleproduct')
  @endif

  @if(count($featuredProducts) == 0 )
  <div class="row row-no-padding">
        @include('partials.featured_modified', $featuredArticles)
  </div>
  @endif

@endsection