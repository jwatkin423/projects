@extends('layouts.base')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-calendar"></i>Select Advertiser and Date Range</h2>
            </div>
            <div class="box-content" id="date-range-filter">
                {!! Form::model($date_filter, ['route' => 'get-pacing', 'class' => 'form-horizontal', 'method' => 'GET']) !!}
                    <fieldset class="col-lg-12">
                        <div class="form-group">
                            {!! Form::label('campaign', 'Campaign:', ["class" => "control-label"]) !!}
                            <div class="controls">
                            {!! Form::select('campaign', $campaigns, null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('first_date', 'Date 1:', ["class" => "control-label"]) !!}
                            <div class="controls">
                                <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
                                    <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                    {!! Form::text('first_date', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('second_date', 'Date 2:', ["class" => "control-label"]) !!}
                            <div class="controls">
                                <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
                                    <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                    {!! Form::text('second_date', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('third_date', 'Date 3:', ["class" => "control-label"]) !!}
                            <div class="controls">
                                <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
                                    <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                    {!! Form::text('third_date', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="controls">
                                <label class="checkbox inline">
                                    {!! Form::checkbox('show_full_table', 1) !!} Show Full Data Table
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                {!!  link_to_action('ReportController@getPacing', 'Today, Yesterday, 1 Week Ago', [
                                    'first_date' => $today_date_filter->date1,
                                    'second_date' => $yesterday_date_filter->date2,
                                    'third_date' => $one_week_filter
                                ], ['data-source' => 'pacing-date-one', 'class' => 'pacing-date-one btn btn-info btn-block mrg-top-10']) !!}
                            </div>
                            <div class="col-sm-6">
                                {!!  link_to_action('ReportController@getPacing', 'Today, 1 Week Ago, 2 Weeks Ago', [
                                    'first_date' => $today_date_filter->date1,
                                    'second_date' => $one_week_filter,
                                    'third_date' => $two_weeks_filter
                               ], ['data-source' => 'pacing-date-two', 'class' => 'pacing-date-two btn btn-info btn-block mrg-top-10']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                {!!  link_to_action('ReportController@getPacing', 'Today, 1 Week Ago, 1 Month Ago', [
                                    'first_date' => $today_date_filter->date1,
                                    'second_date' => $one_week_filter,
                                    'third_date' => $month_prior_filter
                                ], ['data-source' => 'pacing-date-three', 'class' => 'pacing-date-three btn btn-info btn-block mrg-top-10']) !!}
                            </div>
                            <div class="col-sm-6">
                                {!!  link_to_action('ReportController@getPacing', 'Yesterday, Day Before, 1 Week Ago', [
                                    'first_date' => $yesterday_date_filter->date1,
                                    'second_date' => $day_before_filter,
                                    'third_date' => $one_week_filter
                                ], ['data-source' => 'pacing-date-four', 'class' => 'pacing-date-four btn btn-info btn-block mrg-top-10']) !!}
                            </div>
                        </div>

                        <div class="form-actions">
                            {!! Form::submit('Update Graph', ['class' => 'btn btn-success btn-block submit-button-mt-10']) !!}
                        </div>
                    </fieldset>
                {!! Form::close() !!}
            </div>
        </div>
    </div><!--/col-->

    <div class="col-lg-6">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-align-justify"></i>Totals for {{ $date_filter->campaign }}</h2>
            </div>
            <div class="box-content" id="campaign-totals">
                <table class="table table-striped table-bordered bootstrap-datatable datatable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Auctions</th>
                            <th>Redirects</th>
                            <th>Cost</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($summaries['daily'] as $date => $total)
                        <tr>
                            <td>{{ $date_filter[$date] }}</td>
                            <td>{{ number_format($total['auctions']) }}</td>
                            <td>{{ number_format($total['redirects']) }}</td>
                            <td>{{ money_format('%.2n', $total['cost']) }}</td>
                            <td>{{ money_format('%.2n', $total['revenue']) }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div><!--/col-->
</div>

<!-- Auctions -->
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-align-justify"></i>Auction Pacing for {{ $date_filter }}</h2>
            </div>
            <div class="box-content" id="req-pacing">
                <div class="row">
                    <div id="pacing-graph-auctions" class="col-lg-12" style="height:480px"></div>
                </div>
            </div>
        </div>
    </div><!--/col-->
</div>

<!-- Redirects -->
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-align-justify"></i>Redirect Pacing for {{ $date_filter }}</h2>
            </div>
            <div class="box-content" id="red-pacing">
                <div class="row">
                    <div id="pacing-graph-redirect" class="col-lg-12" style="height:480px"></div>
                </div>
            </div>
        </div>
    </div><!--/col-->
</div>

<!-- Cost -->
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-align-justify"></i>Cost Pacing for {{ $date_filter }}</h2>
            </div>
            <div class="box-content" id="rev-pacing">
                <div class="row">
                    <div id="pacing-graph-cost" class="col-lg-12" style="height:480px"></div>
                </div>
            </div>
        </div>
    </div><!--/col-->
</div>

<!-- Revenue -->
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-align-justify"></i>Revenue Pacing for {{ $date_filter }}</h2>
            </div>
            <div class="box-content" id="rev-pacing">
                <div class="row">
                    <div id="pacing-graph-revenue" class="col-lg-12" style="height:480px"></div>
                </div>
            </div>
        </div>
    </div><!--/col-->
</div>

@if($date_filter->show_full_table)
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h2><i class="fa fa-align-justify"></i>Data Per Hour</h2>
                </div>
                <div class="box-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered bootstrap-datatable datatable">
                            <thead>
                            <tr>
                                <th>Hour</th>
                                <th>{{ $date_filter->first_date }} Redirects</th>
                                <th>{{ $date_filter->second_date }} Redirects</th>
                                <th>{{ $date_filter->third_date }} Redirects</th>
                                <th>{{ $date_filter->first_date }} Revenue</th>
                                <th>{{ $date_filter->second_date }} Revenue</th>
                                <th>{{ $date_filter->third_date }} Revenue</th>
                                <th>{{ $date_filter->first_date }} Requests</th>
                                <th>{{ $date_filter->second_date }} Requests</th>
                                <th>{{ $date_filter->third_date }} Requests</th>
                            </tr>
                            </thead>
                            <tbody>
                            @for($hour = 0; $hour < 24; $hour++)
                                <tr>
                                    <td>{{ $hour }}</td>
                                    <td>{{ number_format($summaries['hourly']['first_date'][$hour]['redirects']) }}</td>
                                    <td>{{ number_format($summaries['hourly']['second_date'][$hour]['redirects']) }}</td>
                                    <td>{{ number_format($summaries['hourly']['third_date'][$hour]['redirects']) }}</td>
                                    <td>{{ money_format('%.2n', $summaries['hourly']['first_date'][$hour]['revenue']) }}</td>
                                    <td>{{ money_format('%.2n', $summaries['hourly']['second_date'][$hour]['revenue']) }}</td>
                                    <td>{{ money_format('%.2n', $summaries['hourly']['third_date'][$hour]['revenue']) }}</td>
                                    <td>{{ number_format($summaries['hourly']['first_date'][$hour]['auctions']) }}</td>
                                    <td>{{ number_format($summaries['hourly']['second_date'][$hour]['auctions']) }}</td>
                                    <td>{{ number_format($summaries['hourly']['third_date'][$hour]['auctions']) }}</td>
                                </tr>
                            @endfor
                            </tbody>
                            <tfoot>
                            <td>TOTAL</td>
                            <td>@php number_format($summaries['daily']['first_date']['redirects']) @endphp</td>
                            <td>@php number_format($summaries['daily']['second_date']['redirects']) @endphp</td>
                            <td>@php number_format($summaries['daily']['third_date']['redirects']) @endphp</td>
                            <td>@php money_format('%.2n', $summaries['daily']['first_date']['revenue']) @endphp</td>
                            <td>@php money_format('%.2n', $summaries['daily']['second_date']['revenue']) @endphp</td>
                            <td>@php money_format('%.2n', $summaries['daily']['third_date']['revenue']) @endphp</td>
                            <td>@php number_format($summaries['daily']['first_date']['auctions']) @endphp</td>
                            <td>@php number_format($summaries['daily']['second_date']['auctions']) @endphp</td>
                            <td>@php number_format($summaries['daily']['third_date']['auctions']) @endphp</td>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div><!--/col-->
    </div>
@endif

@endsection

@section('inline-js')
$(function () {
    var date_filter = {!!  json_encode([
            'first_date' => $date_filter->first_date,
            'second_date' => $date_filter->second_date,
            'third_date' => $date_filter->third_date
        ]) !!},
        summaries = {!! json_encode($summaries['hourly']) !!},
        prepared = [];

    for(date in summaries) {
        prepared[date] = {'redirects':[],'revenue':[],'auctions':[], 'cost':[]}
        var summary = summaries[date],
            prev_redirects = 0,
            prev_revenue = 0,
            prev_auctions = 0,
            prev_cost = 0;
        for(hour in summary) {
            var row = summary[hour];
            prev_redirects += parseInt(row.redirects);
            prev_revenue += parseFloat(row.revenue);
            prev_auctions += parseInt(row.auctions);
            prev_cost += parseInt(row.cost);
            prepared[date]['redirects'].push([hour,prev_redirects]);
            prepared[date]['cost'].push([hour,prev_cost]);
            prepared[date]['revenue'].push([hour,prev_revenue]);
            prepared[date]['auctions'].push([hour,prev_auctions]);
        }
    }

    var plot = $.plot($("#pacing-graph-redirect"),
        [
            {
                data: prepared.first_date.redirects,
                label: date_filter.first_date + " redirects",
                color: 'red',
                points: { show: true },
                lines: { show: true }
            },
            {
                data: prepared.second_date.redirects,
                label: date_filter.second_date + " redirects",
                color: 'blue',
                points: { show: true },
                lines: { show: true }
            },
            {
                data: prepared.third_date.redirects,
                label: date_filter.third_date + " redirects",
                color: 'green',
                points: { show: true },
                lines: { show: true }
            }
        ],
        {
            grid: { hoverable: true, clickable: true },
            xaxis: { min: 0, max: 23, ticks: 23 },
            yaxis: { min: 0 },
            legend: { position: 'nw' }
        }
    );

    var plot2 = $.plot($("#pacing-graph-revenue"),
        [
            {
                data: prepared.first_date.revenue,
                label: date_filter.first_date + " revenue",
                color: 'red',
                points: { show: true },
                lines: { show: true }
            },
            {
                data: prepared.second_date.revenue,
                label: date_filter.second_date + " revenue",
                color: 'blue',
                points: { show: true },
                lines: { show: true }
            },
            {
                data: prepared.third_date.revenue,
                label: date_filter.third_date + " revenue",
                color: 'green',
                points: { show: true },
                lines: { show: true }
            }
        ],
        {
            grid: { hoverable: true, clickable: true },
            xaxis: { min: 0, max: 23, ticks: 23 },
            yaxis: { min: 0 },
            legend: { position: 'nw' }
        }
    );

    var plot3 = $.plot($("#pacing-graph-auctions"),
        [
            {
                data: prepared.first_date.auctions,
                label: date_filter.first_date + " auctions",
                color: 'red',
                points: { show: true },
                lines: { show: true }
            },
            {
                data: prepared.second_date.auctions,
                label: date_filter.second_date + " auctions",
                color: 'blue',
                points: { show: true },
                lines: { show: true }
            },
            {
                data: prepared.third_date.auctions,
                label: date_filter.third_date + " auctions",
                color: 'green',
                points: { show: true },
                lines: { show: true }
            }
        ],
        {
            grid: { hoverable: true, clickable: true },
            xaxis: { min: 0, max: 23, ticks: 23 },
            yaxis: { min: 0 },
            legend: { position: 'nw' }
        }
    );

    var plot4 = $.plot($("#pacing-graph-cost"),
        [
            {
                data: prepared.first_date.cost,
                label: date_filter.first_date + " costs",
                color: 'red',
                points: { show: true },
                lines: { show: true }
            },
            {
                data: prepared.second_date.cost,
                label: date_filter.second_date + " cost",
                color: 'blue',
                points: { show: true },
                lines: { show: true }
            },
            {
                data: prepared.third_date.cost,
                label: date_filter.third_date + " cost",
                color: 'green',
                points: { show: true },
                lines: { show: true }
            }
        ],
            {
                grid: { hoverable: true, clickable: true },
                xaxis: { min: 0, max: 23, ticks: 23 },
                yaxis: { min: 0 },
                legend: { position: 'nw' }
            }
        );

    function showTooltip(x, y, contents, bgcolor) {
        $('<div id="tooltip">' + contents + '</div>').css( {
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
    $("#pacing-graph-redirect,#pacing-graph-revenue,#pacing-graph-auctions").bind("plothover", function (event, pos, item) {
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

                if (event.currentTarget.id == "pacing-graph-redirect") {
                    showTooltip(item.pageX, item.pageY,
                                'Hour ' + parseInt(x) + ': ' + commify(parseInt(y)) + ' redirects', bgcolor);
                } else if (event.currentTarget.id == "pacing-graph-auctions"){
                    showTooltip(item.pageX, item.pageY,
                                'Hour ' + parseInt(x) + ': ' + commify(parseInt(y)) + ' auctions', bgcolor);
                } else {
                    showTooltip(item.pageX, item.pageY,
                                'Hour ' + parseInt(x) + ': ' + formatDollar(parseFloat(y)), bgcolor);
                }
            }
        }
        else {
            $("#tooltip").remove();
            previousPoint = null;
            previousColor = null;
        }
    });

});
@endsection



