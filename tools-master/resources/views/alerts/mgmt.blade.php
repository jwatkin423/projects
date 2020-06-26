@extends('layouts.base')

@section('content')

  <div class="row">
    <div class="col-lg-12">
      <div class="box">
        @if(count($jobs) > 0)
          <div class="box-header">
            <h2><i class="fa fa-exclamation-triangle"></i>Job Alerts Meta Details</h2>
          </div>
          <div class="box-content">
            <div class="table-responsive">
              <table class="table table-striped table-hover job-alerts-table bootstrap-datatable datatable">
                <thead>
                <th>Job Alert</th>
                <th>Priority</th>
                <th>Importance</th>
                <th>Delivery</th>
                <th>Reminder</th>
                <th>Reminder Setting</th>
                </thead>
                <tbody>
                @foreach($jobs as $job)
                  @include('alerts.mgmtrow', $job)
                @endforeach
                </tbody>
              </table>
            </div>
            @endif
          </div>
      </div>
    </div>

@endsection