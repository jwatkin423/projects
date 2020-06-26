@extends('layouts.base')

@section('content')
   {{ Form::open(['action' => 'ReportController@getTrafficDistribution', 'class' => 'form-horizontal', 'method' => 'get']) }}
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="box">
                <div class="box-header">
                    <h2><i class="fa fa-calendar"></i>Date Range</h2>
                </div>
                <div class="box-content" id="date-range-filter">
                    <fieldset class="col-sm-12">
                        <div class="form-group">
                            {{ Form::label('from', 'Start Date', ["class" => "control-label"]) }}
                            <div class="controls">
                                <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
                                    <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                    {{ Form::text('from', $date_filter->from, ['class' => 'form-control from']) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('to', 'End Date', ["class" => "control-label"]) }}
                            <div class="controls">
                                <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
                                    <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                                    {{ Form::text('to', $date_filter->to, ['class' => 'form-control to']) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <a href="{{ sprintf("javascript:setDateFilterValues('%s', '%s');",
                            $today_date_filter->from, $today_date_filter->to) }}"
                               class="btn btn-info btn-block">Today</a>
                            <a href="{{ sprintf("javascript:setDateFilterValues('%s', '%s');",
                            $yesterday_date_filter->from, $yesterday_date_filter->to) }}"
                               class="btn btn-info btn-block">Yesterday</a>
                            <a href="{{ sprintf("javascript:setDateFilterValues('%s', '%s');",
                            $week_ago_date_filter->from, $week_ago_date_filter->to) }}" class="btn btn-info btn-block">Last
                                week</a>
                            <script>
                                function setDateFilterValues(from, to) {
                                    $("#from").val(from);
                                    $("#to").val(to);
                                }
                            </script>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <!--/col-->

        <div class="col-lg-6 col-md-6">
            <div class="box">
                <div class="box-header">
                    <h2><i class="fa fa-align-justify"></i>Traffic</h2>
                </div>
                <div class="box-content" id="pick-your-filters">
                    <fieldset class="col-sm-12">

                        <div class="form-group">
                            <label class="radio-inline">
                                <input type="radio" name="traffic" id="advertisers"
                                       value="advertiser" @if($filter->traffic == "advertiser" || is_null($filter->traffic))
                                       checked="true" @endif>
                                Advertisers
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="traffic" id="partners"
                                       value="partner" @if($filter->traffic == "partner") checked="true" @endif >
                                Partners
                            </label>
                        </div>
                        <div class="form-group">
                            {{ Form::label('advertiser', 'Campaign:', ["class" => "control-label"]) }}
                            <div class="controls">
                                {{ Form::select('advertiser', $advertisers, $filter->advertiser, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('partner','API Partner:', ["class" => "control-label"]) }}
                            <div class="controls">
                                {{ Form::select('partner', $partners, $filter->partner, ['class' => 'form-control']) }}
                            </div>
                        </div>

                    </fieldset>
                </div>
            </div>
        </div>
        <!--/col-->
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">{{ Form::submit('Generate Traffic Breakdown Charts', ['id'=>'generate-btn','class' => 'btn btn-success btn-block btn-lg']) }}</div>
        </div>
    </div>
    {{ Form::close() }}
    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h2><i class="fa fa-download"></i>Auctions</h2>

                </div>
                <div class="box-content" id="reqs">
                    <div id="requests-chart" style="width: 100%; height: 200px"></div>
                    <table class="table table-stripped bootstrap-datatable datatable">
                        <thead>
                        <tr>
                            @if($filter->traffic == 'partner')
                                <th>Advertiser</th>
                            @else
                                <th>Partner</th>
                            @endif
                            <th>Requests</th>
                            <th>Pct</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($aggregate as $row)
                            <tr>
                                @if($filter->traffic == 'partner')
                                    <td>{{ $row->adv_code }}_{{ $row->campaign_code }}</td>
                                @else
                                    <td>{{ $row->api_id_external }} ({{ $row->api_name }})</td>
                                @endif
                                <td> {{ number_format($row->auctions) }}</td>
                                <td> {{ $total_requests == 0 ? 0.00 : number_format($row->auctions/$total_requests * 100,2) }}%</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h2><i class="fa fa-refresh"></i>Redirects</h2>
                </div>
                <div class="box-content" id="reds">
                    <div id="redirects-chart" style="width: 100%; height: 200px"></div>
                    <table class="table table-stripped bootstrap-datatable datatable">
                        <thead>
                        <tr>
                            <th>Advertiser</th>
                            <th>Redirects</th>
                            <th>Pct</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($aggregate as $row)
                            <tr>
                                @if($filter->traffic == 'partner')
                                    <td>{{ $row->adv_code }}_{{ $row->campaign_code }}</td>
                                @else
                                    <td>{{ $row->api_id_external }} ({{ $row->api_name }})</td>
                                @endif
                                <td> {{ number_format($row->redirects) }}</td>
                                <td> {{ $total_redirects == 0 ? 0.00 : number_format($row->redirects/$total_redirects * 100,2) }}%</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h2><i class="fa fa-money"></i>Revenue</h2>
               </div>
                <div class="box-content" id="rev">
                    <div id="revenue-chart" style="width: 100%; height: 200px"></div>
                    <table class="table table-stripped bootstrap-datatable datatable">
                        <thead>
                        <tr>
                            <th>Advertiser</th>
                            <th>Revenue</th>
                            <th>Pct</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($aggregate as $row)
                            <tr>
                                @if($filter->traffic == 'partner')
                                    <td>{{ $row->adv_code }}_{{ $row->campaign_code }}</td>
                                @else
                                    <td>{{ $row->api_id_external }} ({{ $row->api_name }})</td>
                                @endif
                                <td> ${{ number_format($row->revenue,2) }}</td>
                                <td> {{ $total_revenue == 0 ? 0.00 : number_format($row->revenue/$total_revenue * 100,2) }}%</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
    @section('inline-js')
        var requests = [];
        var redirects = [];
        var revenue = [];
        @foreach($aggregate as $row)
         requests.push({
             label : "{{$filter->traffic == 'advertiser'? $row->api_id_external." (".$row->api_name.")": $row->adv_code."_".$row->campaign_code}}",
             data : {{$row->auctions}}
         });

        redirects.push({
             label : "{{$filter->traffic == 'advertiser'? $row->api_id_external." (".$row->api_name.")": $row->adv_code."_".$row->campaign_code}}",
             data : {{$row->redirects}}
         });

        revenue.push({
             label : "{{$filter->traffic == 'advertiser'? $row->api_id_external." (".$row->api_name.")": $row->adv_code."_".$row->campaign_code}}",
             data : {{$row->revenue}}
         });
        @endforeach


        function changeTrafficType(type) {
            $('[name="traffic"]').parents('.form-group').siblings().find('select').attr('disabled', true);
            $('[name="' + type + '"]').removeAttr('disabled');
            $('select[disabled="disabled"]').children().first().attr('selected',true);
        }

        $(document).ready(function () {
            $('[name="traffic"]').change(function () {
                changeTrafficType($(this).val());
            });
            changeTrafficType($('[name="traffic"][checked]').val());


            $('.datatable').each(function(index, elem){
                $(elem).dataTable().fnSort( [ [2,'desc'] ] );
            });




            $.plot('#requests-chart',requests, {
                series: {
                    pie: {
                        show: true,
                        label : {
                            show: false
                        },
                        combine: {
                            threshold: 0.05,
                            label: "(others)"
                        }
                    }
                },
                grid: {
                    hoverable: true
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%s - %p.2%",
                    shifts: {
                        x: 20,
                        y: 0
                    },
                    defaultTheme: false
                }

            });

            $.plot('#redirects-chart',redirects, {
                series: {
                    pie: {
                        show: true,
                        label : {
                            show: false
                        },
                        combine: {
                            threshold: 0.05,
                            label: "(others)"
                        }
                    }
                },
                grid: {
                    hoverable: true
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%s - %p.2%",
                    shifts: {
                        x: 20,
                        y: 0
                    },
                    defaultTheme: false
                }
            });

            $.plot('#revenue-chart',revenue, {
                series: {
                    pie: {
                        show: true,
                        label : {
                            show: false
                        },
                        combine: {
                            threshold: 0.05,
                            label: "(others)"
                        }
                    }
                },
                grid: {
                    hoverable: true
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%s - %p.2%",
                    shifts: {
                        x: 20,
                        y: 0
                    },
                    defaultTheme: false
                }
            });


        });
@endsection

