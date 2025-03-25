<?php
require_once __DIR__ . '/inc/require.php';
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
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

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            color: #333;
        }

        /* STRUTTURA BASE */
        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex: 1;
            padding: 30px;
            margin-left: 260px;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #1e1e2f;
            color: white;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 15px 20px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .sidebar a:hover, .sidebar a.active {
            background: #292b3a;
            border-left: 5px solid #00b4d8;
            padding-left: 15px;
        }

        .sidebar .user-info {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid #444;
        }

        .sidebar .user-info img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 3px solid #00b4d8;
            margin-bottom: 10px;
        }

        /* HEADER */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dashboard-header h1 {
            font-weight: 600;
            font-size: 24px;
        }

        .btn-modern {
            background: #00b4d8;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-modern:hover {
            background: #008cba;
        }

        /* CARDS */
        .dashboard-card {
            transition: 0.3s;
            border-radius: 15px;
            cursor: pointer;
            background: white;
            padding: 25px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .dashboard-card i {
            font-size: 40px;
            color: #00b4d8;
            margin-bottom: 10px;
        }

        /* FOOTER */
        .footer {
            background: white;
            padding: 15px;
            text-align: center;
            box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.1);
            font-size: 14px;
            width: 100%;
        }
    </style>
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
        <footer class="footer">
            <p>© 2024 Project-Museum</p>
            <p>Bagnolini Tommaso<br/><span class="text-info fw-bold">Laboratorio di Applicazioni e Servizi Web</span></p>
        </footer>

    </div>
</body>
</html>
