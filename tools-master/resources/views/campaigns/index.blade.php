@extends('layouts.base')

@section('content')

  <div class="row">
    <div class="col-lg-12">
      @if(count($errors))
        <div class="alert alert-danger">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <div class="box">
        <div class="box-header">
          <h2><i class="fa fa-align-justify"></i>Campaigns</h2>
          <div class="box-icon">
            <a href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
          </div>
        </div>
        <div class="box-content">
          <div class="table-responsive">
            <table class="table table-striped table-bordered bootstrap-datatable datatable">
              <thead>
              <tr>
                <th>Adv Code</th>
                <th>Campaign Code</th>
                <th>Campaign Name</th>
                <th>Country</th>
                <th>Budget</th>
                <th>Budget Update</th>
                <th>Status</th>
              </tr>
              </thead>
              <tbody>

              @foreach($campaigns as $campaign)
                @include('campaigns.campaign_row', ['campaign' => $campaign])
              @endforeach
              </tbody>
            </table>
          </div>
          <a href="{{ route('campaigns.create') }}" class="btn btn-lg btn-success btn-block">Create new Campaign</a>
        </div>
      </div>
      @if(!$with_inactive)
        <a href="{{ route('campaigns.index', ['with_inactive' => true]) }}" class="btn btn-lg btn-block">Show Inactive
          Campaigns</a>
      @else
        <a href="{{ route('campaigns.index') }}" class="btn btn-lg btn-block">Hide Inactive Campaigns</a>
      @endif
    </div><!--/col-->
  </div>
  <div class="row">

  </div>
@endsection



@section('inline-js')
  {{--<script>--}}
  $(function () {
      var $update_url = "{!! route('campaigns.update') !!}";

      clearAll();

      $("a[id$='update']").on('click', function (e) {

          console.log("Submitting ... ");
          var $id = e.target.id;
          var chunks = $id.split("_");
          var $adv_code;
          var $campaign_code;
          var $campaign_type;
          $adv_code = chunks[0];
          $campaign_code = chunks[1];
          $campaign_type = chunks[2];

          var $adv_key = $adv_code + "_" + $campaign_code;
          var current_value_id = $adv_key + '_' + $campaign_type + '_budget';
          var $budget = $('#' + current_value_id).val();

         $.ajax({
              url: $update_url,
              type: 'get',
              dataType: 'json',
              data: {
                  adv_key: $adv_key,
                  budget_max: $budget
              },
              success: function(data) {
                  if (data.status == 'success') {
                    location.reload();
                  }
              }
          });

      });

      function clearAll() {
        var $input = $('[id$=budget]');

        $.each($input, function(index, element) {
            $(element).val('');
        });
      }

  });

@endsection
