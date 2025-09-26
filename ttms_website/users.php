<?php 
$page='Users'; 
include 'header.php';
$cn = db();

// Fetch data directly from verified_users
$q = mysqli_query($cn, "
    SELECT id, name, phone, vehicle_number, rfid_number, balance
    FROM verified_users
    ORDER BY name
");
?>
<div class="container-xl">
  <h4 class="fw-bold mb-3">Users</h4>
  <div class="card card-kpi">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Name</th>
              <th>Phone</th>
              <th>Vehicle</th>
              <th>RFID</th>
              <th>Balance</th>
            </tr>
          </thead>
          <tbody>
            <?php while($r = mysqli_fetch_assoc($q)): ?>
              <tr>
                <td>
                  <a class="text-decoration-none fw-semibold" href="user.php?id=<?=$r['id']?>">
                    <?=esc($r['name'])?>
                  </a>
                </td>
                <td><?=esc($r['phone'])?></td>
                <td><?=esc($r['vehicle_number'])?></td>
                <td><?=esc($r['rfid_number'])?></td>
                <td><?=number_format($r['balance'], 2)?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include 'footer.html'; ?>
