<?php // seed_admin.php
require __DIR__.'/config.php';
$r = mysqli_query(db(), "SELECT COUNT(*) AS c FROM admins");
$c = mysqli_fetch_assoc($r)['c'] ?? 0;
if ($c == 0) {
  $u = 'admin'; $p = password_hash('admin123', PASSWORD_DEFAULT);
  $n = 'Administrator';
  $stmt = dbp("INSERT INTO admins(username,password_hash,name) VALUES (?,?,?)", "sss", [$u,$p,$n]);
  echo "Admin created → <b>admin / admin123</b>";
} else echo "Admin already exists.";
