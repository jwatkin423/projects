<div class="col-lg-12">
  <div class="box">
    <div class="box-header">
      <h2><i class="fa fa-align-justify"></i><span class="break"></span>Phone Advertiser Performance</h2>
    </div>

    <div class="box-content">
      <div class="table-responsive">
        <table class="table table-striped bootstrap-datatable datatable">
          <thead>
          <tr>
            <th>Adv Code</th>
            <th>Campaign Code</th>
            @if($show_budget_status)
              <th>Status</th>
            @endif
            <th>Callers</th>
            <th>Connects</th>
            <th>Win %</th>
            <th>Cost</th>
            <th>Revenue</th>
            <th>Profit</th>
            <th>ROI</th>
            <th>CPR</th>
            <th>RPR</th>
          </tr>
          </thead>
          <tbody>
          @foreach($advertiser_phone_summary as $row)
            <tr>
              <td>{{ link_to_route('get-pacing', $row->adv_code, ['campaign' => $row->campaign->id]) }}</td>
              <td>{{ $row->campaign_code }}</td>
              @if($show_budget_status)

                <td>
                    @if ($row->capped['status'] != 'ron-capped')
                      <span class="label {{ $row->capped['label'] }}">{{ $row->capped['status'] }}</span>
                    @else
                      <span class="label {{ $row->capped['label'] }}">Capped <i class="fa fa-asterisk"></i></span>
                    @endif
                  @else
                    <span class="label {{ $row->budget_status['label'] }}">{{ $row->budget_status['status'] }}</span>
                  @endif
                </td>
              @include('partials.phone_summary_row', ['row' => $row])
            </tr>
          @endforeach
          </tbody>
          <tfoot>
          <tr>
            <td>Total</td>
            <td>Total</td>
            @if($show_budget_status)
              <td>Total</td>
            @endif
            @include('partials.phone_summary_row', ['row' => $phone_summary])
          </tr>
          </tfoot>
        </table>
        <div class="row">
          <span class="label label-success">Capped <i class="fa fa-asterisk"></i></span>&nbsp=&nbspMerchant Capped
        </div>
      </div>
    </div> <!-- /box-content -->
  </div> <!-- /box -->
</div> <!-- /col -->