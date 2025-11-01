<?php
// config.php – zentrale Konfiguration
// Stelle sicher, dass dieser Ordner NICHT öffentlich listbar ist.

declare(strict_types=1);
if (session_status() === PHP_SESSION_NONE) {
    // Sichere Session-Cookies (wo möglich)
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    // In produktiven Umgebungen, sofern HTTPS aktiv:
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }
    session_start();
}

// === App ===
const APP_NAME = 'BigFox Auth';
const APP_ENV = 'development'; // 'production' für Live-Betrieb
const BASE_URL = ''; // optional: z. B. 'https://example.com' (für absolute Links)

// === DB: MySQL ODER SQLite wählen ===
// MySQL-Beispiel:
const DB_DRIVER = 'mysql'; // 'mysql' oder 'sqlite'
const DB_HOST   = 'localhost';
const DB_NAME   = 'bigfox_db';
const DB_USER   = 'bigfox_user';
const DB_PASS   = 'bigfox_pass';
const DB_CHARSET= 'utf8mb4';

// SQLite-Beispiel (nur verwenden, wenn DB_DRIVER = 'sqlite'):
const SQLITE_PATH = __DIR__ . '/data/app.sqlite';

function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;
    if (DB_DRIVER === 'mysql') {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } else if (DB_DRIVER === 'sqlite') {
        if (!is_dir(__DIR__ . '/data')) {
            mkdir(__DIR__ . '/data', 0775, true);
        }
        $dsn = 'sqlite:' . SQLITE_PATH;
        $pdo = new PDO($dsn, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        // Tabelle erzeugen falls nicht vorhanden
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            created_at TEXT NOT NULL
        );");
    } else {
        throw new RuntimeException('Unsupported DB_DRIVER');
    }
    return $pdo;
}

function app_url(string $path = ''): string {
    $base = rtrim(BASE_URL ?: (($_SERVER['HTTPS'] ?? 'off') !== 'off' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'], '/');
    return $base . '/' . ltrim($path, '/');
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_check(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(400);
            exit('Ungültiges CSRF-Token.');
        }
    }
}

function flash(string $key, ?string $msg = null): ?string {
    if ($msg === null) {
        $val = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $val;
    } else {
        $_SESSION['flash'][$key] = $msg;
        return null;
    }
}

function logged_in(): bool {
    return !empty($_SESSION['user_id']);
}

function require_login(): void {
    if (!logged_in()) {
        header('Location: ' . app_url('login.php'));
        exit;
    }
}

function current_user(): ?array {
    if (!logged_in()) return null;
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id, email, created_at FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}

// Extra Header
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
