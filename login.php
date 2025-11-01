<?php
require_once __DIR__ . '/config.php';
csrf_check();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(strtolower($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    // Einfache Rate-Limitierung (pro IP)
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $_SESSION['login_tries'][$ip] = $_SESSION['login_tries'][$ip] ?? ['count'=>0, 'ts'=>time()];
    $tries = &$_SESSION['login_tries'][$ip];
    if ($tries['count'] >= 5 && (time() - $tries['ts'] < 300)) {
        $errors[] = 'Zu viele Versuche. Bitte in 5 Minuten erneut versuchen.';
    } else {
        try {
            $pdo = db();
            $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password_hash'])) {
                // Reset tries
                $tries = ['count'=>0, 'ts'=>time()];
                // Session härten
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                flash('ok', 'Erfolgreich angemeldet.');
                header('Location: ' . app_url('dashboard.php'));
                exit;
            } else {
                $tries['count'] += 1
                    ; $tries['ts'] = time();
                $errors[] = 'E-Mail oder Passwort falsch.';
            }
        } catch (Throwable $e) {
            if (APP_ENV !== 'production') {
                $errors[] = 'Fehler: ' . $e->getMessage();
            } else {
                $errors[] = 'Es ist ein Fehler aufgetreten.';
            }
        }
    }
}
?>
<?php require __DIR__ . '/partials/header.php'; ?>
<div class="card">
  <h1>Anmelden</h1>
  <?php if ($msg = flash('ok')): ?><div class="flash ok"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <?php if ($errors): ?>
    <div class="flash err">
      <?php foreach ($errors as $e): ?>
        <div><?= htmlspecialchars($e) ?></div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <form method="post" action="">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
    <label for="email">E-Mail</label>
    <input type="email" name="email" id="email" required>
    <label for="password">Passwort</label>
    <input type="password" name="password" id="password" required>
    <p><button class="btn" type="submit">Anmelden</button></p>
  </form>
  <p class="small">Noch kein Konto? <a href="register.php">Jetzt registrieren</a>.</p>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
