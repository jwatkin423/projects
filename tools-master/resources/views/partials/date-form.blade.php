<div class="box">
  <div class="box-header">
    <h2><i class="fa fa-calendar"></i>Date Range</h2>
  </div>
  <div class="box-content">
    {{ Form::model($date_filter, ['action' => 'HomeController@getIndex', 'class' => 'form-horizontal', 'method' => 'get']) }}
    <fieldset class="col-sm-12">
      <div class="form-group">
        {{ Form::label('from', 'Start Date', ["class" => "control-label"]) }}
        <div class="controls">
          <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
            <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
            {{ Form::text('from', null, ['class' => 'form-control', 'id' => 'fromDate']) }}
          </div>
        </div>
      </div>
      <div class="form-group">
        {{ Form::label('to', 'End Date', ["class" => "control-label"]) }}
        <div class="controls">
          <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
            <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
            {{ Form::text('to', null, ['class' => 'form-control', 'id' => 'toDate']) }}
          </div>
        </div>
      </div>
      <div class="form-actions">
        {{ Form::submit('Update', ['class' => 'btn btn-success btn-block']) }}
        <div class="row">
          <div class="col-sm-6">
            {{ link_to_action('HomeController@getIndex', 'Today', [
                'from' => $today_date_filter->from,
                'to' => $today_date_filter->to
            ], ['data-source' => 'today', 'class' => 'mrg-top-10 btn btn-info btn-block today-date']) }}
          </div>
          <div class="col-sm-6">
            {{ link_to_action('HomeController@getIndex', 'Yesterday', [
                'from' => $yesterday_date_filter->from,
                'to' => $yesterday_date_filter->to
            ], ['data-source' => 'yesterday', 'class' => 'mrg-top-10 btn btn-info btn-block yesterday-date']) }}
          </div>
        </div>
          <div class="row">
              <div class="col-6">
                  {{ link_to_action('HomeController@getIndex', 'Last Week', [
                      'from' => $last_week_date_filter->from,
                      'to' => $last_week_date_filter->to
                  ], ['data-source' => 'lastWeek', 'class' => 'mrg-top-10 btn btn-info btn-block last-week-date']) }}
              </div>
              <div class="col-6">
                  {{ link_to_action('HomeController@getIndex', 'Last 30', [
                      'from' => $thirty_days_date_filter->from,
                      'to' => $thirty_days_date_filter->to
                  ], ['data-source' => 'thirtyDays', 'class' => 'mrg-top-10 btn btn-info btn-block last-month-date']) }}
              </div>
          </div>
        <div class="row">
          <div class="col-sm-6">
            {{ link_to_action('HomeController@getIndex', 'Month to Date', [
                'from' => $month_to_date_date_filter->from,
                'to' => $month_to_date_date_filter->to
            ], ['data-source' => 'monthToDate', 'class' => 'mrg-top-10 btn btn-info btn-block month-date-to-date']) }}
          </div>
          <div class="col-sm-6">
            {{ link_to_action('HomeController@getIndex', 'Last Month', [
                'from' => $last_month_date_filter->from,
                'to' => $last_month_date_filter->to
            ], ['data-source' => 'lastMonth', 'class' => 'mrg-top-10 btn btn-info btn-block last-month-date']) }}
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            {{ link_to_action('HomeController@getIndex', '-1 Day', [
                'from' => $yesterday_date_filter->from,
                'to' => $yesterday_date_filter->to
            ], ['class' => 'mrg-top-10 btn btn-info btn-block', 'id' => 'minus-one']) }}
          </div>
          <div class="col-sm-6">
            {{ link_to_action('HomeController@getIndex', '+1 Day', [
                'from' => $yesterday_date_filter->from,
                'to' => $yesterday_date_filter->to
            ], ['class' => 'mrg-top-10 btn btn-info btn-block', 'id' => 'plus-one']) }}
          </div>
        </div>
      </div>
    </fieldset>
    {{ Form::close() }}
  </div>
</div>
