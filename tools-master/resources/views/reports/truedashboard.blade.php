@extends('layouts.base')

@section('content')
  <div class="row">
    <div class="col-8">
      <div class="row">
        <div class="col-xs-6 col-lg-3">
          <div class="smallstat box">
            <i class="fa fa-download red"></i>
            <span class="title">Cost</span>
            <span class="value">{{ money_format('%.2n', $summary['total']['cost']) }}</span>
          </div>
        </div> <!--/col-->

        <div class="col-xs-6 col-lg-3">
          <div class="smallstat box">
            <i class="fa fa-upload yellow"></i>
            <span class="title">Revenue</span>
            <span class="value">{{ money_format('%.2n', $summary['total']['revenue']) }}</span>
          </div>
        </div><!--/col-->

        <div class="col-xs-6 col-lg-3">
          <div class="smallstat box">
            <i class="fa fa-dollar green"></i>
            <span class="title">Profit</span>
            <span class="value">{{ money_format('%.2n', $summary['total']['profit']) }}</span>
          </div>
        </div><!--/col-->

        <div class="col-xs-6 col-lg-3">
          <div class="smallstat box">
            <i class="fa fa-money blue"></i>
            <span class="title">ROI</span>
            <span class="value">
              @if($summary['total']['roi'] != -1)
                {{ sprintf("%.2f", $summary['total']['roi']) }}%
              @else
                N/A
              @endif
                    </span>
          </div>
        </div><!--/col-->

        <div class="col-xs-6 col-lg-3">
          <div class="smallstat box">
            <span class="title">Redirects</span>
            <span class="value">{{ number_format($summary['total']['redirects']) }}</span>
          </div>
        </div> <!--/col-->

        <div class="col-xs-6 col-lg-3">
          <div class="smallstat box">
            <span class="title">CPR</span>
            <span class="value">
              @if($summary['total']['cpr'] != -1)
                {{ money_format('%.4n', $summary['total']['cpr']) }}
              @else
                N/A
              @endif
                    </span>
          </div>
        </div> <!--/col-->

        <div class="col-xs-6 col-lg-3">
          <div class="smallstat box">
            <span class="title">RPR</span>
            <span class="value">
              @if($summary['total']['rpr'] != -1)
                {{ money_format('%.4n', $summary['total']['rpr']) }}
              @else
                N/A
              @endif
                    </span>
          </div>
        </div> <!--/col-->

      </div> <!-- /row -->
    </div> <!-- /row -->

    <div class="col-lg-4 col-md-4">

      <div class="box">
        <div class="box-header">
          <h2><i class="fa fa-calendar"></i>Select Advertiser and Date Range</h2>
        </div>
        <div class="box-content">
          {{ Form::model($date_filter, ['action' => 'ReportController@getTrueDashboard', 'class' => 'form-horizontal', 'method' => 'get']) }}
          <fieldset class="col-sm-12">
            <div class="form-group">
              {!! Form::label('campaign', 'Campaign:', ["class" => "control-label"]) !!}
              <div class="controls">
                {!! Form::select('rev_code', $rev_codes, $rev_code, ['class' => 'form-control']) !!}
              </div>
            </div>
            <div class="form-group">
              {{ Form::label('from', 'Start Date', ["class" => "control-label"]) }}
              <div class="controls">
                <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
                  <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                  {{ Form::text('from', $from, ['class' => 'form-control', 'id' => 'fromDate']) }}
                </div>
              </div>
            </div>
            <div class="form-group">
              {{ Form::label('to', 'End Date', ["class" => "control-label"]) }}
              <div class="controls">
                <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
                  <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                  {{ Form::text('to', $to, ['class' => 'form-control', 'id' => 'toDate']) }}
                </div>
              </div>
            </div>
            <div class="form-actions">
              {{ Form::submit('Update', ['class' => 'btn btn-success btn-block']) }}
              <div class="row">
                <div class="col-sm-6">
                  {{ link_to_action('ReportController@getTrueDashboard', 'Today', [
                      'from' => $today_date_filter->from,
                      'to' => $today_date_filter->to
                  ], ['data-source' => 'today', 'class' => 'truedashboard-today btn btn-info btn-block date-today mrg-top-10']) }}
                </div>
                <div class="col-sm-6">
                  {{ link_to_action('ReportController@getTrueDashboard', 'Yesterday', [
                      'from' => $yesterday_date_filter->from,
                      'to' => $yesterday_date_filter->to
                  ], ['data-source' => 'yesterday', 'class' => 'truedashboard-yesterday btn btn-info btn-block date-yesterday mrg-top-10']) }}
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  {{ link_to_action('ReportController@getTrueDashboard', 'Last Week', [
                      'from' => $last_week_date_filter->from,
                      'to' => $last_week_date_filter->to
                  ], ['data-source' => 'last-week', 'class' => 'truedashboard-last-week btn btn-info btn-block date-last-week mrg-top-10']) }}
                </div>
                <div class="col-6">
                  {{ link_to_action('ReportController@getTrueDashboard', 'Month to Date', [
                      'from' => $month_to_date_date_filter->from,
                      'to' => $month_to_date_date_filter->to
                  ], ['data-source' => 'monthToDate', 'class' => 'truedashboard-mtd btn btn-info btn-block date-month-to-date mrg-top-10']) }}
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  {{ link_to_action('ReportController@getTrueDashboard', '30 Days', [
                      'from' => $thirty_days_date_filter->from,
                      'to' => $thirty_days_date_filter->to
                  ], ['data-source' => 'thirty-days', 'class' => 'truedashboard-thirty-days btn btn-info btn-block date-last-month mrg-top-10']) }}
                </div>
                <div class="col-sm-6">
                  {{ link_to_action('ReportController@getTrueDashboard', 'Last Month', [
                      'from' => $last_month_date_filter->from,
                      'to' => $last_month_date_filter->to
                  ], ['data-source' => 'lastMonth', 'class' => 'truedashboard-last-month btn btn-info btn-block date-last-month mrg-top-10']) }}
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  {{ link_to_action('ReportController@getTrueDashboard', '-1 Day', [
                      'from' => $yesterday_date_filter->from,
                      'to' => $yesterday_date_filter->to
                  ], ['data-source' => 'minus-one-day', 'class' => 'truedashboard-minus-one-day btn btn-info btn-block mrg-top-10', 'id' => 'minus-one']) }}
                </div>
                <div class="col-sm-6">
                  {{ link_to_action('ReportController@getTrueDashboard', '+1 Day', [
                      'from' => $yesterday_date_filter->from,
                      'to' => $yesterday_date_filter->to
                  ], ['data-source' => 'plus-one-day', 'class' => 'truedashboard-plus-one-day btn btn-info btn-block mrg-top-10', 'id' => 'plus-one']) }}
                </div>
              </div>
            </div>
          </fieldset>
          {{ Form::close() }}
        </div>
      </div>
    </div><!--/col-->

  </div>
  <!-- TABLE SECTION -->
  <div class="row">
    <div class="col-lg-12">
      @if($summary['total']['row_count'] > 0)
      <div class="box">
          <div class="box-header">
            <h2><i class="fa fa-align-justify"></i>Totals for {{ $rev_code_title }}</h2>
          </div>
          <div class="box-content">
              <div class="table-responsive">
                <table class="table table-striped table-hover job-alerts-table bootstrap-datatable datatable">
                  <thead>
                  <th>Date Report</th>
                  <th>Cost</th>
                  <th>Revenue</th>
                  <th>Profit @if($summary['total']['adv_code'] == 'ecn')**@endif </th>
                  <th>ROI</th>
                  <th>Redirects</th>
                  <th>CPR</th>
                  <th>RPR</th>
                  <th>% Breakage Revenue</th>
                  <th>% Breakage Redirects</th>
                  </thead>
                  <tbody>
                  @php

                      $cost_total = $summary_total['cost'];
                      $revenue_total = $summary_total['revenue'];
                      $profit_total = $summary_total['profit'];
                      $roi_total = $summary_total['roi'];
                      $redirects_total = $summary_total['redirects'];
                      $cpr_total = $summary_total['cpr'];
                      $rpr_total = $summary_total['rpr'];
                      $breakage_total = $summary_total['breakage_rd'];
                      $breakage_rev_total = $summary_total['breakage_rev'] * 100;

                  @endphp
                @foreach($summary as $date => $row)
                  @if($date != 'total')
                    @include('reports.truerowdb', $row)
                  @endif
                @endforeach
                  <tfoot>
                  <th>Total</th>
                    <td>{{ money_format('%.2n', $cost_total) }}</td>
                    <td>{{ money_format('%.2n', $revenue_total) }}</td>
                    <td>{{ money_format('%.2n', $profit_total) }}</td>
                    <td>{{ sprintf('%.2f', $roi_total) }}%</td>
                    <td>{{ number_format($redirects_total) }}</td>
                    <td>{{ money_format('%.4n', $cpr_total) }}</td>
                    <td>{{ money_format('%.4n', $rpr_total) }}</td>
                    <td>{{ sprintf('%.2f',$breakage_rev_total) }}%</td>
                    <td>{{ round($breakage_total, 2, PHP_ROUND_HALF_UP) }}%</td>
                  </tfoot>
                  </tbody>
                </table>
              </div>
          </div>
      </div>
      @else
        <h2 class="alert alert-danger">No data available **</h2>
      @endif
    </div>
  </div>

  @if ($summary['total']['row_count'] > 0)
    <div class="row">
      <div class="col-md-12">
        <p class=""><em>*Breakage data for today's date ({{ date('m/d/Y', time()) }}) may not available until after 2:00
            PM</em></p>
        @if($summary['total']['adv_code'] == 'ecn')
        <p class=""><em>** profit calculation takes into account Tamir's profit split of 40% and isn't revenue - cost.</em></p>
        @endif
      </div>
    </div>
  @endif
  <!-- END TABLE SECTION -->

  @if($summary['total']['row_count'] > 0)
  <!-- GRAPHS SECTION -->
  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        <div class="box-header">
          <h2><i class="fa fa-align-justify"></i>Click Volume by Date</h2>
        </div>
        <div class="box-content" id="breakage-percent">
          <div class="row">
            <div id="true-click-volume-graph" class="col-lg-10 col-lg-offset-1" style="height:480px"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        <div class="box-header">
          <h2><i class="fa fa-align-justify"></i>Revenue by Date</h2>
        </div>
        <div class="box-content" id="breakage-absolute">
          <div class="row">
            <div id="true-revenue-graph" class="col-lg-10 col-lg-offset-1" style="height:480px"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        <div class="box-header">
          <h2><i class="fa fa-align-justify"></i>Cost By Date</h2>
        </div>
        <div class="box-content" id="breakage-revenue">
          <div class="row">
            <div id="true-cost-graph" class="col-lg-10 col-lg-offset-1" style="height:480px"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        <div class="box-header">
          <h2><i class="fa fa-align-justify"></i>Profit by Date</h2>
        </div>
        <div class="box-content" id="breakage-rev-diff">
          <div class="row">
            <div id="true-profit-graph" class="col-lg-10 col-lg-offset-1" style="height:480px"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        <div class="box-header">
          <h2><i class="fa fa-align-justify"></i>Breakage Percentage by Date</h2>
        </div>
        <div class="box-content" id="breakage-rev-diff">
          <div class="row">
            <div id="true-breakage-percentage-graph" class="col-lg-10 col-lg-offset-1" style="height:480px"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
