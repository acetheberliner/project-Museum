<?php
require_once __DIR__ . '/../inc/require.php';
require_once __DIR__ . '/../inc/head.php';

session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../access/login.php");
    exit;
}

$page_title = 'Tracking Mostra';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Tracking Mostra</title>
    <base href="/laboratorio/progettoMuseo/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/laboratorio/progettoMuseo/css/app.css">
</head>
<body>
<?php require_once __DIR__ . '/../inc/sidebar.php'; ?>
<div class="content">
    <div class="dashboard-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h1 class="mb-3 mb-md-0"><i class="bi bi-binoculars"></i> Tracking Mostra</h1>
        <a href="../dashboard.php" class="btn btn-outline-dark"><i class="bi bi-arrow-left"></i> Torna alla Dashboard</a>
    </div>

    <div class="card-custom p-4 mt-4 w-100 w-md-75" style="max-width: 700px;">
        <div class="mb-4">
            <label class="form-label">ID Mostra</label>
            <input type="number" id="inputId" class="form-control" placeholder="es. 1">
        </div>
        <button class="btn btn-modern" id="btnMostra"><i class="bi bi-search"></i> Mostra Dettagli</button>

        <div id="output" class="mt-4 bg-light p-3 border rounded text-monospace" style="min-height: 120px">// La risposta apparirà qui</div>
    </div>
</div>

<script src="/laboratorio/progettoMuseo/js/wsMostra.js"></script>

</body>
</html>
