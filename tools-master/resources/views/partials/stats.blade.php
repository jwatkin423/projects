@include('partials.partner_stats')
<hr />

<!-- API STATS SECTION -->
@include('partials.zeroclick')
<hr />

@if($start_date == $current_date && $end_date == $current_date)
  <!-- ACTIVE CAMPAIGNS SECTION -->
  @include('partials.active_campaigns')
    <hr />
@endif

