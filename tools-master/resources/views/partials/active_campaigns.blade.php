<div class="row">

  <div class="col-12">
    <h2><strong>Campaign Stats</strong></h2>
  </div>

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <i class="fa fa-list yellow"></i>
      <span class="title">Active</span>
      <span class="value">{{ $campaigns_active }}</span>
    </div>
  </div> <!--/col-->

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <i class="fa fa-arrow-circle-o-right blue"></i>
      <span class="title">Running</span>
      <span class="value">{{ $campaigns_running }}</span>
    </div>
  </div><!--/col-->

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <i class="fa fa-check green"></i>
      <span class="title">Capped</span>
      <span class="value">{{ $campaigns_capped }}</span>
    </div>
  </div><!--/col-->

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <i class="fa fa-exclamation-triangle red" id="no-traffic"></i>
      <span class="title">No Traffic</span>
      <span class="value">
          {{ $campaigns_inactive }}
      </span>
      <button id="no-traffic-btn" type="button" class="btn btn-xs btn-default" data-toggle="modal" data-target="#no-traffic-modal"><span class="fa fa-info-circle red" id="no-traffic-info"></span></button>
    </div>
  </div><!--/col-->
</div> <!-- /row -->

<!-- Modal show inactive campaigns -->
<div class="modal fade" id="no-traffic-modal" tabindex="-1" role="dialog" aria-labelledby="no-trafficLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="no-trafficLabel"><i class="fa fa-exclamation-triangle red" id="no-traffic"></i> Campaigns with No Traffic</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="">
          @foreach($campaigns_inactive_list as $campaign)
            {{ $campaign }} <br />
          @endforeach
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>