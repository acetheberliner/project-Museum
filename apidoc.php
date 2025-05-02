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

    <!---------------------------------------------------------------------------------------------------------------->
    <section class="api-section">
      <h4>🎨 Mostre attive <span class="badge-api badge-public">pubblica</span></h4>

      <p><strong>🧭 Endpoint:</strong> <code>/mostre/attive</code></p>
      <p><strong>🔀 Metodo:</strong> <code>GET</code></p>
      <p><strong>📝 Descrizione:</strong> restituisce l'elenco di tutte le mostre attualmente in corso, ovvero quelle la cui data di inizio è precedente o uguale a oggi e la data di fine è successiva o uguale a oggi.</p>

      <h6>📩 Parametri:</h6>
      <table class="table table-sm table-bordered w-75">
          <thead>
              <tr><th>Nome</th><th>Tipo</th><th>Obbligatorio</th><th>Descrizione</th></tr>
          </thead>
          <tbody>
              <tr><td>(nessuno)</td><td>-</td><td>-</td><td>Questa API non richiede parametri.</td></tr>
          </tbody>
      </table>

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

      <h6>📘 Note:</h6>
      <ul>
          <li>Il campo <code>numero_opere</code> indica quante opere risultano associate alla mostra.</li>
          <li>Le date sono in formato <code>YYYY-MM-DD</code>.</li>
      </ul>

      <h6>⚠️ Errori:</h6>
      <p>Nessun errore previsto se il sistema è funzionante. Se non ci sono mostre attive, verrà restituito un array vuoto: <pre>[]</pre></p>
  </section>
<!---------------------------------------------------------------------------------------------------------------->
    
  <section class="api-section">
    <h4>🎫 Dettagli mostra + opere <span class="badge-api badge-public">pubblica</span></h4>

    <p><strong>🧭 Endpoint:</strong> <code>/mostra/{id}</code></p>
    <p><strong>🔀 Metodo:</strong> <code>GET</code></p>
    <p><strong>📝 Descrizione:</strong> restituisce i dettagli di una mostra specifica identificata tramite ID, insieme all'elenco delle opere associate alla mostra.</p>

    <h6>📩 Parametri:</h6>
    <table class="table table-sm table-bordered w-75">
        <thead>
            <tr><th>Nome</th><th>Tipo</th><th>Obbligatorio</th><th>Descrizione</th></tr>
        </thead>
        <tbody>
            <tr>
              <td><code>id</code></td>
              <td>int (via URI)</td>
              <td>Sì</td>
              <td>ID della mostra di cui si vogliono ottenere dettagli e opere.</td>
            </tr>
        </tbody>
    </table>

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

  <h6>📘 Note:</h6>
  <ul>
      <li>Se la mostra non ha opere associate, il campo <code>opere</code> conterrà un array vuoto.</li>
      <li>Le date sono fornite in formato <code>YYYY-MM-DD</code>.</li>
  </ul>

  <h6>⚠️ Errori:</h6>
  <pre>
  {
    "errore": "Mostra non trovata."
  }</pre>
</section>
<!---------------------------------------------------------------------------------------------------------------->
  <section class="api-section">
      <h4>🖼️ Opera casuale <span class="badge-api badge-public">pubblica</span></h4>

      <p><strong>🧭 Endpoint:</strong> <code>/opere/random</code></p>
      <p><strong>🔀 Metodo:</strong> <code>GET</code></p>
      <p><strong>📝 Descrizione:</strong> restituisce un'opera casuale tra quelle presenti nel database. Utile per suggerimenti o curiosità sull’arte.</p>

      <h6>📩 Parametri:</h6>
      <p>Nessun parametro richiesto.</p>

      <h6>📦 Risposta:</h6>
  <pre>{
    "ope_id": 2,
    "ope_titolo": "La Notte Stellata",
    "ope_autore": "Vincent van Gogh",
    "ope_anno": "1889",
    "ope_descrizione": "Capolavoro post-impressionista"
  }</pre>

      <h6>📘 Note:</h6>
      <ul>
          <li>Il risultato viene selezionato casualmente ad ogni richiesta.</li>
          <li>Il campo <code>ope_descrizione</code> può contenere un testo descrittivo dell’opera (se disponibile).</li>
      </ul>

      <h6>⚠️ Errori:</h6>
      <p>In caso di problemi interni al database, verrà restituito un errore HTTP <code>500</code> con messaggio generico.</p>
  </section>
