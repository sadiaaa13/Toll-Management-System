<?php
$page = "Emergency Card";
include "header.php";

// Handle form submission
$msg = ""; $err = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name   = post("name");
    $email  = post("email");
    $phone  = post("phone");
    $vehicle = post("vehicle_number");

    if (!$name || !$email || !$phone || !$vehicle) {
        $err = "All fields are required.";
    } else {
        $stmt = dbp(
            "INSERT INTO emergency_users(name, email, phone, vehicle_number, created_at) VALUES (?,?,?,?,NOW())",
            "ssss",
            [$name, $email, $phone, $vehicle]
        );
        if ($stmt) {
            $msg = "Emergency user registered successfully!";
        } else {
            $err = "Error saving data.";
        }
    }
}
?>

<div class="container-xl">
  <h4 class="fw-bold mb-3">Emergency Card Registration</h4>

  <?php if($msg): ?><div class="alert alert-success"><?=$msg?></div><?php endif;?>
  <?php if($err): ?><div class="alert alert-danger"><?=$err?></div><?php endif;?>

  <div class="card card-kpi">
    <div class="card-body">
      <form method="post" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Vehicle Number</label>
          <input type="text" name="vehicle_number" class="form-control" required>
        </div>
        <div class="col-12">
          <button class="btn btn-success"><i class="bi bi-check2-circle"></i> Register Emergency Card</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include "footer.html"; ?>
