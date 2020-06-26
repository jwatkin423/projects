@php

    $auctions = isset($data['auctions']) ? $data['auctions'] : 0;
    $redirects = isset($data['redirects']) ? $data['redirects'] : 0;
    $revenue = isset($data['revenue']) ? $data['revenue'] : 0;
    $profit = isset($data['profit']) ? $data['profit'] : 0;
    $cost = isset($data['cost']) ? $data['cost'] : 0;
    $roi = isset($data['roi']) ? $data['roi'] : 0;
    $cpr = isset($data['cpr']) ? $data['cpr'] : 0;
    $rpr = isset($data['rpr']) ? $data['rpr'] : 0;

    $true_roi = isset($data['true_roi']) ? $data['true_roi'] : 0;
    $true_rev = isset($data['true_rev']) ? $data['true_rev'] : 0;
    $true_rpr = isset($data['true_rpr']) ? $data['true_rpr'] : 0;
    $true_profit = isset($data['true_profit']) ? $data['true_profit'] : 0;

    $cnv = isset($data['sum_cnv']) ? $data['sum_cnv'] : 0;

@endphp

<div class="row">
    <!-- Auctions -->
    <div class="col-xs-6 col-lg-2">
        <div class="smallstat smallstat-dark box">
            <span class="title">Auctions</span>
            <span class="value domain-bkd">{{ number_format($auctions)}}</span>
        </div>
    </div> <!--/col-->

    <!-- Redirects -->
    <div class="col-xs-6 col-lg-2">
        <div class="smallstat smallstat-dark box">
            <span class="title">Redirects</span>
            <span class="value domain-bkd">{{ number_format($redirects) }}</span>
        </div>
    </div> <!--/col-->

    <!-- Cost -->
    <div class="col-xs-6 col-lg-2">
        <div class="smallstat smallstat-dark box">
            <span class="title">Cost</span>
            <span class="value domain-bkd">{{ money_format('%.2n', $cost) }}</span>
        </div>
    </div> <!--/col-->

    <!-- Total RPR -->
    <div class="col-xs-6 col-lg-2">
        <div class="smallstat smallstat-dark box">
            <span class="title">Raw RPR</span>
            <span class="value domain-bkd">{{ money_format('%.4n', $rpr) }}</span>
        </div>
    </div> <!--/col-->

    <!-- Total CNV -->
    <div class="col-xs-6 col-lg-2">
        <div class="smallstat smallstat-dark box">
            <span class="title">CNV</span>
            <span class="value domain-bkd">{{ number_format($cnv) }}</span>
        </div>
    </div> <!--/col-->
</div>

<div class="row">
    <!-- Raw/True Revenue -->
    <div class="col-xs-6 col-lg-2">
        <div class="smallstat smallstat-dark box">
            <span class="title">Revenue</span>
            <span class="value domain-bkd">{{ money_format('%.2n', $revenue) }}</span>
        </div>
    </div> <!--/col-->

    <!-- Profit -->
    <div class="col-xs-6 col-lg-2">
        <div class="smallstat smallstat-dark box">
            <span class="title">Raw Profit</span>
            <span class="value domain-bkd">{{ money_format('%.2n', ($profit)) }}</span>
        </div>
    </div> <!--/col-->

    <!-- Raw ROI -->
    <div class="col-xs-6 col-lg-2">
        <div class="smallstat smallstat-dark box">
            <span class="title">Raw ROI</span>
            <span class="value domain-bkd">{{ number_format($roi, 2) }}%</span>
        </div>
    </div> <!--/col-->


    <!-- Total CNV Rate -->
    <div class="col-xs-6 col-lg-2">
        <div class="smallstat smallstat-dark box">
            <span class="title">CPR</span>
            <span class="value domain-bkd">{{ money_format('%.4n', $cpr) }}</span>
        </div>
    </div> <!--/col-->

</div>

<div class="row">
    <!-- True Revenue -->
    <div class="col-xs-6 col-lg-2">
        <div class="smallstat smallstat-dark box">
            <span class="title">True Revenue</span>
            <span class="value domain-bkd">{{ money_format('%.2n', $true_rev) }}</span>
        </div>
    </div> <!--/col-->

    <!-- True Profit -->
    <div class="col-xs-6 col-lg-2">
        <div class="smallstat smallstat-dark box">
            <span class="title">True Profit</span>
            <span class="value domain-bkd">{{ money_format('%.2n', ($true_profit)) }}</span>
        </div>
    </div> <!--/col-->

    <!-- Raw ROI -->
    <div class="col-xs-6 col-lg-2">
        <div class="smallstat smallstat-dark box">
            <span class="title">True ROI</span>
            <span class="value domain-bkd">{{ number_format($true_roi, 2) }}%</span>
        </div>
    </div> <!--/col-->

    <!-- True CPR -->
    <div class="col-xs-6 col-lg-2">
        <div class="smallstat smallstat-dark box">
            <span class="title">True RPR</span>
            <span class="value domain-bkd">{{ money_format('%.4n', $true_rpr) }}</span>
        </div>
    </div> <!--/col-->

</div> <!-- row -->


