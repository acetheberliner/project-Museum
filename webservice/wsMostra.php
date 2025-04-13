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

<script>
const BASE_URL = 'http://localhost/laboratorio/progettoMuseo/api/';
const APIKEY = 'b4st0I5868HdCLAdeotkrSPGdeT1Df9ixpeQpWgD';

document.getElementById('btnMostra').addEventListener('click', async () => {
    const id = document.getElementById('inputId').value;
    const output = document.getElementById('output');

    if (!id) {
        output.innerHTML = '<em class="text-danger">// Inserisci un ID valido</em>';
        return;
    }

    output.innerHTML = '<em>// Caricamento...</em>';

    try {
        const res = await fetch(`${BASE_URL}mostra/${id}`, {
            headers: {
                'APIKEY': APIKEY,
                'Content-Type': 'application/json'
            }
        });

        const json = await res.json();

        if (json.errore) {
            output.innerHTML = `<div class="text-danger fw-bold">⚠️ ${json.errore}</div>`;
            return;
        }

        const mostra = json.mostra;
        const opere = json.opere;

        let html = `
            <h5 class="mb-3"><i class="bi bi-bank"></i> <strong>${mostra.mos_nome}</strong></h5>
            <p><strong>Data inizio:</strong> ${mostra.mos_data_inizio}<br>
               <strong>Data fine:</strong> ${mostra.mos_data_fine}</p>
        `;

        if (opere.length > 0) {
            html += `
                <h6 class="mt-4 mb-2 fw-bold"><i class="bi bi-palette"></i> Opere in mostra:</h6>
                <table class="table table-sm table-bordered bg-white">
                    <thead class="table-light">
                        <tr>
                            <th>Titolo</th>
                            <th>Autore</th>
                            <th>Anno</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${opere.map(opera => `
                            <tr>
                                <td>${opera.ope_titolo}</td>
                                <td>${opera.ope_autore}</td>
                                <td>${opera.ope_anno}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        } else {
            html += `<p class="text-muted">Nessuna opera associata a questa mostra.</p>`;
        }

        output.innerHTML = html;

    } catch (err) {
        output.innerHTML = `<span class="text-danger">// Errore: ${err.message}</span>`;
    }
});
</script>

</body>
</html>
