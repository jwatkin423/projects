<div class="form-actions">
  <div class="row">
    <div class="col-sm-6">
      {{ link_to_action('HomeController@getIndex', 'Today', [
          'from' => $today_date_filter->from,
          'to' => $today_date_filter->to
      ], ['data-source' => 'today', 'class' => 'btn btn-info btn-block today-date-merchant']) }}
    </div>
    <div class="col-sm-6">
      {{ link_to_action('HomeController@getIndex', 'Yesterday', [
          'from' => $yesterday_date_filter->from,
          'to' => $yesterday_date_filter->to
      ], ['data-source' => 'yesterday', 'class' => 'btn btn-info btn-block yesterday-date-merchant']) }}
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
            {{ link_to_action('ReportController@getTrueDashboard', 'Last 30', [
                'from' => $thirty_days_date_filter->from,
                'to' => $thirty_days_date_filter->to
            ], ['data-source' => 'thirty-days', 'class' => 'truedashboard-thirty-days btn btn-info btn-block date-last-month mrg-top-10']) }}
        </div>
    </div>

  <div class="row">
    <div class="col-sm-6">
      {{ link_to_action('HomeController@getIndex', 'Month to Date', [
          'from' => $month_to_date_date_filter->from,
          'to' => $month_to_date_date_filter->to
      ], ['data-source' => 'monthToDate', 'class' => 'btn btn-info btn-block month-to-date-date-merchant']) }}
    </div>
    <div class="col-sm-6">
      {{ link_to_action('HomeController@getIndex', 'Last Month', [
          'from' => $last_month_date_filter->from,
          'to' => $last_month_date_filter->to
      ], ['data-source' => 'lastMonth', 'class' => 'btn btn-info btn-block last-month-date-merchant']) }}
    </div>
  </div>


  <div class="row">
    <div class="col-sm-6">
      {{ link_to_action('HomeController@getIndex', '-1 Day', [
          'from' => $yesterday_date_filter->from,
          'to' => $yesterday_date_filter->to
      ], ['class' => 'btn btn-info btn-block', 'id' => 'minus-one']) }}
    </div>
    <div class="col-sm-6">
      {{ link_to_action('HomeController@getIndex', '+1 Day', [
          'from' => $yesterday_date_filter->from,
          'to' => $yesterday_date_filter->to
      ], ['class' => 'btn btn-info btn-block', 'id' => 'plus-one']) }}
    </div>
  </div>
</div>
