<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', 1);

$base = __DIR__ . '/../';

require_once $base . 'config/Database.php';
require_once $base . 'classi/Utils.php';

require_once $base . 'classi/Cliente.php';
require_once $base . 'classi/Opera.php';
require_once $base . 'classi/Mostra.php';
require_once $base . 'classi/Utente.php';

try {
    $dbo = new Database();
} catch (Exception $e) {
    exit("❌ Errore di connessione al Database '$dbo->dbname': " . $e->getMessage());
}
