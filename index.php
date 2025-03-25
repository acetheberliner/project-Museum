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
    <style>
        .hero {
            height: 70vh;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .bg-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transition: opacity 1.5s ease-in-out;
            z-index: 0;
            opacity: 0;
        }

        .bg-layer.visible {
            opacity: 1;
        }
    </style>
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
    <div class="hero position-relative overflow-hidden">
        <div id="backgroundA" class="bg-layer"></div>
        <div id="backgroundB" class="bg-layer"></div>
        
        <div class="container position-relative text-center z-1 text-white">
            <h1 style="max-width: 600px; margin: 0 auto; font-size: 2.5rem;" class="mb-4">
                Scopri il mondo dell'arte con <span class="text-warning">Project-Museum</span>
            </h1>
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch('inc/backgrounds.json')
                .then(response => response.json())
                .then(images => {
                    let currentIndex = 0;
                    let currentLayer = true;

                    const layerA = document.getElementById("backgroundA");
                    const layerB = document.getElementById("backgroundB");

                    function setLayerBackground(layer, url) {
                        layer.style.backgroundImage = `linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('${url}')`;
                    }

                    function updateBackground() {
                        const nextImage = images[currentIndex];
                        currentIndex = (currentIndex + 1) % images.length;

                        if (currentLayer) {
                            setLayerBackground(layerB, nextImage);
                            layerB.classList.add("visible");
                            layerA.classList.remove("visible");
                        } else {
                            setLayerBackground(layerA, nextImage);
                            layerA.classList.add("visible");
                            layerB.classList.remove("visible");
                        }

                        currentLayer = !currentLayer;
                    }

                    // iniziale
                    setLayerBackground(layerA, images[0]);
                    layerA.classList.add("visible");
                    currentIndex = 1;

                    setInterval(updateBackground, 7000);
                });
        });
    </script>
</body>
</html>
