<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container my-4">
  <h2 class="mb-4 text-danger"><i class="bi bi-exclamation-triangle"></i> Reported Lost/Found Cars</h2>

  <div class="table-responsive shadow rounded">
    <table class="table table-striped table-hover table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">RFID</th>
          <th scope="col">Message</th>
          <th scope="col">Reported At</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
<?php
// get all rows, newest first
$res = $conn->query("SELECT * FROM lost_car ORDER BY request_time DESC");

$seen = []; // to track if we already processed an RFID
while ($row = $res->fetch_assoc()):
    $rfid = $row['rfid_number'];
    $status = $row['message'];
    $badgeClass = ($status === 'lost') ? 'bg-danger' : 'bg-success';

    // get verified user card_type
    $st = $conn->prepare("SELECT card_type FROM verified_users WHERE rfid_number = ? LIMIT 1");
    $st->bind_param("s", $rfid);
    $st->execute();
    $vu = $st->get_result()->fetch_assoc();
    $currentType = $vu['card_type'] ?? null;

    // check if this is the latest row for this RFID
    $isLatest = !isset($seen[$rfid]);
    $seen[$rfid] = true;
?>
        <tr>
          <td><?=htmlspecialchars($row['id'])?></td>
          <td><span class="fw-bold"><?=htmlspecialchars($rfid)?></span></td>
          <td><span class="badge <?=$badgeClass?>"><?=htmlspecialchars($status)?></span></td>
          <td><?=htmlspecialchars($row['request_time'])?></td>
          <td>
            <?php if ($isLatest): ?>
              <form method="post" action="update_lost.php" class="d-inline">
                <input type="hidden" name="id" value="<?=htmlspecialchars($row['id'])?>">
                <input type="hidden" name="rfid_number" value="<?=htmlspecialchars($rfid)?>">
                <input type="hidden" name="status" value="<?=htmlspecialchars($status)?>">

                <?php if ($status === 'lost' && $currentType !== 'lost'): ?>
                  <button class="btn btn-sm btn-danger">
                    <i class="bi bi-x-octagon"></i> Approve as Lost
                  </button>
                <?php elseif ($status === 'found' && $currentType === 'lost'): ?>
                  <button class="btn btn-sm btn-success">
                    <i class="bi bi-check-circle"></i> Approve as Valid
                  </button>
                <?php else: ?>
                  <span class="text-muted">Processed</span>
                <?php endif; ?>
              </form>
            <?php else: ?>
              <span class="text-muted">Processed</span>
            <?php endif; ?>
          </td>
        </tr>
<?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
