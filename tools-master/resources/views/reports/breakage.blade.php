@extends('layouts.base')

@section('content')
  <div class="row">
    <div class="col-lg-6">
      <div class="box">
        <div class="box-header">
          <h2><i class="fa fa-align-justify"></i>Select Campaign</h2>
        </div>
        <div class="box-content" id="date-range-filter">
          {!! Form::model($date_filter, ['route' => 'pacing-traffic-breakage', 'class' => 'form-horizontal', 'method' => 'get']) !!}
          <fieldset class="col-lg-12">
            <div class="form-group">
              {!! Form::label('campaign', 'Campaign:', ["class" => "control-label"]) !!}
              <div class="controls">
                {!! Form::select('rev_code', $rev_codes, $rev_code, ['class' => 'form-control']) !!}
              </div>
            </div>
          </fieldset>
        </div>
      </div>
    </div><!--/col-->

    <div class="col-lg-6">
      <div class="box">
        <div class="box-header">
          <h2><i class="fa fa-calendar"></i>Select Date Range</h2>
        </div>
        <div class="box-content" id="date-range-filter">
          {!! Form::model($date_filter, ['action' => 'ReportController@getTrafficBreakage', 'class' => 'form-horizontal', 'method' => 'get']) !!}
          <fieldset class="col-lg-12">

            <div class="form-group">
              {!! Form::label('date_from', 'Date From:', ["class" => "control-label"]) !!}
              <div class="controls">
                <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
                  <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                  {!! Form::text('date_from', $current_date_from, ['class' => 'form-control mrg-top-10', 'id' => 'breakage-date-from']) !!}
                </div>
              </div>
            </div>

            <div class="form-group">
              {!! Form::label('date_to', 'Date To:', ["class" => "control-label"]) !!}
              <div class="controls">
                <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
                  <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                  {!! Form::text('date_to', $current_date_to, ['class' => 'form-control', 'id' => 'breakage-date-to']) !!}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                {{ link_to_action('ReportController@getTrafficBreakage', 'Today, 1 Week Ago',
                ['date_from' => $current_date_from, 'date_to' => $current_date_to],
                ['data-source' => 'week-filter', 'class' => 'breakage-date-week btn btn-info btn-block mrg-top-10']) }}
              </div>
              <div class="col-sm-6">
                {{ link_to_action('ReportController@getTrafficBreakage', 'Today, 30 Days Ago',
                ['date_from' => $current_date_from, 'date_to' => $current_date_to],
                ['data-source' => 'thirty-day-filter', 'class' => 'breakage-date-thirty btn btn-info btn-block mrg-top-10']) }}
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                {{ link_to_action('ReportController@getTrafficBreakage', 'Today, 60 Days Ago',
                ['date_from' => $current_date_from, 'date_to' => $current_date_to],
                ['data-source' => 'sixty-day-filter', 'class' => 'breakage-date-sixty btn btn-info btn-block mrg-top-10']) }}
              </div>
              <div class="col-sm-6">
                {{ link_to_action('ReportController@getPacing', 'Today, 90 Days Ago',
                ['date_from' => $current_date_from, 'date_to' => $current_date_to],
                ['data-source' => 'ninety-day-filter', 'class' => 'breakage-date-ninety btn btn-info btn-block mrg-top-10']) }}
              </div>
            </div>
          </fieldset>

        </div>
      </div>
    </div><!--/col-->
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        {!! Form::submit('Retrieve Breakage', ['class' => 'btn btn-success btn-block']) !!}
      </div>
    </div>
  </div>

  {!! Form::close() !!}



  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        <div class="box-header">
          <h2><i class="fa fa-align-justify"></i>Redirects Percentage Breakage</h2>
        </div>
        <div class="box-content" id="breakage-percent">
          <div class="row">
            <div id="breakage-percent-graph" class="col-lg-12" style="height:480px"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        <div class="box-header">
          <h2><i class="fa fa-align-justify"></i>Redirects Absolute Breakage</h2>
        </div>
        <div class="box-content" id="breakage-absolute">
          <div class="row">
            <div id="breakage-absolute-graph" class="col-lg-12" style="height:480px"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        <div class="box-header">
          <h2><i class="fa fa-align-justify"></i>Our Revenue vs Portal Revenue</h2>
        </div>
        <div class="box-content" id="breakage-revenue">
          <div class="row">
            <div id="breakage-revenue-graph" class="col-lg-12" style="height:480px"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        <div class="box-header">
          <h2><i class="fa fa-align-justify"></i>Revenue Delta Percentage</h2>
        </div>
        <div class="box-content" id="breakage-rev-diff">
          <div class="row">
            <div id="breakage-rev-diff-graph" class="col-lg-12" style="height:480px"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        @if(count($breakages) > 0)
          <div class="box-header">
            <h2><i class="fa fa-align-justify"></i>Campaign Breakage</h2>
          </div>
          <div class="box-content">
            <div class="table-responsive">
              <table class="table table-striped table-hover job-alerts-table bootstrap-datatable datatable">
                <thead>
                <th>Date Report</th>
                <th>Rev Code</th>
                <th>Portal Redirects</th>
                <th>Our Redirects</th>
                <th>% Breakage Redirects</th>
                <th>Portal Revenue</th>
                <th>Our Revenue</th>
                <th>% Breakage Revenue</th>
                </thead>
                <tbody>
                @foreach($breakages as $breakage)
                  @include('reports.breakagerow', $breakage)
                @endforeach
                </tbody>
              </table>
            </div>

            @else
              <h2 class="alert alert-danger">No data available</h2>
            @endif
          </div>
      </div>
    </div>
  </div>

  @if (count($breakages) > 0)
    <div class="row">
      <div class="col-md-12">
        <p class=""><em>*Breakage data for today's date ({{ date('m/d/Y', time()) }}) may not available until after 2:00
            PM</em></p>
      </div>
    </div>
  @endif

@endsection

@section('inline-js')
  {{--<script type="text/javascript">--}}
      $(function () {
          var breakage = {!! json_encode($br) !!};

          var percent_plot = $.plot($("#breakage-percent-graph"),
              [
                  {
                      data: breakage.percent,
                      label: "% Redirects",
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

          var absolute_plot = $.plot($("#breakage-absolute-graph"),
              [
                  {
                      data: breakage.absolute.diff,
                      label: "&Delta; Redirects",
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

          var revenue_plot = $.plot($("#breakage-revenue-graph"),
              [
                  {
                      data: breakage.revenue.portal,
                      label: "Portal Revenue",
                      color: 'purple',
                      points: {show: true},
                      lines: {show: true}
                  },
                  {
                      data: breakage.revenue.adren,
                      label: "Our Revenue",
                      color: 'orange',
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

          var rev_diff_plot = $.plot($("#breakage-rev-diff-graph"),
              [
                  {
                      data: breakage.rev_diff.percent,
                      label: "&Delta; Revenue",
                      color: 'grey',
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

          $("#breakage-percent-graph, #breakage-absolute-graph, #breakage-revenue-graph, #breakage-rev-diff-graph").bind("plothover", function (event, pos, item) {
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

                      if (event.currentTarget.id == "breakage-absolute-graph") {
                          showTooltip(item.pageX, item.pageY, commify(y) + ' (' + display_date + ')', bgcolor);
                      } else if (event.currentTarget.id == "breakage-revenue-graph") {
                          showTooltip(item.pageX, item.pageY, formatDollar(y) + ' (' + display_date + ')', bgcolor);
                      } else if (event.currentTarget.id == "breakage-rev-diff-graph") {
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
