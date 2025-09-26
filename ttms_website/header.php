<?php 
require_once __DIR__.'/config.php';
$page = $page ?? 'Dashboard';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>TTMS — <?=esc($page)?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
<link href="assets/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg topbar">
  <div class="container-fluid">
    <span class="navbar-brand fw-bold text-white">TTMS</span>
    <div class="d-flex align-items-center gap-3 text-white">
      <?php if(!empty($_SESSION['admin_id'])): ?>
        <span class="small d-none d-sm-inline">Hi, <?=esc($_SESSION['admin_name'])?></span>
        <a class="btn btn-sm btn-light" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
      <?php else: ?>
        <a class="btn btn-sm btn-light" href="login.php"><i class="bi bi-box-arrow-in-right"></i> Admin Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="d-flex">
  <aside class="sidebar">
    <a href="dashboard.php" class="slink"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="emergency.php" class="slink"><i class="bi bi-exclamation-triangle"></i> Emergency Card</a>
    <a href="recharge.php" class="slink"><i class="bi bi-currency-dollar"></i> Recharge</a>

    <?php if(!empty($_SESSION['admin_id'])): ?>
      <hr class="text-secondary">
      <a href="users.php" class="slink"><i class="bi bi-people"></i> Details of Users</a>
      <!-- you can add more admin-only options here -->
    <?php endif; ?>
  </aside>
  <main class="content container-fluid py-4">
