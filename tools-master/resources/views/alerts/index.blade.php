@extends('layouts.base')

@section('content')


@if (count($important_alerts) > 0)
<div class="row">
    <div class="col-lg-12">
        @include('alerts.box', ['alerts' => $important_alerts, 'box_title' => "Important Alerts"] )
    </div><!--/col-->
</div>
@endif
<div class="row">
    <div class="col-lg-12">
        @include('alerts.box', ['box_title' => 'System Alerts'])
    </div><!--/col-->
</div>

@stop
