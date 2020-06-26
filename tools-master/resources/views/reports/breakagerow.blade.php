<tr>
  <td>{{ date('m/d/Y', strtotime($date_report)) }}</td>
  <td>{{ $rev_code }}</td>
  <td>{{ number_format($portal_redirects) }}</td>
  <td>{{ number_format($our_redirects) }}</td>
  <td>{{ round((100 * $breakage_rd), 2, PHP_ROUND_HALF_UP) }}%</td>
  <td>{{ money_format('%.2n', $portal_revenue) }}</td>
  <td>{{ money_format('%.2n', $our_revenue) }}</td>
  <td>{{ round((100 * $breakage_rev), 2, PHP_ROUND_HALF_UP) }}%</td>
</tr>