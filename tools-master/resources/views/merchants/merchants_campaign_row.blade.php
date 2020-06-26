@php  $profit = $row->revenue - $row->cost; @endphp

<tr>
  @if(isset($row->date_log))
    <td>{{ $row->date_log }}</td>
  @endif
  @if ($report_type != 'merchant')
    <td>{{ $row->merchant_name }}</td>
    <td>{{ $row->merchant_id }}</td>
  @endif
  @if (!$totals)
    <td>{{ $row->adv_code }}</td>
    <td>{{ $row->campaign_code }}</td>
  @endif
  <td>{{ number_format($row->requests) }}</td>
  <td>{{ number_format($row->redirects) }}</td>
  <td>{{ money_format('%.2n', $row->cost) }}</td>
  <td>{{ money_format('%.2n', $row->revenue) }}</td>
  <td>{{ money_format('%.2n', $profit) }}</td>

</tr>