@endsection

@section('inline-js')
  {{--<script type="text/javascript">--}}
      $(function () {



          var graph_data = {!! json_encode($graph_data) !!};

          var click_plot = $.plot($("#true-click-volume-graph"),
              [
                  {
                      data: graph_data.clicks,
                      label: "Clicks",
                      color: 'red',
                      points: {show: true},
                      lines: {show: true}
                  }
              ],
              {
                  grid: {hoverable: true, clickable: true},
                  yaxis: {min: -10},
                  xaxis: {mode: 'time', timeformat: '%Y-%m-%d', minTickSize: [1, 'day']},
                  legend: {position: 'ne'}
              }
          );

          var revenue_plot = $.plot($("#true-revenue-graph"),
              [
                  {
                      data: graph_data.revenue,
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
                  legend: {position: 'ne'}
              }
          );

          var cost_plot = $.plot($("#true-cost-graph"),
              [
                  {
                      data: graph_data.cost,
                      label: "Portal Revenue",
                      color: 'purple',
                      points: {show: true},
                      lines: {show: true}
                  }
              ],
              {
                  grid: {hoverable: true, clickable: true},
                  yaxis: {min: 0},
                  xaxis: {mode: 'time', timeformat: '%Y-%m-%d', minTickSize: [1, 'day']},
                  legend: {position: 'ne'}
              }
          );

          var profit_plot = $.plot($("#true-profit-graph"),
              [
                  {
                      data: graph_data.profit,
                      label: "Profit",
                      color: 'green',
                      points: {show: true},
                      lines: {show: true}
                  }
              ],
              {
                  grid: {hoverable: true, clickable: true},
                  yaxis: {min: -8},
                  xaxis: {mode: 'time', timeformat: '%Y-%m-%d', minTickSize: [1, 'day']},
                  legend: {position: 'ne'}
              }
          );

          var breakage_plot = $.plot($("#true-breakage-percentage-graph"),
              [
                  {
                      data: graph_data.breakage,
                      label: "Breakage Percentage",
                      color: 'orange',
                      points: {show: true},
                      lines: {show: true}
                  }
              ],
              {
                  grid: {hoverable: true, clickable: true},
                  yaxis: {min: -50},
                  xaxis: {mode: 'time', timeformat: '%Y-%m-%d', minTickSize: [1, 'day']},
                  legend: {position: 'ne'}
              }
          );

          function showTooltip(x, y, contents, bgcolor) {
              $('<div id="tooltip">' + contents + '</div>').css({
                  position: 'absolute',
                  display: 'none',
                  top: y + 20,
                  left: x + 20,
                  padding: '2px',
                  'background-color': bgcolor,
                  opacity: 0.70,
                  color: '#fff'
              }).appendTo("body").fadeIn(200);
          }

          var previousPoint = null,
              previousColor = null;

          $("#true-breakage-percentage-graph, #true-profit-graph, #true-cost-graph, #true-revenue-graph, #true-click-volume-graph").bind("plothover", function (event, pos, item) {
              $("#x").text(pos.x.toFixed(2));
              $("#y").text(pos.y.toFixed(2));

              if (item) {
                  var bgcolor = item.series.color;
                  if (previousPoint != item.dataIndex || previousColor != bgcolor) {
                      previousPoint = item.dataIndex;
                      previousColor = bgcolor;

                      $("#tooltip").remove();
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

                      if (id == "true-click-volume-graph") {
                          showTooltip(item.pageX, item.pageY, commify(y) + ' (' + display_date + ')', bgcolor);
                      } else if (id == "true-profit-graph" || id == "true-cost-graph" || id == "true-revenue-graph") {
                          showTooltip(item.pageX, item.pageY, formatDollar(y) + ' (' + display_date + ')', bgcolor);
                      } else if (id == "true-breakage-percentage-graph") {
                          showTooltip(item.pageX, item.pageY, y + '%' + ' (' + display_date + ')', bgcolor);
                      }
                      else {
                          showTooltip(item.pageX, item.pageY, y + '%' + ' (' + display_date + ')', bgcolor);
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
