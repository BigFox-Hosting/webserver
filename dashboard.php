<?php
require_once __DIR__ . '/config.php';
require_login();
$user = current_user();
?>
<?php require __DIR__ . '/partials/header.php'; ?>
<div class="card">
  <h1>Dashboard</h1>
  <?php if ($msg = flash('ok')): ?><div class="flash ok"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <p>Hallo <strong><?= htmlspecialchars($user['email'] ?? 'Unbekannt') ?></strong>!</p>
  <p class="muted small">Konto erstellt: <?= htmlspecialchars($user['created_at'] ?? '-') ?></p>
  <p><a class="btn" href="logout.php">Logout</a></p>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
