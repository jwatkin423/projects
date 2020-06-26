<div class="row">

    <div class="col-12">
        <h2><strong>Zeroclick</strong></h2>
    </div>

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <i class="fa fa-download lightOrange"></i>
      <span class="title">Cost</span>
      <span class="value">{{ money_format('%.2n', $summary['cost']) }}</span>
    </div>
  </div> <!--/col-->

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <i class="fa fa-upload yellow"></i>
      <span class="title">Revenue</span>
      <span class="value">{{ money_format('%.2n', $summary['revenue']) }}</span>
    </div>
  </div><!--/col-->

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <i class="fa fa-dollar green"></i>
      <span class="title">Profit</span>
      <span class="value">{{ money_format('%.2n', $summary['profit']) }}</span>
    </div>
  </div><!--/col-->

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <i class="fa fa-money blue"></i>
      <span class="title">ROI</span>
      <span class="value">
        @if($summary['roi'] != -1)
          {{ sprintf("%.2f", $summary['roi']) }}%
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
      <span class="title">Requests</span>
      <span class="value">{{ number_format($summary['auctions']) }}</span>
    </div>
  </div> <!--/col-->

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <span class="title">Redirects</span>
      <span class="value">{{ number_format($summary['redirects']) }}</span>
    </div>
  </div> <!--/col-->

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <span class="title">CPR</span>
      <span class="value">
        @if($summary['cpr'] != -1)
          {{ money_format('%.4n', $summary['cpr']) }}
        @else
          N/A
        @endif
      </span>
    </div>
  </div> <!--/col-->

  <div class="col-xs-6 col-lg-3">
    <div class="smallstat smallstat-dark box">
      <span class="title">RPR</span>
      <span class="value">
        @if($summary['rpr'] != -1)
          {{ money_format('%.4n', $summary['rpr']) }}
        @else
          N/A
        @endif
      </span>
    </div>
  </div> <!--/col-->
</div> <!-- /row -->
