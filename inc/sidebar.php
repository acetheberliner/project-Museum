<?php
// Verifica se la sessione è attiva
if (!isset($_SESSION)) {
    session_start();
}

$ruolo = $_SESSION['user_ruolo'] ?? 'user';

// Determina il percorso base (dashboard o sotto-cartelle)
$base_path = (basename(getcwd()) == "azioni") ? "../" : "";
?>

<!-- Sidebar -->
<div class="sidebar">
    <div class="user-info border border-0">
        <img src="https://i.pravatar.cc/100?u=<?= $_SESSION['user_id'] ?>" alt="Avatar">
        <h5><?= htmlspecialchars($_SESSION['user_nome']) ?></h5>
        <p><?= ucfirst($ruolo) ?></p>
    </div>
    <a href="<?= $base_path ?>dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
    <a href="<?= $base_path ?>azioni/gestione_clienti.php"><i class="bi bi-person"></i> Clienti</a>
    <a href="<?= $base_path ?>azioni/gestione_opere.php"><i class="bi bi-palette"></i> Opere</a>
    <a href="<?= $base_path ?>azioni/gestione_mostre.php"><i class="bi bi-building"></i> Mostre</a>
    <?php if ($ruolo === 'admin'): ?>
        <a href="<?= $base_path ?>azioni/gestione_utenti.php"><i class="bi bi-key"></i> Utenti</a>
    <?php endif; ?>
    <a href="<?= $base_path ?>logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>
