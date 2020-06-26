@php  $profit = $row['revenue'] - $row['cost']; @endphp

<tr>
  <td>{{ sprintf("%.2f%%", (($profit / $row['cost']) * 100)) }}</td>
  <td>{{ money_format('%.4n', ($row['cost'] / $row['redirects'])) }}</td>
  <td>{{ money_format('%.4n', ($row['revenue'] / $row['redirects'])) }}</td>
</tr>