<!---------------------------------------------------------------------------------------------------------------->
<section class="api-section">
    <h4>🆕 Ultime opere <span class="badge-api badge-public">pubblica</span></h4>

    <p><strong>🧭 Endpoint:</strong> <code>/opere/recenti</code></p>
    <p><strong>🔀 Metodo:</strong> <code>GET</code></p>
    <p><strong>📝 Descrizione:</strong> restituisce un array contenente le 5 opere più recentemente inserite nel database.</p>

    <h6>📩 Parametri:</h6>
    <p>Nessun parametro richiesto.</p>

    <h6>📦 Risposta:</h6>
<pre>[
  {
    "ope_id": 10,
    "ope_titolo": "Guernica",
    "ope_autore": "Pablo Picasso",
    "ope_anno": "1937",
    "ope_descrizione": "Opera simbolica contro la guerra"
  },
  {
    "ope_id": 9,
    "ope_titolo": "Il Bacio",
    "ope_autore": "Gustav Klimt",
    "ope_anno": "1907",
    "ope_descrizione": "Celebre rappresentazione del romanticismo"
  },
  ...
]</pre>

    <h6>📘 Note:</h6>
    <ul>
        <li>Le opere sono ordinate in modo decrescente secondo l'ID (<code>ope_id</code>).</li>
        <li>Vengono restituite al massimo 5 opere.</li>
    </ul>

    <h6>⚠️ Errori:</h6>
    <p>In caso di problemi con la query o il database, può essere restituito un errore HTTP <code>500</code>.</p>
</section>
<!---------------------------------------------------------------------------------------------------------------->
<section class="api-section">
    <h4>👨‍🎨 Statistiche autori <span class="badge-api badge-public">pubblica</span></h4>

    <p><strong>🧭 Endpoint:</strong> <code>/opere/autori</code></p>
    <p><strong>🔀 Metodo:</strong> <code>GET</code></p>
    <p><strong>📝 Descrizione:</strong> restituisce un array con l'elenco di tutti gli autori presenti nel database, ciascuno accompagnato dal numero totale di opere a lui attribuite.</p>

    <h6>📩 Parametri:</h6>
    <p>Nessun parametro richiesto.</p>

    <h6>📦 Risposta:</h6>
<pre>[
  {
    "ope_autore": "Leonardo da Vinci",
    "numero_opere": 3
  },
  {
    "ope_autore": "Pablo Picasso",
    "numero_opere": 2
  },
  ...
]</pre>

    <h6>📘 Note:</h6>
    <ul>
        <li>Gli autori sono ordinati in base al numero di opere in ordine decrescente.</li>
        <li>Autori senza opere associate non vengono inclusi nella risposta.</li>
    </ul>

    <h6>⚠️ Errori:</h6>
    <p>In caso di errore di query, viene restituito un errore HTTP <code>500</code> con messaggio descrittivo.</p>
</section>
<!---------------------------------------------------------------------------------------------------------------->
<section class="api-section">
    <h4>👥 Elenco clienti <span class="badge-api badge-public">pubblica</span></h4>

    <p><strong>🧭 Endpoint:</strong> <code>/clienti</code></p>
    <p><strong>🔀 Metodo:</strong> <code>GET</code></p>
    <p><strong>📝 Descrizione:</strong> restituisce un elenco completo di tutti i clienti registrati nel sistema.</p>

    <h6>📩 Parametri:</h6>
    <p>Nessun parametro richiesto.</p>

    <h6>📦 Risposta:</h6>
