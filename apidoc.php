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
</head>
<body>
<?php require_once __DIR__ . '/inc/sidebar.php'; ?>
<div class="container-api" style="margin-left:260px; padding:30px;">
    <h1 class="mb-4">📘 Documentazione API - Project Museum</h1>

    <section class="mb-5">
        <h3>🔐 Autenticazione</h3>
        <p>Tutte le richieste devono includere una APIKEY negli header.</p>
        <table class="table table-bordered w-50">
            <thead><tr><th>Parametro</th><th>Valore</th></tr></thead>
            <tbody>
                <tr><td>APIKEY</td><td><code>b4st0I5868HdCLAdeotkrSPGdeT1Df9ixpeQpWgD</code></td></tr>
                <tr><td>Content-Type</td><td><code>application/json</code></td></tr>
            </tbody>
        </table>
    </section>

    <!-- API 1 -->
    <section class="mb-5">
        <h4>🎨 Mostre attive</h4>
        <p><strong>GET</strong> <code>/mostre/attive</code></p>
        <p>Restituisce tutte le mostre attualmente in corso (data odierna compresa tra data_inizio e data_fine).</p>
        <h6>🔁 Risposta:</h6>
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

    <!-- API 2 -->
    <section class="mb-5">
        <h4>🎫 Dettagli mostra con opere</h4>
        <p><strong>GET</strong> <code>/mostra/{id}</code></p>
        <p>Restituisce i dettagli di una mostra specifica e le opere ad essa associate.</p>
        <h6>📩 Parametri:</h6>
        <ul><li><strong>id</strong> (int, obbligatorio) – ID della mostra</li></ul>
        <h6>🔁 Risposta:</h6>
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
    </section>

    <!-- API 3 -->
    <section class="mb-5">
        <h4>🖼️ Opera casuale</h4>
        <p><strong>GET</strong> <code>/opere/random</code></p>
        <p>Restituisce un'opera casuale dal database.</p>
        <h6>🔁 Risposta:</h6>
<pre>{
    "ope_id": 2,
    "ope_titolo": "La Notte Stellata",
    "ope_autore": "Vincent van Gogh",
    "ope_anno": "1889",
    "ope_descrizione": "Capolavoro post-impressionista"
}</pre>
    </section>

    <!-- API 4 -->
    <section class="mb-5">
        <h4>🆕 Ultime opere inserite</h4>
        <p><strong>GET</strong> <code>/opere/recenti</code></p>
        <p>Restituisce le 5 opere più recentemente aggiunte.</p>
        <h6>🔁 Risposta:</h6>
<pre>[
    {
        "ope_id": 10,
        "ope_titolo": "Guernica",
        ...
    }
]</pre>
    </section>

    <!-- API 5 -->
    <section class="mb-5">
        <h4>👨‍🎨 Statistiche autori</h4>
        <p><strong>GET</strong> <code>/opere/autori</code></p>
        <p>Restituisce un elenco degli autori e il numero di opere associate.</p>
        <h6>🔁 Risposta:</h6>
<pre>[
    {
        "ope_autore": "Leonardo da Vinci",
        "numero_opere": 3
    },
    ...
]</pre>
    </section>

    <!-- API 6 -->
    <section class="mb-5">
        <h4>👥 Elenco clienti</h4>
        <p><strong>GET</strong> <code>/clienti</code></p>
        <p>Restituisce l'elenco di tutti i clienti.</p>
        <h6>🔁 Risposta:</h6>
<pre>[
    {
        "cli_id": 1,
        "cli_nome": "Mario Rossi",
        "cli_email": "mario@email.com",
        "cli_telefono": "3331112222"
    }
]</pre>
    </section>

    <!-- API 7 -->
    <section class="mb-5">
        <h4>🔐 Elenco utenti (solo admin)</h4>
        <p><strong>GET</strong> <code>/utenti</code></p>
        <p>Restituisce l'elenco degli utenti, solo se l'utente loggato ha ruolo <code>admin</code>.</p>
        <h6>🔁 Risposta:</h6>
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

    <!-- API 8 -->
    <section class="mb-5">
        <h4>📊 Tutte le mostre</h4>
        <p><strong>GET</strong> <code>/mostre</code></p>
        <p>Restituisce l’elenco completo di tutte le mostre, ordinate per data di inizio.</p>
        <h6>🔁 Risposta:</h6>
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
