<?php
require_once __DIR__ . '/../inc/require.php';
require_once __DIR__ . '/../classi/Utils.php';

session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../access/login.php");
    exit;
}

$page_title = 'Gestione Opere';
require_once __DIR__ . '/../inc/head.php';

$limit = 5;
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $limit;

$total_opere = count(Opera::getOpere());
$total_pages = ceil($total_opere / $limit);

$ruolo = $_SESSION['user_ruolo'] ?? 'user';

$search = $_GET['search'] ?? '';
$where = '';
$bind = [];

if ($search) {
    $where = "AND (ope_titolo LIKE :search OR ope_autore LIKE :search)";
    $bind['search'] = "%$search%";
}

$opere = Opera::getOpere("$where LIMIT $limit OFFSET $offset", $bind);

// Eliminazione
if ($ruolo === 'admin' && $_GET['azione'] === 'elimina' && isset($_GET['id'])) {
    $dbo->delete('opere', 'ope_id', $_GET['id']);
    header("Location: gestione_opere.php?success=Opera eliminata");
    exit;
}

// Recupero per modifica
$edit_opera = null;
if ($ruolo === 'admin' && $_GET['azione'] === 'modifica' && isset($_GET['id'])) {
    $edit_opera = $dbo->find('opere', 'ope_id', $_GET['id']);
}

// Salvataggio
if ($ruolo === 'admin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['ope_id'] ?? null;
    $data = [
        'ope_titolo' => $_POST['ope_titolo'],
        'ope_autore' => $_POST['ope_autore'],
        'ope_anno'   => $_POST['ope_anno']
    ];

    if ($id) {
        $dbo->update('opere', 'ope_id', $id, $data);
    } else {
        $dbo->insert('opere', $data);
    }

    header("Location: gestione_opere.php?success=Azione completata");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<body>
    <?php require_once __DIR__ . '/../inc/sidebar.php'; ?>

    <div class="container-custom">
        <div class="dashboard-header">
            <h1>🖼️ Gestione Opere</h1>
        </div>

        <?php if ($ruolo === 'admin'): ?>
            <div class="card card-custom p-3 my-4">
                <h5><?= $edit_opera ? 'Modifica Opera' : 'Aggiungi Opera' ?></h5>
                <form method="post">
                    <input type="hidden" name="ope_id" value="<?= $edit_opera['ope_id'] ?? '' ?>">
                    <label>Titolo:</label>
                    <input type="text" name="ope_titolo" required class="form-control mb-2" value="<?= $edit_opera['ope_titolo'] ?? '' ?>">
                    <label>Autore:</label>
                    <input type="text" name="ope_autore" required class="form-control mb-2" value="<?= $edit_opera['ope_autore'] ?? '' ?>">
                    <label>Anno:</label>
                    <input type="number" name="ope_anno" class="form-control mb-3" value="<?= $edit_opera['ope_anno'] ?? '' ?>">
                    <button type="submit" class="btn btn-success">💾 Salva</button>
                    <?php if ($edit_opera): ?>
                        <a href="gestione_opere.php" class="btn btn-secondary">❌ Annulla</a>
                    <?php endif; ?>
                </form>
            </div>
        <?php endif; ?>

        <form method="GET" class="my-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cerca per titolo o autore..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">🔍 Cerca</button>
            </div>
        </form>

        <div class="card card-custom p-3">
            <h2>Lista Opere</h2>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th><th>Titolo</th><th>Autore</th><th>Anno</th>
                        <?php if ($ruolo === 'admin') echo "<th>Azioni</th>"; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($opere as $opera): ?>
                        <tr>
                            <td><?= $opera['ope_id'] ?></td>
                            <td><?= htmlspecialchars($opera['ope_titolo']) ?></td>
                            <td><?= htmlspecialchars($opera['ope_autore']) ?></td>
                            <td><?= htmlspecialchars($opera['ope_anno']) ?></td>
                            <?php if ($ruolo === 'admin'): ?>
                                <td>
                                    <a href="?azione=modifica&id=<?= $opera['ope_id'] ?>" class="btn btn-warning btn-sm">✐</a>
                                    <a href="?azione=elimina&id=<?= $opera['ope_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Eliminare l\'opera?')">🗑️</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <nav>
                <ul class="pagination mb-0">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
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
