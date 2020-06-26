@php $boxColor = 'blue'; @endphp
<div class="row">
    <div class="col-12">
        <h2><strong>Partner Stats</strong></h2>
    </div>

    <div class="col-xs-6 col-lg-3">
        <div class="smallstat smallstat-dark box">
            <i class="fa fa-arrow-circle-o-right blue"></i>
            <span class="title">Running</span>
            <span class="value">{{ count($partners_traffic['running_partners']) }}</span>
        </div>
    </div><!--/col-->

    <div class="col-xs-6 col-lg-3">
        @if ($partners_traffic['zero']  >= 1)
            @php $boxColor = "red"; @endphp
        @endif
        <div class="smallstat smallstat-dark box">
            <i class="fa fa-exclamation-triangle {{ $boxColor }}" id="no-traffic"></i>
            <span class="title">No Traffic</span>
            <span class="value">{{ $partners_traffic['zero'] }}</span>
            <button id="partner-no-traffic-btn" type="button" class="btn btn-xs btn-default" data-toggle="modal" data-target="#partner-no-traffic-modal"><span class="fa fa-info-circle red" id="partner-no-traffic-info"></span></button>
        </div>
    </div><!--/col-->
</div> <!-- /row -->

<!-- Modal show inactive campaigns -->
<div class="modal fade" id="partner-no-traffic-modal" tabindex="-1" role="dialog" aria-labelledby="no-trafficLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="partner-no-trafficLabel"><i class="fa fa-exclamation-triangle red" id="partner-no-traffic"></i> Partners with No Traffic</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="">
                    @foreach($partners_traffic['zt_partners'] as $partner)
                        {{ $partner }} <br />
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
