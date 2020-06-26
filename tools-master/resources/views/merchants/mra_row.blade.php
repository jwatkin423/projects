<tr>
    <td>{{ $row->date_report}}</td>
    <td>{{ $row->merchant_id}}</td>
    <td>{{ number_format($row->ron_redirects_max) }}</td>
    <td>{{ number_format($row->rpc_count) }}</td>
    <td>{{ money_format('%.2n', $row->rpc_max) }}</td>
    <td>{{ money_format('%.2n', $row->rpc_min) }}</td>
    <td>{{ money_format('%.2n', $row->rpc_mean) }}</td>
    <td>{{ money_format('%.2n', $row->rpc_median) }}</td>
    <td>{{ money_format('%.2n', $row->rpc_std_dev) }}</td>
</tr>
