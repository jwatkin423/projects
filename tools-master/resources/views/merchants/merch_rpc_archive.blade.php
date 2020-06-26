@extends('layouts.base')
{{--@php dd($adv_key); @endphp--}}
@section('content')

    <div class="row">
        <div class="col-lg-12">
            @if(count($errors))
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    {!! Form::open(['action' => 'ReportController@getMerchantRPPCArchives', 'class' => 'form-horizontal', 'method' => 'get', 'autocomplete' => 'off']) !!}
    <div class="row">
        <div class="col-lg-6">
            <div class="box">
                <div class="box-header">
                    <h2><i class="fa fa-calendar"></i>Date Range </h2>
                </div>
                <div class="box-content" id="date-range-filter">

                    <fieldset class="col-lg-12">
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

        <div class="col-lg-6">
            <div class="box">
                <div class="box-header">
                    <h2><i class="fa fa-calendar"></i>Commerce Filters</h2>
                </div>
                <div class="box-content" id="date-range-filter">

                    <fieldset class="col-lg-12">

                        <div class="form-group">
                            {!! Form::label('adv_keys', 'Adv Keys:', ["class" => "control-label"]) !!}
                            <div class="controls">
                                {!! Form::select('adv_key', $adv_keys, $adv_key, ['class' => 'form-control', 'id' => 'adv-key']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('merchant', 'Merchant:', ['class' => 'control-label']) !!}
                            <div class="controls">
                                {!! Form::text('merchant', $merchant_name, ['class' => 'form-control typeahead', 'id' => 'merchant-name']) !!}
                            </div>
                        </div>


                        <div class="form-group">
                            {!! Form::label('merchant_id_hidden', 'Merchant ID:', ['class' => 'control-label']) !!}
                            <div class="controls">
                                @php $merchant_id = $merchant_id === 0 ? '' : $merchant_id; @endphp
                                {!! Form::text('merchant_id', $merchant_id, ['class' => 'form-control typeahead', 'id' => 'merchant-id']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                {!! Form::submit('Generate Merchant Performance Report', ['class' => 'btn btn-success btn-block']) !!}
                            </div>
                        </div>
                    </fieldset>

                </div>
            </div>
        </div><!--/col-->
    </div>

    {!! Form::close() !!}

    <hr>

    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h2><i class="fa fa-align-justify"></i>{{ $merchant_name }} ({{ $adv_key }})</h2>
                </div>
                <div class="box-content" id="campaign-totals">
                    <div class="row">
                        <div class="col-lg-12">
                            <table
                                class="table table-striped table-bordered merchant-table-bordered bootstrap-datatable datatable">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Merchant ID</th>
                                    <th>RON RD Max</th>
                                    <th>Offer Count</th>
                                    <th>RPC Max</th>
                                    <th>RPC Min</th>
                                    <th>RPC Mean</th>
                                    <th>RPC Median</th>
                                    <th>RPC Std Dev</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($merchant_rpc_archive as $mra_row)
                                    @include('merchants.mra_row', ['row' => $mra_row])
                                @endforeach
                            </table>
                        </div><!-- col 12 -->
                    </div>
                </div>
            </div>
        </div><!--/col-->
    </div>

    <!-- Redirects and Requests -->
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h2><i class="fa fa-align-justify"></i>Max Offer RPC by Day for Merchant {{ $merchant_name }}</h2>
                </div>
                <div class="box-content" id="max-rpc">
                    <div class="row">
                        <div id="redirects-requests-graph" class="col-lg-12" style="height:480px"></div>
                    </div>
                </div>
            </div>
        </div><!--/col-->
    </div>

    <!-- Financial Performance -->
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h2><i class="fa fa-align-justify"></i>Median RPC by Day for Merchant {{ $merchant_name }}</h2>
                </div>
                <div class="box-content" id="median-rpc">
                    <div class="row">
                        <div id="financial-performance-graph" class="col-lg-12"
                             style="height:480px"></div>
                    </div>
                </div>
            </div>
        </div><!--/col-->
    </div>

@endsection
@section('inline-js')
{{--<script type="application/javascript">--}}
$(function () {

    var search_url = "{{ route('get-report-merchants') }}";
    var merchant_id_search_url = "{{ route('get-merchant-ids') }}";
    var rpc_data = {!! json_encode($graph_data) !!};
    var max_rpcs = rpc_data.max_rpcs;
    var median_rpcs = rpc_data.median_rpcs;

    /** plot section **/
    var plot = $.plot($("#redirects-requests-graph"),
        [
            {
                data: max_rpcs,
                label: "RPCs Max",
                color: 'blue',
                points: {show: true},
                lines: {show: true}
            }
        ],
        {
            grid: {hoverable: true, clickable: true},
            xaxis: {mode: 'time', timeformat: '%Y-%m-%d', minTickSize: [1, 'day']},
            yaxis: {min: 0},
            legend: {position: 'ne'}
        }
    );

    var plot2 = $.plot($("#financial-performance-graph"),
        [
            {
                data: median_rpcs,
                label: "RPCs Median",
                color: 'green',
                points: {show: true},
                lines: {show: true}
            }
        ],
        {
            grid: {hoverable: true, clickable: true},
            xaxis: {mode: 'time', timeformat: '%Y-%m-%d', minTickSize: [1, 'day']},
            yaxis: {min: 0},
            legend: {position: 'ne'}
        }
    );

    /** end plot section **/

    /** TYPEAHEAD section **/

    var merchantIDsBH = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('adv_key'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: merchant_id_search_url + '?q=',
            prepare: function (query, settings) {
                var adv_key = encodeURIComponent($('#adv-key').find(":selected").text());
                settings.url += query + '&adv_key=' + adv_key;
                return settings;
            }
        }
    });

    merchantIDsBH.initialize();

    $('#merchant-id').typeahead(null, {
        hint: true,
        highlight: true,
        minLength: 2,
        name: 'merchants',
        display: 'merchant_id',
        source: merchantIDsBH.ttAdapter(),
        templates: {
            empty: ['<div class = "no-items"> ' +
            '<p class="alert alert-danger">',
                '<strong>No Merchant IDs Found</strong>',
                '</p></div>'
            ].join('\n')
        }
    }).on('typeahead:selected', function (obj, datum) {
        $("#merchant-name").val(datum['merchant_name']);
        $('#adv-key').val(datum['adv_key']);
    });


    // Bloodhound Engine for merchant name
    var merchantsBH = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('merchant_name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: search_url + '?query=',
            prepare: function (query, settings) {
                var adv_key = encodeURIComponent($('#adv-key').find(":selected").text());
                settings.url += query + '&adv_key=' + adv_key;
                return settings;
            }
        }
    });

    // Typeahead by merchant name
    merchantsBH.initialize();

    $('#merchant-name').typeahead(null, {
        hint: true,
        highlight: true,
        minLength: 3,
        name: 'merchants',
        display: 'merchant_name',
        source: merchantsBH.ttAdapter(),
        templates: {
            empty: ['<div class = "no-items" > ' +
            '<p class="alert alert-danger">',
                '<strong>No Items Found</strong>',
                '</p></div>'
            ].join('\n')
        }
    }).on('typeahead:selected', function (obj, datum) {
        $("#merchant-id").val(datum['id']);
        $('#adv-key').val(datum['adv_key']);
    });

    var previousPoint = null,
        previousColor = null;
    $("#redirects-requests-graph,#financial-performance-graph").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));


        if (item) {
            var bgcolor = item.series.color;
            if (previousPoint != item.dataIndex || previousColor != bgcolor) {
                var x = item.datapoint[0],
                    y = item.datapoint[1];


                // create date for the tooltip.
                var d = new Date(x),
                    day = d.getUTCDate(),
                    year = d.getUTCFullYear(),
                    month = d.getUTCMonth() + 1;

                // tack on a leading 0 if the date is less than 10
                if (day < 10) {
                    day = '0' + day
                }

                // build the date
                var display_date = year + '-' + month + '-' + day;
                var id = event.currentTarget.id;

                previousPoint = item.dataIndex;
                previousColor = bgcolor;

                $("#tooltip").remove();
                var x = item.datapoint[0].toFixed(2),
                    y = item.datapoint[1].toFixed(2);

                if (id == "redirects-requests-graph") {
                    showTooltip(item.pageX, item.pageY,
                        commify(parseInt(y)) + ' (' + display_date + ')', bgcolor);
                } else {
                    showTooltip(item.pageX, item.pageY,
                        formatDollar(parseFloat(y)) + ' (' + display_date + ')', bgcolor);
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
