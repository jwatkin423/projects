@php

    $win_raw = ((float)$row['raw_rd'] / (float)$row['auc'] * 100);
    if ($display !== 'log_rev' && $row['true_rd'] !== '-') {
        $win_true = ((float)$row['true_rd'] / (float)$row['auc'] * 100);
        $win_true = number_format($win_true, '2');
    } else {
        $win_true = '-';
    }

@endphp
<tr>
    <td style="display: none;">0</td>
    <td>{{ $row['api_id'] }}</td>
    <td>{{ $row['domain'] }}</td>
    <td>{{ number_format($row['auc']) }}</td>

    @if($display == 'log_rev')
        <td>{{ number_format((int)$row['raw_rd']) }}</td>
        <td>{{ number_format($win_raw, '2') }}%</td>
        <td>{{ money_format('%.2n', (float)$row['cost']) }}</td>
        <td>{{ money_format('%.2n', (float)$row['raw_rev']) }}</td>
        <td>{{ money_format('%.2n', ((float)$row['raw_rev'] - (float)$row['cost'])) }}</td>
    @else
        @if ($row['true_rd'] !== '-')
            <td>{{ number_format((int)$row['true_rd']) }}</td>
            <td>{{ $win_true }}%</td>
        @else
            <td>-</td>
            <td>-</td>
        @endif
        <td>{{ money_format('%.2n', (float)$row['cost']) }}</td>
        <td>{{ money_format('%.2n', (float)$row['true_rev']) }}</td>
        <td>{{ money_format('%.2n', ((float)$row['true_rev'] - (float)$row['cost'])) }}</td>
    @endif

    <td>{{ money_format('%.4n', (float)$row['avg_win_bid']) }}</td>
    <td>{{ money_format('%.4n', (float)$row['max_bid']) }}</td>

    @if ($row['sum_cnv'] !== '-')
        <td>{{ number_format((float)$row['sum_cnv']) }}</td>
    @else
        <td>-</td>
    @endif

    @if ($row['cnv_rate'] !== '-')
        @php $cnv_rate = (float)$row['cnv_rate'] * 100; @endphp
        {{--        <td>{{ number_format((float)$row['cnv_rate'], 5) }}%</td>--}}
        <td>{{ $cnv_rate }}%</td>
    @else
        <td>-</td>
    @endif
</tr>
