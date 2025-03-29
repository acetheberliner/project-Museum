<?php
require_once __DIR__ . '/../inc/require.php';
require_once __DIR__ . '/../classi/Utils.php';

session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../access/login.php");
    exit;
}

$page_title = 'Gestione Mostre';
require_once __DIR__ . '/../inc/head.php';

$limit = 5;
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $limit;

$total_mostre = count(Mostra::getMostre());
$total_pages = ceil($total_mostre / $limit);

$ruolo = $_SESSION['user_ruolo'] ?? 'user';

$search = $_GET['search'] ?? '';
$where = '';
$bind = [];

if ($search) {
    $where = "AND (mos_nome LIKE :search)";
    $bind['search'] = "%$search%";
}

$mostre = Mostra::getMostre("$where LIMIT $limit OFFSET $offset", $bind);

// elimina mostra
if ($ruolo === 'admin' && $_GET['azione'] === 'elimina' && isset($_GET['id'])) {
    $dbo->delete('mostre', 'mos_id', $_GET['id']);
    header("Location: gestione_mostre.php?success=Mostra eliminata");
    exit;
}

// modifica mostra
$edit_mostra = null;
if ($ruolo === 'admin' && $_GET['azione'] === 'modifica' && isset($_GET['id'])) {
    $edit_mostra = $dbo->find('mostre', 'mos_id', $_GET['id']);
}

// inserimento o update
if ($ruolo === 'admin' && $_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['mos_id'] ?? null;
    $nome = $_POST['mos_nome'];
    $inizio = $_POST['mos_data_inizio'];
    $fine = $_POST['mos_data_fine'];

    $dati = [
        'mos_nome' => $nome,
        'mos_data_inizio' => $inizio,
        'mos_data_fine' => $fine
    ];

    if ($id) {
        $dbo->update('mostre', 'mos_id', $id, $dati);
    } else {
        $dbo->insert('mostre', $dati);
    }

    header("Location: gestione_mostre.php?success=Azione completata");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<body>
    <?php require_once __DIR__ . '/../inc/sidebar.php'; ?>

    <div class="container-custom">
        <div class="dashboard-header">
            <h1>🎪 Gestione Mostre</h1>
        </div>

        <?php if ($ruolo === 'admin'): ?>
            <div class="card card-custom p-3 my-4">
                <h5><?= $edit_mostra ? 'Modifica Mostra' : 'Aggiungi Mostra' ?></h5>
                <form method="post">
                    <input type="hidden" name="mos_id" value="<?= $edit_mostra['mos_id'] ?? '' ?>">
                    <label>Nome:</label>
                    <input type="text" name="mos_nome" required class="form-control mb-2" placeholder="Inserisci nome mostra" value="<?= $edit_mostra['mos_nome'] ?? '' ?>">
                    <label>Data Inizio:</label>
                    <input type="date" name="mos_data_inizio" required class="form-control mb-2" value="<?= $edit_mostra['mos_data_inizio'] ?? '' ?>">
                    <label>Data Fine:</label>
                    <input type="date" name="mos_data_fine" class="form-control mb-3" value="<?= $edit_mostra['mos_data_fine'] ?? '' ?>">
                    <button type="submit" class="btn btn-success">💾 Salva</button>
                    <?php if ($edit_mostra): ?>
                        <a href="gestione_mostre.php" class="btn btn-secondary">❌ Annulla</a>
                    <?php endif; ?>
                </form>
            </div>
        <?php endif; ?>

        <!-- Ricerca -->
        <form method="GET" class="my-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cerca per titolo..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">🔍 Cerca</button>
            </div>
        </form>

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
                            <td><?= htmlspecialchars(Utils::raw2date($mostra['mos_data_inizio'])) ?></td>
                            <td><?= htmlspecialchars(Utils::raw2date($mostra['mos_data_fine'])) ?></td>
                            <?php if ($ruolo === 'admin'): ?>
                                <td>
                                    <a href="?azione=modifica&id=<?= $mostra['mos_id'] ?>" class="btn btn-warning btn-sm">✐</a>
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

    <?php require_once __DIR__ . '/../inc/footer.php'; ?>
</body>
</html>
