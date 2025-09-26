<?php 
$page='Dashboard'; 
include 'header.php';
?>

<div class="container-xl">

<?php if(empty($_SESSION['admin_id'])): ?>
  <!-- ==================== GUEST DASHBOARD ==================== -->
  <div id="carouselExample" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-inner rounded shadow">
      <div class="carousel-item active">
        <img src="assets/slide1.jpg" class="d-block w-100" alt="Toll System">
      </div>
      <div class="carousel-item">
        <img src="assets/slide2.jpg" class="d-block w-100" alt="Smart Cards">
      </div>
      <div class="carousel-item">
        <img src="assets/slide3.jpg" class="d-block w-100" alt="Automation">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>

  <div class="card card-kpi mb-4">
    <div class="card-body">
      <h3 class="fw-bold">Welcome to Toll Tax Management System</h3>
      <p class="text-muted">
        This platform allows seamless toll collection using RFID-based smart cards. 
        Users can recharge their balance, report lost cards, and access emergency services. 
        Our mission is to reduce traffic congestion and enable cashless transactions at toll booths.
      </p>
    </div>
  </div>

<?php else: ?>
  <!-- ==================== ADMIN DASHBOARD ==================== -->
  <div class="row g-3">
    <div class="col-md-3">
      <div class="card card-kpi"><div class="card-body">
        <div class="kpi-title">Total Users</div>
        <div id="k_users" class="kpi-value">0</div>
      </div></div>
    </div>
    <div class="col-md-3">
      <div class="card card-kpi"><div class="card-body">
        <div class="kpi-title">Total Cards</div>
        <div id="k_cards" class="kpi-value">0</div>
      </div></div>
    </div>
    <div class="col-md-3">
      <div class="card card-kpi"><div class="card-body">
        <div class="kpi-title">Passes Today</div>
        <div id="k_passes_today" class="kpi-value">0</div>
      </div></div>
    </div>
    <div class="col-md-3">
      <div class="card card-kpi"><div class="card-body">
        <div class="kpi-title">Recharges Today</div>
        <div id="k_recharges_today" class="kpi-value">0</div>
      </div></div>
    </div>
  </div>

  <div class="mt-4">
    <a class="btn btn-outline-success" href="users.php"><i class="bi bi-people"></i> Details of Users</a>
  </div>

  <script>
  async function loadKPIs(){
    const r = await fetch('api/stats.php'); const j = await r.json();
    k_users.textContent = j.total_users;
    k_cards.textContent = j.total_cards;
    k_passes_today.textContent = j.passes_today;
    k_recharges_today.textContent = j.recharges_today;
  }
  loadKPIs(); setInterval(loadKPIs, 3000);
  </script>
<?php endif; ?>

</div>

<?php include 'footer.html'; ?>
