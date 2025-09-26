<?php 
$page='Recharge'; 
include 'header.php';

$msg=''; $err='';

if ($_SERVER['REQUEST_METHOD']==='POST'){
  $rfid = post('rfid_number');
  $amount = (float)post('amount');
  $note = post('note');

  if ($amount <= 0) {
    $err = 'Amount must be positive';
  } else {
    // Check if RFID exists in verified_users
    $stmt = dbp("SELECT id, balance FROM verified_users WHERE rfid_number=? LIMIT 1","s",[$rfid]);
    $rs = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($rs) != 1) {
      $err = 'RFID not found.';
    } else {
      $user = mysqli_fetch_assoc($rs);
      $newBalance = $user['balance'] + $amount;

      // Update balance in verified_users
      dbp("UPDATE verified_users SET balance=? WHERE rfid_number=?","ds",[$newBalance,$rfid]);

      // Insert recharge record
      dbp("INSERT INTO recharge(rfid_number, amount, recharge_time) VALUES (?,?,NOW())","sd",[$rfid,$amount]);

      $msg = "Recharge successful! New Balance: " . number_format($newBalance, 2);
    }
  }
}
?>

<div class="container-xl">
  <h4 class="fw-bold mb-3">Recharge Card</h4>

  <?php if($msg): ?><div class="alert alert-success"><?=$msg?></div><?php endif;?>
  <?php if($err): ?><div class="alert alert-danger"><?=$err?></div><?php endif;?>

  <div class="card card-kpi"><div class="card-body">
    <form method="post" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">RFID Number</label>
        <input name="rfid_number" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Amount</label>
        <input type="number" step="0.01" min="0.01" name="amount" class="form-control" required>
      </div>
      <div class="col-md-8">
        <label class="form-label">Note (optional)</label>
        <input name="note" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">Recharge Time</label>
        <input type="text" class="form-control" value="<?=date('Y-m-d H:i:s')?>" disabled>
      </div>
      <div class="col-12">
        <button class="btn btn-success"><i class="bi bi-plus-circle"></i> Add Balance</button>
      </div>
    </form>
  </div></div>
</div>

<?php include 'footer.html'; ?>
