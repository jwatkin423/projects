@extends('layouts.base')

@section('content')
    {{ Form::open(['route' => 'pacing-traffic', 'class' => 'form-horizontal', 'method' => 'get']) }}
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="box">
                <div class="box-header">
                    <h2><i class="fa fa-calendar"></i>Pick your Date Range</h2>
                </div>
                <div class="box-content" id="date-range-filter">
                    <fieldset class="col-lg-12 col-xs-12">
                        <div class="form-group">
                            {!! Form::label('start_date', 'Start Date:', ["class" => "control-label"]) !!}
                            <div class="controls">
                                <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
                                    <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                    {!! Form::text('start_date', $start_date, ['class' => 'form-control', 'id' => 'fromDate']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('end_date', 'End Date:', ["class" => "control-label"]) !!}
                            <div class="controls">
                                <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
                                    <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                    {!! Form::text('end_date', $end_date, ['class' => 'form-control', 'id' => 'toDate']) !!}
                                </div>
                            </div>
                        </div>
                        @include('reports.date-controls')
                    </fieldset>
                </div>
            </div>
        </div><!--/col-->

        <div class="col-lg-6 col-md-6">
            <div class="box">
                <div class="box-header">
                    <h2><i class="fa fa-align-justify"></i>Pick your Filters</h2>
                </div>
                <div class="box-content" id="pick-your-filters">
                    <fieldset class="col-sm-12">

                        <div class="form-group">
                            {{ Form::label('campaign', 'Campaign:', ["class" => "control-label"]) }}
                            <div class="controls">
                                {{ Form::select('campaign', $campaigns, $filter['campaign'], ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('inventory', 'API Partner:', ["class" => "control-label"]) }}
                            <div class="controls">
                                {{ Form::select('inventory', $inventories, $filter['inventory'], ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-actions">
                            {{ Form::submit('Update', ['class' => 'btn btn-success btn-block', 'id' => 'update-filters']) }}
                        </div>
                    </fieldset>
                </div>
            </div>
        </div><!--/col-->
    </div>
    {{ Form::close() }}


    <div class="row">
        <div class="col-xs-6 col-lg-2">
            <div class="smallstat box">
                <i class="fa fa-download red"></i>
                <span class="title">Auctions</span>
                <span class="value">{{ number_format($summary['auctions']) }}</span>
            </div>
        </div> <!--/col-->

        <div class="col-xs-6 col-lg-2">
            <div class="smallstat box">
                <i class="fa fa-upload yellow"></i>
                <span class="title">Redirects</span>
                <span class="value">{{ number_format($summary['redirects']) }}</span>
            </div>
        </div><!--/col-->

        <div class="col-xs-6 col-lg-2">
            <div class="smallstat box">
                <i class="fa fa-download red"></i>
                <span class="title">Cost</span>
                <span class="value">{{ money_format('%.2n', $summary['cost']) }}</span>
            </div>
        </div> <!--/col-->

        <div class="col-xs-6 col-lg-2">
            <div class="smallstat box">
                <i class="fa fa-upload yellow"></i>
                <span class="title">Revenue</span>
                <span class="value">{{ money_format('%.2n', $summary['revenue']) }}</span>
            </div>
        </div><!--/col-->

        <div class="col-xs-6 col-lg-2">
            <div class="smallstat box">
                <i class="fa fa-dollar green"></i>
                <span class="title">Profit</span>
                <span class="value">{{ money_format('%.2n', $summary['profit']) }}</span>
            </div>
        </div><!--/col-->

        <div class="col-xs-6 col-lg-2">
            <div class="smallstat box">
                <i class="fa fa-money blue"></i>
                <span class="title">ROI</span>
                <span class="value">
                @if($summary['roi'] != -1)
                        {{ sprintf("%.2f", $summary['roi']) }}%
                    @else
                        N/A
                    @endif
            </span>
            </div>
        </div><!--/col-->

    </div><!--/row-->

    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h2><i class="fa fa-align-justify"></i>Requests and Redirects</h2>
                </div>
                <div class="box-content" id="reqs-reds">
                    <div class="row">
                        <div id="requests-redirects-traffic-graph" class="col-lg-12" style="height:480px"></div>
                    </div>
                </div>
            </div>
        </div><!--/col-->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h2><i class="fa fa-align-justify"></i>Cost and Revenue</h2>
                </div>
                <div class="box-content" id="cos-rev">
                    <div class="row">
                        <div id="cost-revenue-traffic-graph" class="col-lg-12" style="height:480px"></div>
                    </div>
                </div>
            </div>
        </div><!--/col-->
    </div>
@endsection

@section('inline-js')
$(document).ready(function () {
    var summaries = {!! json_encode($prepared_daily_summary) !!},
        request_redirects_plot = $.plot($("#requests-redirects-traffic-graph"),
            [
                {
                    data: summaries.auctions,
                    label: "Auctions",
                    color: 'red',
                    points: {show: true},
                    lines: {show: true}
                },
                {
                    data: summaries.redirects,
                    label: "Redirects",
                    color: 'blue',
                    points: {show: true},
                    lines: {show: true}
                }
            ],
            {
                grid: {hoverable: true, clickable: true},
                yaxis: {min: 0},
                xaxis: {mode: 'time', timeformat: '%Y-%m-%d', minTickSize: [1, 'day']},
                legend: {position: 'nw'}
            }
        ),
        cost_revenue_plot = $.plot($("#cost-revenue-traffic-graph"),
            [
                {
                    data: summaries.cost,
                    label: "Cost",
                    color: 'red',
                    points: {show: true},
                    lines: {show: true}
                },
                {
                    data: summaries.revenue,
                    label: "Revenue",
                    color: 'blue',
                    points: {show: true},
                    lines: {show: true}
                }
            ],
            {
                grid: {hoverable: true, clickable: true},
                yaxis: {min: 0},
                xaxis: {mode: 'time', timeformat: '%Y-%m-%d', minTickSize: [1, 'day']},
                legend: {position: 'nw'}
            }
        );

    function showTooltip(x, y, contents, bgcolor) {
        $('<div id="tooltip">' + contents + '</div>').css({
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            padding: '2px',
            'background-color': bgcolor,
            opacity: 0.70,
            color: '#fff'
        }).appendTo("body").fadeIn(200);
    }

    var previousPoint = null,
        previousColor = null;
    $("#requests-redirects-traffic-graph, #cost-revenue-traffic-graph").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));

        if (item) {
            var bgcolor = item.series.color;
            if (previousPoint != item.dataIndex || previousColor != bgcolor) {
                previousPoint = item.dataIndex;
                previousColor = bgcolor;

                $("#tooltip").remove();
                var x = item.datapoint[0].toFixed(2),
                    y = item.datapoint[1].toFixed(2);

                if (event.currentTarget.id == 'requests-redirects-traffic-graph') {
                    showTooltip(item.pageX, item.pageY,
                        commify(parseInt(y)), bgcolor);
                } else {
                    showTooltip(item.pageX, item.pageY,
                        formatDollar(parseFloat(y)), bgcolor);
                }
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
            previousColor = null;
        }
    });

});
@endsection
