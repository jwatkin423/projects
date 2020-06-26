@extends('layouts.base')



@section('content')
@php
$auctions = 0;
$redirects = 0;
$revenue = 0;
// total sum_cnv
$total_cnv = 0;
// total cnv rate
$cnv_rate = 0;
// total cost
$cost = 0;
// num of records
$count = count($mbd_data) ?? 0;
// total cnv rate
$total_cnv_rate = 0;
// sum/total cnv
$total_cnv = 0;
// total CPR
$total_cpr = 0;
// revenue type
$rev_type = "Raw ";

@endphp
@foreach ($mbd_data as $mbd_row)
@php $auctions += $mbd_row['auc']; @endphp
@php $cost += $mbd_row['cost']; @endphp
@php
    if ($display === 'log_rev') {
        $redirects += (float)$mbd_row['raw_rd'];
        $revenue += (float)$mbd_row['raw_rev'];
        $rev_type = "Raw ";
    } else {
        $redirects += (float)$mbd_row['true_rd'];
        $revenue += (float)$mbd_row['true_rev'];
        $rev_type = "True ";
    }

@endphp
@php $total_cnv += (float)$mbd_row['sum_cnv']; @endphp

@endforeach

@php

if ($count > 0) {
    if ($redirects != 0) {
        $total_cnv_rate = ($total_cnv / $redirects) * 100;
        $total_cpr = $cost / $redirects;
    } else {
        $total_cnv_rate = 0;
        $total_cpr = 0;
    }

}

@endphp

@if(count($errors))
<div class="row">
    <div class="col-xs-12">

        <div class="alert alert-danger">
            <ul>
                @foreach($errors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

    </div>
</div>
@endif

{!! Form::open(['action' => 'ReportController@getMerchantBreakDown', 'class' => 'form-horizontal', 'method' => 'get', 'autocomplete' => 'off']) !!}
<div class="row">

<div class="col-lg-6 col-xs-12">
    <div class="box">
        <div class="box-header">
            <h2><i class="fa fa-calendar"></i>Date Range </h2>
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

<div class="col-lg-6 col-xs-12">
    <div class="box">
        <div class="box-header">
            <h2><i class="fa fa-calendar"></i>Commerce Filters</h2>
        </div>
        <div class="box-content" id="date-range-filter">

            <fieldset class="col-xs-12">

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
                <div class="form-group">
                    {!! Form::label('limit', 'Number of Results:', ['class' => 'control-label']) !!}
                    <div class="controls">
                        @if ($limit == 10)
                            <input type="radio" name="limit" value="10" checked> 10
                        @else
                            <input type="radio" name="limit" value="10"> 10
                        @endif

                        @if ($limit == 20)
                            <input type="radio" name="limit" value="20" checked> 20
                        @else
                            <input type="radio" name="limit" value="20"> 20
                        @endif

                        @if ($limit == 'all')
                            <input type="radio" name="limit" value="all" checked> All
                        @else
                            <input type="radio" name="limit" value="all"> All
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('sort', 'Specify Default Sort:', ['class' => 'control-label']) !!}
                    <div class="controls">
                        <div class="col-xs-4">
                            @if ($sort == 'auc')
                                <input type="radio" name="sort" value="auc" checked> Auctions Descending
                            @else
                                <input type="radio" name="sort" value="auc"> Auctions Descending
                            @endif
                        </div>
                        <div class="col-xs-4">
                            @if ($sort == 'raw_rd')
                                <input type="radio" name="sort" value="raw_rd" checked> Redirects Descending
                            @else
                                <input type="radio" name="sort" value="raw_rd"> Redirects Descending
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('display', 'Display Revenue:', ['class' => 'control-label']) !!}
                    <div class="col-xs-6">
                        <div class="controls">
                            @if ($display == 'log_rev')
                                <input type="radio" name="display" value="log_rev" checked> Logged Revenue
                            @else
                                <input type="radio" name="display" value="log_rev"> Logged Revenue
                            @endif
                        </div>
                        <div class="col-xs-6">
                            <div class="controls">
                                @if ($display == 'true_rev')
                                    <input type="radio" name="display" value="true_rev" checked> True Revenue
                                @else
                                    <input type="radio" name="display" value="true_rev"> True Revenue
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-xs-12">
                            {!! Form::submit('Generate Domain Breakdown by Merchant', ['class' => 'btn btn-success btn-block']) !!}
                        </div>
                    </div>
                </div>
            </fieldset>

        </div>
    </div>
