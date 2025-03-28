<?php
require_once __DIR__ . '/inc/require.php';
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: access/login.php");
    exit;
}

$ruolo = $_SESSION['user_ruolo'] ?? 'user';

// Conteggi per statistiche rapide
$num_clienti = count(Cliente::getClienti());
$num_opere = count(Opera::getOpere());
$num_mostre = count(Mostra::getMostre());
$num_utenti = count(Utente::getUtenti());
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Project-Museum</title>

    <!-- Font Google: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Global CSS -->
    <link rel="stylesheet" href="css/app.css">
</head>
<body>

    <!-- Wrapper -->
    <div class="wrapper">

        <!-- Sidebar -->
        <?php require_once __DIR__ . '/inc/sidebar.php'; ?>

        <!-- Contenuto -->
        <div class="content">
            <div class="dashboard-header">
                <h1>Benvenuto, <?= htmlspecialchars($_SESSION['user_nome']) ?> 👋</h1>
                <a href="azioni/gestione_opere.php" class="btn btn-modern">+ Aggiungi Nuova Opera</a>
            </div>

            <!-- Cards -->
            <div class="row mt-4 g-4">
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <i class="bi bi-people"></i>
                        <h4>Clienti</h4>
                        <p class="fs-3"><?= $num_clienti ?></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <i class="bi bi-image"></i>
                        <h4>Opere</h4>
                        <p class="fs-3"><?= $num_opere ?></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <i class="bi bi-bank"></i>
                        <h4>Mostre</h4>
                        <p class="fs-3"><?= $num_mostre ?></p>
                    </div>
                </div>
                <?php if ($ruolo === 'admin'): ?>
                    <div class="col-md-3">
                        <div class="dashboard-card">
                            <i class="bi bi-person-check"></i>
                            <h4>Utenti</h4>
                            <p class="fs-3"><?= $num_utenti ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Footer -->
        <?php require_once __DIR__ . '/inc/footer.php'; ?>
    </div>
</body>
</html>
