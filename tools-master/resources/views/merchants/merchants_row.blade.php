@php  $profit = $row->revenue - $row->cost; @endphp

<tr>
  <td style="display: none;">0</td>
  @if(isset($row->date_log))
    <td>{{ $row->date_log }}</td>
  @endif
  @if ($report_type != 'merchant')
    <td>{{ $row->merchant_name }}</td>
    <td>{{ $row->merchant_id }}</td>
  @endif
  <td>{{ number_format($row->requests) }}</td>
  <td>{{ number_format($row->redirects) }}</td>
  <td>{{ money_format('%.2n', $row->cost) }}</td>
  <td>{{ money_format('%.2n', $row->revenue) }}</td>
  <td>{{ money_format('%.2n', $profit) }}</td>
  <td class="blank-col" width="30">&nbsp;</td>
  <td>{{ sprintf("%.2f%%", (($profit / $row->cost) * 100)) }}</td>
  <td>{{ money_format('%.4n', ($row->cost / $row->redirects)) }}</td>
  <td>{{ money_format('%.4n', ($row->revenue / $row->redirects)) }}</td>
</tr>
