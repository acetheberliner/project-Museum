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
    <!-- Footer -->
    <?php require_once __DIR__ . '/../inc/footer.php'; ?>
</body>
</html>
