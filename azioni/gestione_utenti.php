<?php
require_once __DIR__ . '/../inc/require.php';
session_start();

// Solo gli admin possono accedere
if (!isset($_SESSION['loggedin']) || $_SESSION['user_ruolo'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

$limit = 5;
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $limit;

$total_utenti = count(Utente::getUtenti());
$total_pages = ceil($total_utenti / $limit);

// Controllo ricerca
$search = $_GET['search'] ?? '';
$where = '';
$bind = [];

if ($search) {
    $where = "AND (ute_nome LIKE :search OR ute_email LIKE :search)";
    $bind['search'] = "%$search%";
}

$utenti = Utente::getUtenti("$where LIMIT $limit OFFSET $offset", $bind);

// Eliminazione utente
if (isset($_GET['azione']) && $_GET['azione'] == 'elimina' && isset($_GET['id'])) {
    $dbo->delete('utenti', 'ute_id', $_GET['id']);
    header("Location: gestione_utenti.php?success=Utente eliminato");
    exit;
}

// Inserimento o modifica utente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['ute_nome'];
    $email = $_POST['ute_email'];
    $password = password_hash($_POST['ute_password'], PASSWORD_DEFAULT);
    $ruolo = $_POST['ute_ruolo'];

    if (isset($_POST['ute_id']) && !empty($_POST['ute_id'])) {
        $dbo->update('utenti', 'ute_id', $_POST['ute_id'], [
            'ute_nome' => $nome,
            'ute_email' => $email,
            'ute_ruolo' => $ruolo
        ]);
    } else {
        $dbo->insert('utenti', [
            'ute_nome' => $nome,
            'ute_email' => $email,
            'ute_password' => $password,
            'ute_ruolo' => $ruolo
        ]);
    }

    header("Location: gestione_utenti.php?success=Azione completata");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Gestione Utenti - Project-Museum</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
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

        .container-custom {
            margin-left: 260px;
            padding: 30px;
            flex: 1;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-modern {
            background: #00b4d8;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-modern:hover {
            background: #008cba;
        }

        .pagination .page-item.active .page-link {
            background-color: #00b4d8;
            border-color: #00b4d8;
        }

        .card-custom {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php require_once __DIR__ . '/../inc/sidebar.php'; ?>

    <div class="container-custom">
        <div class="dashboard-header">
            <h1>Gestione Utenti</h1>
        </div>

        <!-- Form Aggiunta/Modifica Utente -->
        <div class="card card-custom p-3 my-4">
            <h5>Aggiungi o Modifica Utente</h5>
            <form method="post">
                <input type="hidden" name="ute_id">
                <label>Nome:</label>
                <input type="text" name="ute_nome" required class="form-control mb-2" placeholder="Inserisci nome e cognome">
                <label>Email:</label>
                <input type="email" name="ute_email" required class="form-control mb-2" placeholder="Inserisci email">
                <label>Password:</label>
                <input type="password" name="ute_password" required class="form-control mb-2" placeholder="Inserisci password">
                <label>Ruolo:</label>
                <select name="ute_ruolo" class="form-control mb-3">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" class="btn btn-success">💾 Salva</button>
            </form>
        </div>

        <div class="card card-custom p-3">
            <h2>Lista Utenti</h2>
            <table class="table table-striped table-hover">
                <thead>
                    <tr><th>ID</th><th>Nome</th><th>Email</th><th>Ruolo</th><th>Azioni</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($utenti as $utente): ?>
                        <tr>
                            <td><?= $utente['ute_id'] ?></td>
                            <td><?= htmlspecialchars($utente['ute_nome']) ?></td>
                            <td><?= htmlspecialchars($utente['ute_email']) ?></td>
                            <td><span class="badge <?= $utente['ute_ruolo'] === 'admin' ? 'bg-danger' : 'bg-primary' ?>"><?= htmlspecialchars($utente['ute_ruolo']) ?></span></td>
                            <td><a href="?azione=elimina&id=<?= $utente['ute_id'] ?>" class="btn btn-danger btn-sm">🗑️</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <nav>
                <ul class="pagination mb-0">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>

</body>
</html>
