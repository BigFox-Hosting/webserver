<?php
require_once __DIR__ . '/config.php';
csrf_check();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(strtolower($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Ungültige E-Mail-Adresse.';
    if (strlen($password) < 8) $errors[] = 'Passwort muss mindestens 8 Zeichen lang sein.';
    if ($password !== $confirm) $errors[] = 'Passwörter stimmen nicht überein.';

    if (!$errors) {
        try {
            $pdo = db();
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = 'Diese E-Mail ist bereits registriert.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                if (DB_DRIVER === 'mysql') {
                    $stmt = $pdo->prepare('INSERT INTO users (email, password_hash, created_at) VALUES (?, ?, NOW())');
                } else {
                    $stmt = $pdo->prepare('INSERT INTO users (email, password_hash, created_at) VALUES (?, ?, datetime("now"))');
                }
                $stmt->execute([$email, $hash]);
                flash('ok', 'Registrierung erfolgreich. Bitte melde dich an.');
                header('Location: ' . app_url('login.php'));
                exit;
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
  <h1>Registrieren</h1>
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
    <label for="confirm">Passwort bestätigen</label>
    <input type="password" name="confirm" id="confirm" required>
    <p><button class="btn" type="submit">Konto erstellen</button></p>
  </form>
  <p class="small">Schon ein Konto? <a href="login.php">Hier anmelden</a>.</p>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
