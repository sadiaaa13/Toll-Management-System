<?php 
require __DIR__.'/config.php';
if (!empty($_SESSION['admin_id'])) { header('Location: dashboard.php'); exit; }

$error='';
if ($_SERVER['REQUEST_METHOD']==='POST'){
  $u = post('username'); $p=post('password');
  $stmt = dbp("SELECT * FROM admins WHERE username=?","s",[$u]);
  $res = mysqli_stmt_get_result($stmt); $row = mysqli_fetch_assoc($res);
  if ($row && password_verify($p, $row['password_hash'])) {
    $_SESSION['admin_id']=$row['id']; $_SESSION['admin_name']=$row['name'];
    header('Location: dashboard.php'); exit;
  } else $error='Invalid credentials';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>TTMS — Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
<style>
  body {
    min-height: 100vh;
    background: linear-gradient(135deg, #0ea35a 0%, #14b8a6 100%);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Inter', sans-serif;
  }
  .login-card {
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,.2);
    width: 100%;
    max-width: 380px;
    animation: fadeIn 0.8s ease;
  }
  .login-card h4 {
    font-weight: 800; color: #0f172a;
  }
  .form-control {
    border-radius: 12px;
  }
  .btn-login {
    background: #0ea35a;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    transition: 0.3s;
  }
  .btn-login:hover {
    background: #0a7a44;
  }
  @keyframes fadeIn {
    from {opacity:0; transform: translateY(-20px);}
    to {opacity:1; transform: translateY(0);}
  }
</style>
</head>
<body>
  <div class="login-card">
    <div class="text-center mb-4">
      <i class="bi bi-shield-lock" style="font-size:3rem; color:#0ea35a;"></i>
      <h4 class="mt-2">Admin Login</h4>
      <p class="text-muted small">Secure access to TTMS</p>
    </div>
    
    <?php if($error): ?>
      <div class="alert alert-danger py-2"><?=$error?></div>
    <?php endif;?>
    
    <form method="post">
      <div class="mb-3">
        <label class="form-label fw-semibold">Username</label>
        <input name="username" class="form-control" placeholder="Enter username" required>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
      </div>
      <button class="btn btn-login w-100 py-2 text-white">
        <i class="bi bi-box-arrow-in-right"></i> Login
      </button>
    </form>
  </div>
</body>
</html>
