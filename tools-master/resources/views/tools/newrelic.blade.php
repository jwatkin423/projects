@extends('layouts.base')

@section('content')

@foreach ($newrelic as $title => $iframe)

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-align-justify"></i>{{ $title }}</h2>
                <div class="box-icon">
                    <a href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <div class="row">
                    <iframe src="https://rpm.newrelic.com/public/charts/{{ $iframe }}" height="480" scrolling="no" frameborder="no" class="col-lg-12"></iframe>
                </div>
            </div>
        </div>
    </div><!--/col-->
</div> <!-- /row -->

@endforeach

@stop
