# BigFox Hosting – PHP Login & Registrierung (PDO, MySQL/SQLite, Sessions, CSRF)

Ein leichtgewichtiges, **wirklich funktionierendes** Auth-System für Shared Hosting.

## Features
- **Registrierung & Login** mit `password_hash()` und `password_verify()`
- **PDO & Prepared Statements** (gegen SQL-Injection)
- **CSRF-Schutz** (pro Formular ein Token)
- **Saubere Sessions** (sichere Cookies, Regeneration nach Login)
- **MySQL oder SQLite** wählbar
- Beispiel-Seiten: `index.php`, `register.php`, `login.php`, `dashboard.php`, `logout.php`

## Schnellstart (BigFox / Shared Hosting)
1. Lade den gesamten Ordner-Inhalt auf deinen Webspace (z. B. per FTP in `public_html/`).
2. **Datenbank wählen & konfigurieren:** Öffne `config.php` und trage deine MySQL-Zugangsdaten ein **oder** wähle SQLite.
3. **MySQL:** Lege in deinem Hosting-Panel eine Datenbank an und führe die Datei `init_db.mysql.sql` aus (phpMyAdmin).
4. **SQLite (Alternative):** Keine Schritte nötig – die Datei `data/app.sqlite` wird automatisch erzeugt (achte auf Schreibrechte im Ordner `data/`).
5. Öffne deine Domain: Du solltest die Startseite sehen. Registriere einen neuen Benutzer und logge dich ein.

## Sicherheitstipps
- In `config.php` `APP_ENV` auf `production` setzen.
- In `.htaccess` HTTPS-Weiterleitung aktivieren.
- Domain-Session-Cookie ggf. anpassen.
- Regelmäßige Updates & starke Passwörter.

Viel Erfolg! 🚀
