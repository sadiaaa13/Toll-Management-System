<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';
?>

<h2>Pending User Requests</h2>



<table class="table">
  <thead>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Vehicle</th><th>Action</th></tr>
  </thead>
  <tbody>
<?php
$res = $conn->query("SELECT * FROM pending_users ORDER BY created_at DESC");
while ($row = $res->fetch_assoc()):
?>
    <tr>
      <td><?=htmlspecialchars($row['id'])?></td>
      <td><?=htmlspecialchars($row['name'])?></td>
      <td><?=htmlspecialchars($row['email'])?></td>
      <td><?=htmlspecialchars($row['phone'])?></td>
      <td><?=htmlspecialchars($row['vehicle_number'])?></td>
      <td>
        <form method="post" action="approve.php" style="display:inline-block;margin-right:8px;">
          <input type="hidden" name="id" value="<?=htmlspecialchars($row['id'])?>">
          <input type="text" name="rfid_number" placeholder="RFID" required class="input-small">
          <button class="btn btn-success">Approve</button>
        </form>
      </td>
    </tr>
<?php endwhile; ?>
  </tbody>
</table>


<h2>Emergency User Requests</h2>

<table class="table">
  <thead>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Vehicle</th><th>Action</th></tr>
  </thead>
  <tbody>
<?php
$res = $conn->query("SELECT * FROM emergency_users ORDER BY created_at DESC");
while ($row = $res->fetch_assoc()):
?>
    <tr>
      <td><?=htmlspecialchars($row['id'])?></td>
      <td><?=htmlspecialchars($row['name'])?></td>
      <td><?=htmlspecialchars($row['email'])?></td>
      <td><?=htmlspecialchars($row['phone'])?></td>
      <td><?=htmlspecialchars($row['vehicle_number'])?></td>
      <td>
        <form method="post" action="emergency_approve.php" style="display:inline-block;">
          <input type="hidden" name="id" value="<?=htmlspecialchars($row['id'])?>">
          <input type="text" name="rfid_number" placeholder="RFID" required class="input-small">
          <button class="btn btn-success">Approve as Emergency</button>
        </form>
      </td>
    </tr>
<?php endwhile; ?>
  </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
