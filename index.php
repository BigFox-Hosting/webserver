<?php require __DIR__ . '/partials/header.php'; ?>
  <div class="card">
    <h1>Willkommen bei <?= APP_NAME ?></h1>
    <p class="muted">Ein minimales, sicheres Login- & Registrierungssystem für BigFox Hosting.</p>
    <div class="row">
      <div class="grow">
        <h2>Loslegen</h2>
        <p><a class="btn" href="register.php">Jetzt registrieren</a></p>
        <p class="small">Schon ein Konto? <a href="login.php">Hier anmelden</a>.</p>
      </div>
      <div class="grow">
        <h2>Hinweise</h2>
        <ul>
          <li>Passwörter werden sicher gehasht.</li>
          <li>Formulare sind CSRF-geschützt.</li>
          <li>Sessions werden nach Login regeneriert.</li>
        </ul>
      </div>
    </div>
  </div>
<?php require __DIR__ . '/partials/footer.php'; ?>
