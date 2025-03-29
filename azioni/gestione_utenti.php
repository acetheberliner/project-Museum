<?php
require_once __DIR__ . '/../inc/require.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['user_ruolo'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

$page_title = 'Gestione Utenti';
require_once __DIR__ . '/../inc/head.php';

$limit = 5;
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $limit;

$total_utenti = count(Utente::getUtenti());
$total_pages = ceil($total_utenti / $limit);

$search = $_GET['search'] ?? '';
$where = '';
$bind = [];

if ($search) {
    $where = "AND (ute_nome LIKE :search OR ute_email LIKE :search)";
    $bind['search'] = "%$search%";
}

$utenti = Utente::getUtenti("$where LIMIT $limit OFFSET $offset", $bind);

// Elimina utente
if (isset($_GET['azione'], $_GET['id']) && $_GET['azione'] === 'elimina') {
    $dbo->delete('utenti', 'ute_id', $_GET['id']);
    header("Location: gestione_utenti.php?success=Utente eliminato");
    exit;
}

// Recupera utente per modifica
$edit_utente = null;
if (isset($_GET['azione'], $_GET['id']) && $_GET['azione'] === 'modifica') {
    $edit_utente = $dbo->find('utenti', 'ute_id', $_GET['id']);
}

// Inserimento o modifica
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['ute_id'] ?? null;
    $nome = $_POST['ute_nome'];
    $email = $_POST['ute_email'];
    $ruolo = $_POST['ute_ruolo'];

    if (!empty($id)) {
        $dbo->update('utenti', 'ute_id', $id, [
            'ute_nome' => $nome,
            'ute_email' => $email,
            'ute_ruolo' => $ruolo
        ]);
    } else {
        $password = password_hash($_POST['ute_password'], PASSWORD_DEFAULT);
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
<body>
<?php require_once __DIR__ . '/../inc/sidebar.php'; ?>

<div class="container-custom">
    <div class="dashboard-header">
        <h1>🔐 Gestione Utenti</h1>
    </div>

    <div class="card card-custom p-3 my-4">
        <h5><?= $edit_utente ? 'Modifica Utente' : 'Aggiungi Utente' ?></h5>
        <form method="post">
            <input type="hidden" name="ute_id" value="<?= $edit_utente['ute_id'] ?? '' ?>">
            <label>Nome:</label>
            <input type="text" name="ute_nome" required class="form-control mb-2" placeholder="Nome completo" value="<?= $edit_utente['ute_nome'] ?? '' ?>">
            <label>Email:</label>
            <input type="email" name="ute_email" required class="form-control mb-2" placeholder="Email" value="<?= $edit_utente['ute_email'] ?? '' ?>">

            <?php if (!$edit_utente): ?>
                <label>Password:</label>
                <input type="password" name="ute_password" required class="form-control mb-2" placeholder="Password">
            <?php endif; ?>

            <label>Ruolo:</label>
            <select name="ute_ruolo" class="form-control mb-3">
                <option value="user" <?= isset($edit_utente) && $edit_utente['ute_ruolo'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= isset($edit_utente) && $edit_utente['ute_ruolo'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>

            <button type="submit" class="btn btn-success">💾 Salva</button>
            <?php if ($edit_utente): ?>
                <a href="gestione_utenti.php" class="btn btn-secondary">❌ Annulla</a>
            <?php endif; ?>
        </form>
    </div>

    <form method="GET" class="my-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cerca per nome o email..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">🔍 Cerca</button>
        </div>
    </form>

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
                        <td><span class="badge <?= $utente['ute_ruolo'] === 'admin' ? 'bg-danger' : 'bg-primary' ?>"><?= $utente['ute_ruolo'] ?></span></td>
                        <td>
                            <a href="?azione=modifica&id=<?= $utente['ute_id'] ?>" class="btn btn-warning btn-sm">✐</a>
                            <a href="?azione=elimina&id=<?= $utente['ute_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Eliminare l\'utente?')">🗑️</a>
                        </td>
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
