<?php

error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE ^ E_DEPRECATED);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classi/Cliente.php';
require_once __DIR__ . '/../classi/Opera.php';
require_once __DIR__ . '/../classi/Mostra.php';
require_once __DIR__ . '/../classi/Utente.php';
require_once __DIR__ . '/../classi/Utils.php';

// Debug: Verifica la connessione
try {
    $dbo = new Database();
} catch (Exception $e) {
    die("❌ ERRORE DATABASE: " . $e->getMessage());
}
?>
