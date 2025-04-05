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
    <title>Documentazione API - Project Museum</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css">
    <style>
        .container-api {
            margin-left: 260px;
            padding: 30px;
        }
        .api-section {
            background: #fff;
            border-left: 6px solid #00b4d8;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            padding: 25px;
            margin-bottom: 35px;
        }
        pre {
            background: #1e1e2f;
            color: #f1f1f1;
            padding: 15px;
            border-radius: 10px;
            font-size: 0.9rem;
            overflow-x: auto;
        }
        code {
            background: #f0f0f0;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .badge-api {
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 6px;
            margin-left: 8px;
        }
        .badge-public {
            background-color: #00b894;
            color: #fff;
        }
        .badge-admin {
            background-color: #d63031;
            color: #fff;
        }
        h4 {
            font-weight: 600;
            color: #00b4d8;
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/inc/sidebar.php'; ?>

<div class="container-api">
    <div class="dashboard-header mb-4 d-flex justify-content-between align-items-center">
        <h1 class="mb-0">📘 Documentazione API</h1>
        <a href="api_test.php" class="btn btn-modern"><i class="bi bi-flask"></i> Test API</a>
    </div>

    <!-- Autenticazione -->
    <section class="api-section">
        <h4>🔐 Autenticazione</h4>
        <p>Ogni richiesta deve includere l'APIKEY nell'header della richiesta.</p>
        <table class="table table-bordered w-75">
            <thead>
                <tr><th>Header</th><th>Valore</th></tr>
            </thead>
            <tbody>
                <tr><td>APIKEY</td><td><code>b4st0I5868HdCLAdeotkrSPGdeT1Df9ixpeQpWgD</code></td></tr>
                <tr><td>Content-Type</td><td><code>application/json</code></td></tr>
            </tbody>
        </table>
    </section>

    <!-- MOSTRE ATTIVE -->
    <section class="api-section">
        <h4>🎨 Mostre attive <span class="badge-api badge-public">pubblica</span></h4>
        <p><strong>GET</strong> <code>/mostre/attive</code></p>
        <p>Restituisce tutte le mostre attualmente in corso.</p>
        <h6>📦 Risposta:</h6>
<pre>[
  {
    "mos_id": 1,
    "mos_nome": "Rinascimento Italiano",
    "mos_data_inizio": "2025-04-01",
    "mos_data_fine": "2025-06-15",
    "numero_opere": 2
  }
]</pre>
    </section>

    <!-- MOSTRA + OPERE -->
    <section class="api-section">
        <h4>🎫 Dettagli mostra + opere <span class="badge-api badge-public">pubblica</span></h4>
        <p><strong>GET</strong> <code>/mostra/{id}</code></p>
        <p>Restituisce una mostra e le opere associate.</p>
        <h6>📩 Parametri:</h6>
        <ul><li><code>id</code> (int, obbligatorio) – identificativo mostra (via URI)</li></ul>
        <h6>📦 Risposta:</h6>
<pre>{
  "mostra": {
    "mos_nome": "Rinascimento Italiano",
    "mos_data_inizio": "2025-04-01",
    "mos_data_fine": "2025-06-15"
  },
  "opere": [
    {
      "ope_titolo": "Monna Lisa",
      "ope_autore": "Leonardo da Vinci",
      "ope_anno": "1503"
    }
  ]
}</pre>
        <h6>⚠️ Errori:</h6>
<pre>{
  "errore": "Mostra non trovata."
}</pre>
    </section>

    <!-- OPERA RANDOM -->
    <section class="api-section">
        <h4>🖼️ Opera casuale <span class="badge-api badge-public">pubblica</span></h4>
        <p><strong>GET</strong> <code>/opere/random</code></p>
        <p>Restituisce un'opera casuale dal database.</p>
        <h6>📦 Risposta:</h6>
<pre>{
  "ope_id": 2,
  "ope_titolo": "La Notte Stellata",
  "ope_autore": "Vincent van Gogh",
  "ope_anno": "1889",
  "ope_descrizione": "Capolavoro post-impressionista"
}</pre>
    </section>

    <!-- OPERE RECENTI -->
    <section class="api-section">
        <h4>🆕 Ultime opere <span class="badge-api badge-public">pubblica</span></h4>
        <p><strong>GET</strong> <code>/opere/recenti</code></p>
        <p>Restituisce le 5 opere più recenti.</p>
        <h6>📦 Risposta:</h6>
<pre>[
  {
    "ope_id": 10,
    "ope_titolo": "Guernica",
    "ope_autore": "Pablo Picasso",
    ...
  }
]</pre>
    </section>

    <!-- STATISTICHE AUTORI -->
    <section class="api-section">
        <h4>👨‍🎨 Statistiche autori <span class="badge-api badge-public">pubblica</span></h4>
        <p><strong>GET</strong> <code>/opere/autori</code></p>
        <p>Restituisce l'elenco degli autori con conteggio opere.</p>
        <h6>📦 Risposta:</h6>
<pre>[
  {
    "ope_autore": "Leonardo da Vinci",
    "numero_opere": 3
  }
]</pre>
    </section>

    <!-- CLIENTI -->
    <section class="api-section">
        <h4>👥 Elenco clienti <span class="badge-api badge-public">pubblica</span></h4>
        <p><strong>GET</strong> <code>/clienti</code></p>
        <p>Restituisce tutti i clienti registrati nel sistema.</p>
        <h6>📦 Risposta:</h6>
<pre>[
  {
    "cli_id": 1,
    "cli_nome": "Mario Rossi",
    "cli_email": "mario@email.com",
    "cli_telefono": "3331112222"
  }
]</pre>
    </section>

    <!-- UTENTI (solo admin) -->
    <section class="api-section">
        <h4>🔐 Elenco utenti <span class="badge-api badge-admin">solo admin</span></h4>
        <p><strong>GET</strong> <code>/utenti</code></p>
        <p>Restituisce l'elenco utenti se l’utente loggato è admin.</p>
        <h6>📦 Risposta:</h6>
<pre>[
  {
    "ute_id": 1,
    "ute_nome": "Tommaso Bagnolini",
    "ute_email": "tommy@example.com",
    "ute_ruolo": "admin"
  }
]</pre>
        <h6>⚠️ Errori:</h6>
<pre>{
  "errore": "Accesso riservato agli admin."
}</pre>
    </section>

    <!-- MOSTRE TUTTE -->
    <section class="api-section">
        <h4>📊 Tutte le mostre <span class="badge-api badge-public">pubblica</span></h4>
        <p><strong>GET</strong> <code>/mostre</code></p>
        <p>Restituisce tutte le mostre presenti, ordinate per data inizio.</p>
        <h6>📦 Risposta:</h6>
<pre>[
  {
    "mos_id": 5,
    "mos_nome": "Maestri Olandesi",
    "mos_data_inizio": "2025-01-10",
    "mos_data_fine": "2025-02-20"
  }
]</pre>
    </section>
</div>
</body>
</html>
