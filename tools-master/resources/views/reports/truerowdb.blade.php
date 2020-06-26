<tr>
  <td>{{ $row['date_report'] }}</td>
  <td>{{ money_format('%.2n', $row['cost']) }}</td>
  <td>{{ money_format('%.2n', $row['revenue']) }}</td>
  <td>{{ money_format('%.2n', $row['profit']) }}</td>
  <td>{{ sprintf('%.2f', $row['roi']) }}%</td>
  <td>{{ number_format($row['redirects']) }}</td>
  <td>{{ money_format('%.4n', $row['cpr']) }}</td>
  <td>{{ money_format('%.4n', $row['rpr']) }}</td>
  <td>{{ sprintf('%.2f',$row['breakage']) }}%</td>
  <td>{{ round(($row['breakage_rd'] * 100), 2, PHP_ROUND_HALF_UP) }}%</td>
</tr>