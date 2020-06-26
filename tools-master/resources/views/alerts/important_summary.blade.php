
@if(count($alerts) > 0)
  <div class="row">
    <div class="col-12 mrg-b-15">
  <a href="{!! action('AlertsController@getIndex') !!}" class="btn btn-danger btn-block">There are
  ({{ count($alerts) }}) outstanding alerts. Please view them on the 'Alerts' page.</a>
  </div>
  </div>
@endif
