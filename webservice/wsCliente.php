<?php
require_once __DIR__ . '/../inc/require.php';
require_once __DIR__ . '/../inc/head.php';

session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../access/login.php");
    exit;
}

$page_title = 'Aggiorna cliente';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Aggiorna Cliente</title>
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
        <h1 class="mb-3 mb-md-0"><i class="bi bi-telephone"></i> Aggiorna Telefono Cliente</h1>
        <a href="../dashboard.php" class="btn btn-outline-dark"><i class="bi bi-arrow-left"></i> Torna alla Dashboard</a>
    </div>

    <div class="card-custom p-4 mt-4 w-100 w-md-75" style="max-width: 700px;">
        <div class="mb-4">
            <label class="form-label">ID Cliente</label>
            <input type="number" id="cli_id" class="form-control" placeholder="es. 1">
        </div>
        <div class="mb-4">
            <label class="form-label">Nuovo Telefono</label>
            <input type="text" id="cli_tel" class="form-control" placeholder="es. 3331112222">
        </div>
        <button class="btn btn-modern" id="btnAggiorna"><i class="bi bi-pencil-square"></i> Aggiorna Telefono</button>

        <div id="output" class="mt-4 bg-light p-3 border rounded text-monospace" style="min-height: 120px">// Risultato della richiesta</div>
    </div>
</div>

<script src="/laboratorio/progettoMuseo/js/wsCliente.js"></script>

</body>
</html>
