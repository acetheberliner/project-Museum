<?php
require_once __DIR__ . '/inc/require.php';
require_once __DIR__ . '/inc/head.php';

session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: access/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Test API Museo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <!-- Sidebar -->
    <?php require_once __DIR__ . '/inc/sidebar.php'; ?>

    <!-- Main content -->
    <div class="container-api">
        <div class="dashboard-header mb-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h1 class="mb-3 mb-md-0">🧪 Test API Museo</h1>
            <a href="apidoc.php" target="_blank" class="btn btn-outline-primary">
                <i class="bi bi-journal-bookmark"></i> Documentazione API
            </a>
        </div>

        <!-- Selettore API -->
        <div class="mb-3 w-25">
            <label class="form-label">Seleziona API da testare:</label>
            <select id="apiSelect" class="form-select">
                <option value="mostre/attive">Mostre attive</option>
                <option value="mostra/1">Dettagli mostra con ID 1</option>
                <option value="opere/random">Opera casuale</option>
                <option value="opere/recenti">Ultime 5 opere</option>
                <option value="opere/autori">Numero opere per autore</option>
                <option value="clienti">Lista clienti</option>
                <option value="utenti">Lista utenti (solo admin)</option>
            </select>
        </div>

        <!-- Bottone chiamata -->
        <button id="callApi" class="btn btn-api mb-4">
            <i class="bi bi-beaker"></i> Invia Richiesta
        </button>

        <!-- Risultato -->
        <h5>Risultato:</h5>
        <pre id="result" class="result-box w-75">// La risposta apparirà qui</pre>
    </div>

    <!-- Script -->
    <script>
        const BASE_URL = 'http://localhost/laboratorio/progettoMuseo/api/';
        const APIKEY = 'b4st0I5868HdCLAdeotkrSPGdeT1Df9ixpeQpWgD';

        document.getElementById('callApi').addEventListener('click', async () => {
            const endpoint = document.getElementById('apiSelect').value;
            const resultBox = document.getElementById('result');
            resultBox.textContent = '// caricamento...';

            try {
                const response = await fetch(BASE_URL + endpoint, {
                    headers: {
                        'APIKEY': APIKEY,
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();
                resultBox.textContent = JSON.stringify(data, null, 4);
            } catch (err) {
                resultBox.textContent = '// Errore nella richiesta: ' + err.message;
            }
        });
    </script>
</body>
</html>
