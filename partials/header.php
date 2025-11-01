<?php
require_once __DIR__ . '/config.php';
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= APP_NAME ?></title>
  <style>
    :root { --bg:#0b1220; --card:#111a2b; --muted:#7c8aa5; --acc:#5b8cff; --ok:#21c55d; --err:#ef4444; }
    *{box-sizing:border-box}
    body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:var(--bg);color:#e6eaf2}
    .wrap{max-width:880px;margin:40px auto;padding:0 16px}
    .card{background:var(--card);border:1px solid #1d2a44;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,.25);padding:24px}
    h1,h2{margin:0 0 12px}
    a{color:var(--acc);text-decoration:none}
    input[type=email],input[type=password],input[type=text]{width:100%;padding:12px 14px;background:#0a1324;color:#e6eaf2;border:1px solid #1d2a44;border-radius:10px}
    label{display:block;margin-top:10px;margin-bottom:6px;color:#c9d6ea}
    .btn{display:inline-block;background:var(--acc);color:white;border:none;padding:12px 16px;border-radius:12px;font-weight:600;cursor:pointer}
    .row{display:flex;gap:16px;flex-wrap:wrap}
    .grow{flex:1}
    .muted{color:var(--muted)}
    .flash{padding:12px 14px;border-radius:10px;margin:12px 0}
    .flash.ok{background:rgba(33,197,93,.15);border:1px solid #2cca74}
    .flash.err{background:rgba(239,68,68,.15);border:1px solid #ff6b6b}
    nav{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
    .navlinks a{margin-left:12px}
    footer{margin-top:24px;color:#93a2be}
    .small{font-size:.925rem}
  </style>
</head>
<body>
<div class="wrap">
  <nav>
    <div><strong><?= APP_NAME ?></strong></div>
    <div class="navlinks small">
      <a href="index.php">Start</a>
      <?php if (!logged_in()): ?>
        <a href="register.php">Registrieren</a>
        <a href="login.php">Anmelden</a>
      <?php else: ?>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
      <?php endif; ?>
    </div>
  </nav>
