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

$total_mostre = count(Mostra::getMostre());
$total_pages = ceil($total_mostre / $limit);

$ruolo = $_SESSION['user_ruolo'] ?? 'user';

// Controllo ricerca
$search = $_GET['search'] ?? '';
$where = '';
$bind = [];

if ($search) {
    $where = "AND (mostra_nome LIKE :search)";
    $bind['search'] = "%$search%";
}

$mostre = Mostra::getMostre("$where LIMIT $limit OFFSET $offset", $bind);

// Eliminazione mostra
if ($ruolo === 'admin' && isset($_GET['azione']) && $_GET['azione'] == 'elimina' && isset($_GET['id'])) {
    $dbo->delete('mostre', 'mostra_id', $_GET['id']);
    header("Location: gestione_mostre.php?success=Mostra eliminata");
    exit;
}

// Recupero dati mostra per modifica
$edit_cliente = null;
if ($ruolo === 'admin' && isset($_GET['azione']) && $_GET['azione'] == 'modifica' && isset($_GET['id'])) {
    $edit_cliente = $dbo->find('mostre', 'mos_id', $_GET['id']);
}

// Inserimento o modifica mostra
if ($ruolo === 'admin' && $_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = $_POST['mos_id'] ?? null;
    $nome = $_POST['mos_nome'];
    $data_inizio = $_POST['mos_data_inizio'];
    $data_fine = $_POST['mos_data_fine'];

    if (!empty($id_cliente)) {
        // Modifica mostra
        $dbo->update('clienti', 'mos_id', $id_cliente, [
            'mos_nome' => $nome,
            'mos_data_inizio' => $data_inizio,
            'mos_data_fine' => $data_fine
        ]);
    } else {
        // Inserimento nuova mostra
        $dbo->insert('clienti', [
            'mos_nome' => $nome,
            'mos_data_inizio' => $data_inizio,
            'mos_data_fine' => $data_fine
        ]);
    }

    header("Location: gestione_clienti.php?success=Azione completata");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Gestione Mostre - Project-Museum</title>
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
            <h1>Gestione Mostre</h1>
        </div>

        <!-- Form Aggiunta/Modifica Mostra -->
        <?php if ($ruolo === 'admin'): ?>
            <div class="card card-custom p-3 my-4">
                <h5>Aggiungi o Modifica Mostra</h5>
                <form method="post">
                    <input type="hidden" name="mostra_id">
                    <label>Nome:</label>
                    <input type="text" name="mostra_nome" required class="form-control mb-2" placeholder="Inserisci titolo">
                    <label>Data Inizio:</label>
                    <input type="date" name="mostra_inizio" required class="form-control mb-2">
                    <label>Data Fine:</label>
                    <input type="date" name="mostra_fine" class="form-control mb-3">
                    <button type="submit" class="btn btn-success">💾 Salva</button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Lista Mostre -->
        <div class="card card-custom p-3">

            <h2>Lista Mostre</h2>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome Mostra</th>
                        <th>Data Inizio</th>
                        <th>Data Fine</th>
                        <?php if ($ruolo === 'admin'): ?><th>Azioni</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mostre as $mostra): ?>
                        <tr>
                            <td><?= $mostra['mos_id'] ?></td>
                            <td><?= htmlspecialchars($mostra['mos_nome']) ?></td>
                            <td><?= htmlspecialchars($mostra['mos_data_inizio']) ?></td>
                            <td><?= htmlspecialchars($mostra['mos_data_fine']) ?></td>
                            <?php if ($ruolo === 'admin'): ?>
                                <td>
                                    <a href="?azione=modifica&id=<?= $cliente['cli_id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                                    <a href="?azione=elimina&id=<?= $mostra['mos_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Eliminare la mostra?')">🗑️</a>
                                </td>
                            <?php endif; ?>
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