<pre>[
  {
    "cli_id": 1,
    "cli_nome": "Mario Rossi",
    "cli_email": "mario@email.com",
    "cli_telefono": "3331112222"
  },
  {
    "cli_id": 2,
    "cli_nome": "Luca Verdi",
    "cli_email": "luca@email.com",
    "cli_telefono": "3209988776"
  }
]</pre>

    <h6>📘 Note:</h6>
    <ul>
        <li>L'elenco è ordinato alfabeticamente per <code>cli_nome</code>.</li>
        <li>Ogni record contiene ID, nome, email e numero di telefono del cliente.</li>
    </ul>

    <h6>⚠️ Errori:</h6>
    <p>In caso di errore imprevisto del server, viene restituito un errore HTTP <code>500</code> con un messaggio di errore in JSON.</p>
</section>
<!---------------------------------------------------------------------------------------------------------------->
<section class="api-section">
    <h4>🔐 Elenco utenti <span class="badge-api badge-admin">solo admin</span></h4>

    <p><strong>🧭 Endpoint:</strong> <code>/utenti</code></p>
    <p><strong>🔀 Metodo:</strong> <code>GET</code></p>
    <p><strong>📝 Descrizione:</strong> restituisce l'elenco di tutti gli utenti registrati nel sistema. Accesso consentito solo agli utenti con ruolo <code>admin</code>.</p>

    <h6>📩 Parametri:</h6>
    <ul>
        <li><code>Sessione attiva</code> – la richiesta deve essere inviata da un utente autenticato come <code>admin</code>.</li>
    </ul>

    <h6>📦 Risposta:</h6>
<pre>[
  {
    "ute_id": 1,
    "ute_nome": "Tommaso Bagnolini",
    "ute_email": "tommaso.bagnolini@studio.unibo.com",
    "ute_ruolo": "admin"
  },
  {
    "ute_id": 2,
    "ute_nome": "Giulia Rossi",
    "ute_email": "giulia@example.com",
    "ute_ruolo": "user"
  }
]</pre>

    <h6>📘 Note:</h6>
    <ul>
        <li>Accessibile solo se l’utente corrente ha ruolo <code>admin</code>.</li>
        <li>Restituisce ID, nome, email e ruolo di ciascun utente.</li>
    </ul>

    <h6>⚠️ Errori:</h6>
<pre>{
  "errore": "Accesso riservato agli admin."
}</pre>
    <p>Errore HTTP <code>403</code> se l’utente non ha i permessi adeguati.</p>
</section>
<!---------------------------------------------------------------------------------------------------------------->
<section class="api-section">
    <h4>📊 Tutte le mostre <span class="badge-api badge-public">pubblica</span></h4>

    <p><strong>🧭 Endpoint:</strong> <code>/mostre</code></p>
    <p><strong>🔀 Metodo:</strong> <code>GET</code></p>
    <p><strong>📝 Descrizione:</strong> restituisce tutte le mostre registrate nel sistema, ordinate in base alla loro data di inizio (dalla più recente alla meno recente).</p>

    <h6>📩 Parametri:</h6>
    <ul>
        <li>Nessuno</li>
    </ul>

    <h6>📦 Risposta:</h6>
<pre>[
  {
    "mos_id": 5,
    "mos_nome": "Maestri Olandesi",
    "mos_data_inizio": "2025-01-10",
    "mos_data_fine": "2025-02-20"
  },
  {
    "mos_id": 3,
    "mos_nome": "Futurismo Italiano",
    "mos_data_inizio": "2024-11-01",
    "mos_data_fine": "2025-01-01"
  }
]</pre>

    <h6>📘 Note:</h6>
    <ul>
        <li>Utile per visualizzare una panoramica cronologica delle mostre.</li>
        <li>Il campo <code>mos_id</code> può essere usato per chiamate successive (es. dettagli mostra).</li>
    </ul>

    <h6>⚠️ Errori:</h6>
    <p>Nessun errore previsto, salvo problemi generici del server HTTP <code>500</code>.</p>
</section>
<!---------------------------------------------------------------------------------------------------------------->
</div>
</body>
</html>
