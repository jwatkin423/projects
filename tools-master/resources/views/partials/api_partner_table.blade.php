<div class="col-lg-12">
    <div class="box">
        <div class="box-header">
            <h2><i class="fa fa-align-justify"></i><span class="break"></span>Partner Performance</h2>
        </div>

        <div class="box-content">
            <div class="table-responsive">
                <table class="table table-striped bootstrap-datatable datatable">
                    <thead>
                    <tr>
                        <th>API ID</th>
                        <th>Partner</th>
                        <th>Auctions</th>
                        <th>Redirects</th>
                        <th>Win %</th>
                        <th>Cost</th>
                        <th>Revenue</th>
                        <th>Profit</th>
                        <th style="color: #fff !important;background-color: #fff !important;border-bottom-color: #fff !important;border-top-color: #fff !important;">
                        <th>ROI</th>
                        <th>CPR</th>
                        <th>RPR</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($partner_summary as $row)
                        <tr>
                            <td>{{ link_to_route('get-partner-pacing', $row->api_id, ['api_partner' => $row->api_id]) }}</td>
                            <td>{{ $row->api_name }}</td>
                            @include('partials.zeroclick_summary_row', ['row' => $row])
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>Total</td>
                        <td>Total</td>
                        @include('partials.zeroclick_summary_row', ['row' => $summary])
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div> <!-- /box-content -->
    </div> <!-- /box -->
</div> <!-- /col -->
