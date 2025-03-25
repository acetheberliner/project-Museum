<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Otteniamo il ruolo dell'utente
$ruolo = $_SESSION['user_ruolo'] ?? 'user';

// Determiniamo il percorso base in base alla posizione del file chiamante
$base_path = (strpos($_SERVER['PHP_SELF'], '/azioni/') !== false) ? "../" : "";

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProgettoMuseo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base_path ?>css/app.css">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= $base_path ?>dashboard.php">ProgettoMuseo</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>azioni/gestione_clienti.php">Clienti</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>azioni/gestione_opere.php">Opere</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>azioni/gestione_mostre.php">Mostre</a></li>
                <?php if ($ruolo === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= $base_path ?>azioni/gestione_utenti.php">Utenti</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link btn btn-outline-danger text-white" href="<?= $base_path ?>logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