</div><!--/col-->

</div><!-- form row -->
{!! Form::close() !!}
<hr>

<div class="row">
<div class="col-lg-12">
    <div class="box">
        <div class="box-header">
            <h2><i class="fa fa-align-justify"></i>{{ $merchant_name }} </h2>
        </div>
        <div class="box-content">

            <div class="row">

                <!-- Auctions -->
                <div class="col-xs-6 col-lg-3">
                    <div class="smallstat smallstat-dark box">
                        <span class="title">Auctions</span>
                        <span class="value domain-bkd">{{ number_format($auctions) }}</span>
                    </div>
                </div> <!--/col-->


                <!-- Redirects -->
                <div class="col-xs-6 col-lg-3">
                    <div class="smallstat smallstat-dark box">
                        <span class="title">{{ $rev_type }}Redirects</span>
                        <span class="value domain-bkd">{{ number_format($redirects) }}</span>
                    </div>
                </div> <!--/col-->

                <!-- Cost -->
                <div class="col-xs-6 col-lg-3">
                    <div class="smallstat smallstat-dark box">
                        <span class="title">Cost</span>
                        <span class="value domain-bkd">{{ money_format('%.2n', $cost) }}</span>
                    </div>
                </div> <!--/col-->

                <!-- Raw/True Revenue -->
                <div class="col-xs-6 col-lg-3">
                    <div class="smallstat smallstat-dark box">
                        <span class="title">{{ $rev_type }}Revenue</span>
                        <span class="value domain-bkd">{{ money_format('%.2n', $revenue) }}</span>
                    </div>
                </div> <!--/col-->

                <!-- Profit -->
                <div class="col-xs-6 col-lg-3">
                    <div class="smallstat smallstat-dark box">
                        <span class="title">Profit</span>
                        <span class="value domain-bkd">{{ money_format('%.2n', ($revenue - $cost)) }}</span>
                    </div>
                </div> <!--/col-->

                <!-- Total CNV -->
                <div class="col-xs-6 col-lg-3">
                    <div class="smallstat smallstat-dark box">
                        <span class="title">Total CNV</span>
                        <span class="value domain-bkd">{{ number_format($total_cnv) }}</span>
                    </div>
                </div> <!--/col-->


                <!-- Total CNV Rate -->
                <div class="col-xs-6 col-lg-3">
                    <div class="smallstat smallstat-dark box">
                        <span class="title">Total CNV Rate</span>
                        <span class="value domain-bkd">{{ number_format($total_cnv_rate, 3) }}%</span>
                    </div>
                </div> <!--/col-->


                <!-- Total CPR -->
                <div class="col-xs-6 col-lg-3">
                    <div class="smallstat smallstat-dark box">
                        <span class="title">Total CPR</span>
                        <span class="value domain-bkd">{{ money_format('%.4n', $total_cpr) }}</span>
                    </div>
                </div> <!--/col-->
            </div>

            <div class="table-responsive">
                <table class="table table-striped merchant-table-bordered bootstrap-datatable datatable">
                    <thead>
                    <tr>
                        <th style="display: none;">hide this row</th>
                        <th>API ID</th>
                        <th>Domain</th>
                        <th>Auctions</th>
                        @if($display === 'log_rev')
                            <th>Raw Redirects</th>
                            <th>Win %</th>
                        @else
                            <th>True Redirects</th>
                            <th>Win %</th>
                        @endif
                        <th>Cost</th>
                        @if($display === 'log_rev')
                            <th>Raw Revenue</th>
                        @else
                            <th>True Revenue</th>
                        @endif
                        <th>Profit</th>
                        <th>CPR</th>
                        <th>Max Bid</th>
                        <th>CNV</th>
                        <th>CNV Rate</th>
                    </tr>

                    </thead>
                    <tbody>
                    @foreach ($mbd_data as $mbd_row)
                        @include('merchants.merchant_breakdown_row', ['row' => $mbd_row])
                    @endforeach
                    </tbody>
                </table>
            </div><!-- table responsive -->
        </div>
    </div>
</div>


@endsection
@section('inline-js')
{{--            <script type="application/javascript">--}}
        $(function () {

            var search_url = "{{ route('get-report-merchants') }}";
            var merchant_id_search_url = "{{ route('get-merchant-ids') }}";


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
