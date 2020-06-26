@php
    $true_rev = !$row['true_rev'] ? '-' : $row['true_rev'];
    $cnv_sum = !$row['sum_cnv'] ? '-' : $row['sum_cnv'];
    $profit = $row['revenue'] - $row['cost'];
    $true_profit = $row['true_rev'] === '-' ?  '-' : money_format('%.2n', $row['true_rev'] - $row['cost']);
    $true_profit_raw = $row['true_rev'] === '-' ?  '-' : $row['true_rev'] - $row['cost'];
@endphp

<tr>
    @if(isset($row['date_log']))
        {{-- date --}}
        <td>{{ $row['date_log']}}</td>
    @endif
    @if ($report_type != 'merchant')
        <td>{{ $row['merchant_name']}}</td>
        <td>{{ $row['merchant_id']}}</td>
    @endif
    @if (!$totals)
        <td>{{ $row['adv_code']}}</td>
        <td>{{ $row['campaign_code']}}</td>
    @endif
    {{-- auctions --}}
    <td>{{ number_format($row['requests']) }}</td>
    {{-- redirects --}}
    <td>{{ number_format($row['redirects']) }}</td>
    {{-- win % --}}
    <td>{{ sprintf("%.2f%%", (($row['redirects'] / $row['requests']) * 100)) }}</td>
    {{-- cost --}}
    <td>{{ money_format('%.2n', $row['cost']) }}</td>
    {{-- raw revenue --}}
    <td>{{ money_format('%.2n', $row['revenue']) }}</td>
    {{-- raw profit --}}
    <td>{{ money_format('%.2n', $profit) }}</td>
    {{-- true revenue --}}
    @if($true_profit !== '-' )
        <td>{{ money_format('%.2n', $row['true_rev']) }}</td>
    @else
        <td><span style="display: none;">0</span>-</td>
    @endif
    {{-- true profit --}}
    <td>{{ $true_profit }}</td>
    {{-- cnv --}}
    @if($true_rev !== '-')
        <td>{{ number_format($row['sum_cnv']) }}</td>
    @else
        <td><span style="display: none;">0</span>-</td>
    @endif

    <td class="blank-col" width="30">nbsp;</td>
    {{-- raw roi --}}
    @if ($row['cost'] > 0)
        <td>{{ sprintf("%.2f%%", (($profit / $row['cost']) * 100)) }}</td>
    @else
        <td>-</td>
    @endif
    {{-- true roi --}}
    @if($true_profit_raw !== '-')
        @if ($row['cost'] > 0)
            <td>{{ sprintf("%.2f%%", (($true_profit_raw / $row['cost']) * 100)) }}</td>
        @else
            <td>-</td>
        @endif
    @else
        <td><span style="display: none;">0</span>-</td>
    @endif
    {{-- cpr --}}

    @if ($row['cost'] > 0)
        <td>{{ money_format('%.4n', ($row['cost'] / $row['redirects'])) }}</td>
        {{-- raw rpr --}}
        <td>{{ money_format('%.4n', ($row['revenue'] / $row['redirects'])) }}</td>
        {{-- true rpr --}}
        @if($true_rev !== '-')
            <td>{{ money_format('%.4n', ($row['true_rev'] / $row['redirects'])) }}</td>
        @else
            <td><span style="display: none;">0</span>-</td>
        @endif
    @else
        <td>-</td>
        <td>-</td>
        <td>-</td>
    @endif
</tr>
