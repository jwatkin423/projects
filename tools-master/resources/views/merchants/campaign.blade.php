@section('content')
{!! Form::open(['action' => 'ReportController@getTopMerchants', 'class' => 'form-horizontal', 'method' => 'get']) !!}
<div class="row">
  <div class="col-lg-6">
    <div class="box">
      <div class="box-header">
        <h2><i class="fa fa-calendar"></i>Date Range</h2>
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
          <div class="row">
            <p><i>NOTE: Only 90 DAYS OF HISTORICAL DATA</i></p>
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
            {!! Form::label('adv_key', 'Adv Keys:', ["class" => "control-label"]) !!}
            <div class="controls">
              {!! Form::select('adv_key', $adv_keys, null, ['class' => 'form-control']) !!}
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
                        @if ($sort == 'requests')
                            <input type="radio" name="sort" value="requests" checked> Auctions Descending
                        @else
                            <input type="radio" name="sort" value="requests"> Auctions Descending
                        @endif
                    </div>
                    <div class="col-xs-4">
                        @if ($sort == 'redirects')
                            <input type="radio" name="sort" value="redirects" checked> Redirects Descending
                        @else
                            <input type="radio" name="sort" value="redirects"> Redirects Descending
                        @endif
                    </div>
                    <div class="col-xs-4">
                        @if ($sort == 'revenue')
                            <input type="radio" name="sort" value="revenue" checked> Revenue Descending
                        @else
                            <input type="radio" name="sort" value="revenue"> Revenue Descending
                        @endif
                    </div>
                </div>
            </div>

          <div class="row">
            <div class="col-lg-12">
              {!! Form::submit('Generate Top Merchants Report', ['class' => 'btn btn-success btn-block']) !!}
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
        <h2><i class="fa fa-align-justify"></i>{{ $adv_key }} [Total]</h2>
      </div>
      <div class="box-content" id="campaign-totals">
        <div class="row campaign-note">
          * <i>NOTE:profits calculated without rev share.</i>
        </div>
          <div class="table-responsive">
            <table class="table table-striped table-bordered datatable">
          <thead>
          <tr>
            <th style="display: none;">hide this row</th>
            <th>Merchant</th>
            <th>Merchant ID</th>
            <th>Auctions</th>
            <th>Redirects</th>
            <th>Cost</th>
            <th>Revenue</th>
            <th>Profit</th>
            <th style="color: #fff !important;background-color: #fff !important;border-bottom-color: #fff !important;border-top-color: #fff !important;">&nbsp;</th>
            <th>ROI</th>
            <th>CPR</th>
            <th>RPR</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($merchant_totals as $row_totals)
            @php $row_totals->no_date = true; @endphp
            @include('merchants.merchants_row', ['row' => $row_totals, 'totals' => true])
          @endforeach
        </table>
          </div>
      </div>
    </div>
  </div><!--/col-->
</div>

<hr>

<div class="row">
  <div class="col-lg-12">
    <div class="box">
      <div class="box-header">
        <h2><i class="fa fa-align-justify"></i>{{ $adv_key }}</h2>
      </div>
      <div class="box-content" id="campaign-totals">
        <div class="row campaign-note">
          * <i>NOTE:profits calculated without rev share.</i>
        </div>
          <div class="table-responsive">
            <table class="table table-striped table-bordered bootstrap-datatable datatable" id="table-targeted">
          <thead>
          <tr>
            <th style="display: none;">hide this row</th>
            <th>Merchant</th>
            <th>Merchant ID</th>
            <th>Auctions</th>
            <th>Redirects</th>
            <th>Cost</th>
            <th>Revenue</th>
            <th>Profit</th>
            <th style="color: #fff !important;background-color: #fff !important;border-bottom-color: #fff !important;border-top-color: #fff !important;">&nbsp;</th>
            <th>ROI</th>
            <th>CPR</th>
            <th>RPR</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($merchant_totals_targeted as $row_targeted)
            @include('merchants.merchants_row', ['row' => $row_targeted, 'totals' => false])
          @endforeach
        </table>
          </div>
      </div>
    </div>
  </div><!--/col-->
</div>

<hr>

<div class="row">
  <div class="col-lg-12">
    <div class="box">
      <div class="box-header">
        <h2><i class="fa fa-align-justify"></i>{{ $adv_key . "ron" }}</h2>
      </div>
      <div class="box-content" id="campaign-totals">
        <div class="row campaign-note">
          * <i>NOTE:profits calculated without rev share.</i>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered bootstrap-datatable datatable" id="table-ron">
              <thead>
              <tr>
                <th style="display: none;">hide this row</th>
                <th>Merchant</th>
                <th>Merchant ID</th>
                <th>Auctions</th>
                <th>Redirects</th>
                <th>Cost</th>
                <th>Revenue</th>
                <th>Profit</th>
                <th style="color: #fff !important;background-color: #fff !important;border-bottom-color: #fff !important;border-top-color: #fff !important;">&nbsp;</th>
                <th>ROI</th>
                <th>CPR</th>
                <th>RPR</th>
              </tr>
              </thead>
              <tbody>
              @foreach ($merchant_totals_ron as $row_ron)
                @include('merchants.merchants_row', ['row' => $row_ron, 'totals' => false])
              @endforeach
            </table>
        </div>
      </div>
    </div>
  </div><!--/col-->
</div>



@endsection

@section('inline-js')
{{--<script>--}}
{{--  $("#table-total").dataTable({
    "bPaginate" : false,
    "bInfo" : false,
    "bFilter" : false,
    "aaSorting": [[ 3, "desc" ]]
  });

  $("#table-ron").dataTable({
  "bPaginate" : false,
  "bInfo" : false,
  "bFilter" : false,
  "aaSorting": [[ 5, "desc" ]]
  });

  $("#table-targeted").dataTable({
  "bPaginate" : false,
  "bInfo" : false,
  "bFilter" : false,
  "aaSorting": [[ 5, "desc" ]]
  });--}}

@endsection
