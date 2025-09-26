<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';
?>

<h2>Live Transactions</h2>
<meta http-equiv="refresh" content="3">

<table class="table">
  <thead><tr><th>Time</th><th>Vehicle</th><th>RFID</th><th>Current Balance</th><th>Result</th></tr></thead>
  <tbody>
<?php
$res = $conn->query("SELECT * FROM transactions ORDER BY transaction_time DESC LIMIT 50");
while ($r = $res->fetch_assoc()):
?>
    <tr>
      <td><?=htmlspecialchars($r['transaction_time'])?></td>
      <td><?=htmlspecialchars($r['v_number'] ?? '')?></td>
      <td><?=htmlspecialchars($r['rfid'])?></td>
      <td><?=number_format($r['amount'],2)?></td>
      <td><?=htmlspecialchars($r['c_type'])?></td>
    </tr>
<?php endwhile; ?>
  </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
