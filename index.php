<?php
session_start();
$loggedIn = isset($_SESSION['loggedin']);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project-Museum</title>
    
    <!-- Font Google: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body style="font-family: 'Poppins', sans-serif; background-color: #e3e6ea; color: #333;">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: #1d1f21;;">
        <div class="container">
            <a class="navbar-brand" href="index.php" style="font-size: 1.8rem; font-weight: 600;">🎨 Project-Museum</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero d-flex align-items-center text-center justify-content-center flex-column" 
        style="background: linear-gradient(rgba(0, 0, 0, 0.37), rgba(0, 0, 0, 0.36)), url('https://images.unsplash.com/photo-1578301978018-3005759f48f7?q=80&w=2044&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') center/cover no-repeat; 
               max-height: 78vh; width: 100%; color: white; padding: 20px; display: flex;">
        <div class="container">
            <h1 style="max-width: 600px; margin: 0 auto; font-size: 2.5rem;" class="mb-4">Scopri il mondo dell'arte con <span class="text-warning">Project-Museum</span></h1>
            <p class="lead" style="font-size: 1.2rem;">Gestisci le mostre, le opere e i visitatori con semplicità</p>
            <?php if ($loggedIn): ?>
                <a href="dashboard.php" class="btn btn-light btn-lg mt-3">📋 Vai alla Dashboard</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary btn-lg mt-2" style="z-index: 2; position: relative;">🔐 Accedi</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sezione Informazioni -->
    <div class="container py-5 text-center">
        <h2 class="mb-4" style="font-size: 2rem;">Esplora le feature</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="icon" style="font-size: 50px; color:rgb(179, 0, 0); margin-bottom: 15px;"><i class="bi bi-building"></i></div>
                <h3>Gestione Mostre</h3>
                <p>Organizza, pianifica e aggiorna le mostre in modo intuitivo.</p>
            </div>
            <div class="col-md-4">
                <div class="icon" style="font-size: 50px; color: #0056b3; margin-bottom: 15px;"><i class="bi bi-palette"></i></div>
                <h3>Catalogo Opere</h3>
                <p>Consulta e modifica le opere presenti nel museo.</p>
            </div>
            <div class="col-md-4">
                <div class="icon" style="font-size: 50px; color:rgb(0, 179, 60); margin-bottom: 15px;"><i class="bi bi-person-check"></i></div>
                <h3>Accesso Sicuro</h3>
                <p>Gestisci gli utenti e proteggi i dati con login sicuro.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer text-center" style="background: #1d1f21; color: white; padding: 20px 0;">
        <p>© 2024 Project-Museum - Bagnolini Tommaso</p>
        <p><span class="text-warning">Laboratorio di Applicazioni e Servizi Web</span><br/>Tecnologie dei Sistemi Informatici.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Media Queries -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function adjustHeroHeight() {
                document.querySelector('.hero').style.minHeight = window.innerHeight + 'px';
            }
            adjustHeroHeight();
            window.addEventListener("resize", adjustHeroHeight);
        });
    </script>

</body>
</html>
