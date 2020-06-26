<td>{{ number_format($row->auctions) }}</td>
<td>{{ number_format($row->redirects) }}</td>
<td>{{ number_format($row->win, 2) }}%</td>
<td>{{ money_format('%.2n', $row->cost) }}</td>
<td>{{ money_format('%.2n', $row->revenue) }}</td>
<td>{{ money_format('%.2n', $row->profit) }}</td>
<td class="blank-col" width="30">nbsp;</td>
<td>
    @if($row->roi != -1)
        {{ number_format($row->roi, 2) }}%
    @else
        N/A
    @endif
</td>
<td>
    @if($row->cpr != -1)
        {{ money_format('%.4n', $row->cpr) }}
    @else
        N/A
    @endif
</td>
<td>
    @if($row->rpr != -1)
        {{ money_format('%.4n', $row->rpr) }}
    @else
        N/A
    @endif
</td>
