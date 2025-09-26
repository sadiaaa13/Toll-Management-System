<?php
// includes/header.php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Smart Toll Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
  /* === Base setup === */
  body {
    font-family: "Inter", "Segoe UI", Arial, sans-serif;
    background: #f9fafb;
    margin: 0;
    color: #111827;
  }

  /* === Header === */
  .site-header {
    background: #0ea35a; /* dark navy */
    color: #fff;
    padding: 14px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }

  .site-header h1 {
    font-size: 20px;
    font-weight: 600;
    margin: 0;
    letter-spacing: .5px;
  }

  .site-header nav {
    display: flex;
    gap: 20px;
  }

  .site-header nav a {
    color: #cbd5e1;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    transition: color .2s, border-bottom .2s;
    padding-bottom: 3px;
  }

  .site-header nav a:hover {
    color: #fff;
    border-bottom: 2px solid #3b82f6;
  }

  /* === Page content wrapper === */
  .container {
    max-width: 1200px;
    margin: 24px auto;
    padding: 0 16px;
  }

  /* === Section headings === */
  .section-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 24px 0 12px;
    color: #1e293b;
  }

  /* === Card wrapper for tables === */
  .table-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    margin-bottom: 32px;
  }

  /* === Tables === */
  .table {
    width: 100%;
    border-collapse: collapse;
  }

  .table thead {
    background: #f3f4f6;
    text-transform: uppercase;
    font-size: 0.75rem;
    color: #6b7280;
  }

  .table th, .table td {
    padding: 14px 16px;
    text-align: left;
  }

  .table tbody tr {
    border-top: 1px solid #e5e7eb;
    transition: background 0.2s;
  }

  .table tbody tr:hover {
    background: #f9fafb;
  }

  /* === Inputs + Buttons === */
  .input-small {
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    font-size: 0.9rem;
    margin-right: 8px;
  }

  .btn {
    border: none;
    border-radius: 9999px;
    padding: 8px 16px;
    font-weight: 500;
    font-size: 0.85rem;
    cursor: pointer;
    transition: background 0.2s, transform 0.1s;
  }

  .btn:hover {
    transform: translateY(-1px);
  }

  .btn-primary { background: #2563eb; color: #fff; }
  .btn-primary:hover { background: #1d4ed8; }
  .btn-success { background: #16a34a; color: #fff; }
  .btn-danger { background: #dc2626; color: #fff; }
  .btn-warning { background: #f59e0b; color: #111; }
  </style>
</head>
<body>
  <header class="site-header">
    <h1>Smart Toll Management</h1>
    <nav>
      <a href="/toll_system/admin/index.php">Admin</a>
      <a href="/toll_system/transactions/live.php">Live Transactions</a>
      <a href="/toll_system/admin/lostcar.php">Lost Car Report</a>
      <a href="/toll_system/dashboard.php">Dashboard</a>
    </nav>
  </header>
  <main class="container">
