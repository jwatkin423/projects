<div class="col-lg-12">
  <div class="box">
    <div class="box-header">
      <h2><i class="fa fa-align-justify"></i><span class="break"></span>Phone API Partner Performance</h2>
    </div>

    <div class="box-content">
      <div class="table-responsive">
        <table class="table table-striped bootstrap-datatable datatable">
          <thead>
          <tr>
            <th>API ID</th>
            <th>Partner</th>
            <th>Caller</th>
            <th>Connects</th>
            <th>Win %</th>
            <th>Cost</th>
            <th>Revenue</th>
            <th>Profit</th>
            <th>ROI</th>
            <th>Avg Cost</th>
            <th>Avg Rev Per Call</th>
          </tr>
          </thead>
          <tbody>
          @foreach($partner_phone_summary as $row)
            <tr>
              <td>{{ $row->api_id }}</td>
              <td>{{ $row->api_name }}</td>
              @include('partials.phone_summary_row', ['row' => $row])
            </tr>
          @endforeach
          </tbody>
          <tfoot>
          <tr>
            <td>Total</td>
            <td>Total</td>
            @include('partials.phone_summary_row', ['row' => $phone_summary])
          </tr>
          </tfoot>
        </table>
      </div>
    </div> <!-- /box-content -->
  </div> <!-- /box -->
</div> <!-- /col -->