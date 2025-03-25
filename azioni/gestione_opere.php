<?php
require_once __DIR__ . '/../inc/require.php';
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}

$limit = 5; 
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $limit;

$total_opere = count(Opera::getOpere());
$total_pages = ceil($total_opere / $limit);

$ruolo = $_SESSION['user_ruolo'] ?? 'user';

// Controllo ricerca
$search = $_GET['search'] ?? '';
$where = '';
$bind = [];

if ($search) {
    $where = "AND (ope_titolo LIKE :search OR ope_autore LIKE :search)";
    $bind['search'] = "%$search%";
}

$opere = Opera::getOpere("$where LIMIT $limit OFFSET $offset", $bind);

// Eliminazione opera
if ($ruolo === 'admin' && isset($_GET['azione']) && $_GET['azione'] == 'elimina' && isset($_GET['id'])) {
    $dbo->delete('opere', 'ope_id', $_GET['id']);
    header("Location: gestione_opere.php?success=Opera eliminata");
    exit;
}

// Inserimento o modifica opera
if ($ruolo === 'admin' && $_SERVER["REQUEST_METHOD"] == "POST") {
    $titolo = $_POST['ope_titolo'];
    $autore = $_POST['ope_autore'];
    $anno = $_POST['ope_anno'];

    if (isset($_POST['ope_id']) && !empty($_POST['ope_id'])) {
        $dbo->update('opere', 'ope_id', $_POST['ope_id'], [
            'ope_titolo' => $titolo,
            'ope_autore' => $autore,
            'ope_anno' => $anno
        ]);
    } else {
        $dbo->insert('opere', [
            'ope_titolo' => $titolo,
            'ope_autore' => $autore,
            'ope_anno' => $anno
        ]);
    }

    header("Location: gestione_opere.php?success=Azione completata");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Gestione Opere - Project-Museum</title>
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
            <h1>Gestione Opere</h1>
        </div>

        <!-- Form Aggiunta/Modifica Opera -->
        <?php if ($ruolo === 'admin'): ?>
            <div class="card card-custom p-3 my-4">
                <h5>Aggiungi o Modifica Opera</h5>
                <form method="post">
                    <input type="hidden" name="ope_id">
                    <label>Titolo:</label>
                    <input type="text" name="ope_titolo" required class="form-control mb-2" placeholder="Inserisci titolo">
                    <label>Autore:</label>
                    <input type="text" name="ope_autore" required class="form-control mb-2" placeholder="Inserisci autore">
                    <label>Anno:</label>
                    <input type="number" name="ope_anno" class="form-control mb-3" placeholder="Inserisci anno di pubblicazione">
                    <button type="submit" class="btn btn-success">💾 Salva</button>
                </form>
            </div>
        <?php endif; ?>
        
        <div class="card card-custom p-3">
            <h2>Lista Opere</h2>
            <table class="table table-striped table-hover">
                <thead>
                    <tr><th>ID</th><th>Titolo</th><th>Autore</th><th>Anno</th><?php if ($ruolo === 'admin') echo "<th>Azioni</th>"; ?></tr>
                </thead>
                <tbody>
                    <?php foreach ($opere as $opera): ?>
                        <tr>
                            <td><?= $opera['ope_id'] ?></td>
                            <td><?= htmlspecialchars($opera['ope_titolo']) ?></td>
                            <td><?= htmlspecialchars($opera['ope_autore']) ?></td>
                            <td><?= htmlspecialchars($opera['ope_anno']) ?></td>
                            <?php if ($ruolo === 'admin'): ?><td><a href="?azione=elimina&id=<?= $opera['ope_id'] ?>" class="btn btn-danger btn-sm">🗑️</a></td><?php endif; ?>
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
