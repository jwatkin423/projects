<div class="row">

  <div class="col-12">
    <h2><strong>Phone Calls</strong></h2>
  </div>

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <i class="fa fa-download red"></i>
      <span class="title">Cost</span>
      <span class="value">{{ money_format('%.2n', $phone_summary['cost']) }}</span>
    </div>
  </div> <!--/col-->

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <i class="fa fa-upload yellow"></i>
      <span class="title">Revenue</span>
      <span class="value">{{ money_format('%.2n', $phone_summary['revenue']) }}</span>
    </div>
  </div><!--/col-->

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <i class="fa fa-dollar green"></i>
      <span class="title">Profit</span>
      <span class="value">{{ money_format('%.2n', $phone_summary['profit']) }}</span>
    </div>
  </div><!--/col-->

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <i class="fa fa-money blue"></i>
      <span class="title">ROI</span>
      <span class="value">
        @if($phone_summary['roi'] != -1)
          {{ sprintf("%.2f", $phone_summary['roi']) }}%
        @else
          N/A
        @endif
      </span>
    </div>
  </div><!--/col-->
</div> <!-- /row -->

<div class="row">
  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <span class="title">Callers</span>
      <span class="value">{{ number_format($phone_summary['callers']) }}</span>
    </div>
  </div> <!--/col-->

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <span class="title">Connects</span>
      <span class="value">{{ number_format($phone_summary['connects']) }}</span>
    </div>
  </div> <!--/col-->

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <span class="title">Avg Cost Per Call</span>
      <span class="value">
        @if($phone_summary['cpr'] != -1)
          {{ money_format('%.4n', $phone_summary['cpr']) }}
        @else
          N/A
        @endif
     </span>
    </div>
  </div> <!--/col-->

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <span class="title">Avg Rev Per Call</span>
      <span class="value">
       @if($phone_summary['rpr'] != -1)
          {{ money_format('%.4n', $phone_summary['rpr']) }}
        @else
          N/A
        @endif
      </span>
    </div>
  </div> <!--/col-->


</div><!-- internal row -->
