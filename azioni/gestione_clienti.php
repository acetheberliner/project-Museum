<?php
require_once __DIR__ . '/../inc/require.php';

session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../access/login.php");
    exit;
}

$page_title = 'Gestione Clienti';
require_once __DIR__ . '/../inc/head.php';

$limit = 5;
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $limit;

$total_clienti = count(Cliente::getClienti());
$total_pages = ceil($total_clienti / $limit);

$ruolo = $_SESSION['user_ruolo'] ?? 'user';

$search = $_GET['search'] ?? '';
$where = '';
$bind = [];

if ($search) {
    $where = "AND (cli_nome LIKE :search OR cli_email LIKE :search)";
    $bind['search'] = "%$search%";
}

$clienti = Cliente::getClienti("$where LIMIT $limit OFFSET $offset", $bind);

if ($ruolo === 'admin' && isset($_GET['azione']) && $_GET['azione'] == 'elimina' && isset($_GET['id'])) {
    $dbo->delete('clienti', 'cli_id', $_GET['id']);
    header("Location: gestione_clienti.php?success=Cliente eliminato");
    exit;
}

$edit_cliente = null;
if ($ruolo === 'admin' && isset($_GET['azione']) && $_GET['azione'] == 'modifica' && isset($_GET['id'])) {
    $edit_cliente = $dbo->find('clienti', 'cli_id', $_GET['id']);
}

if ($ruolo === 'admin' && $_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = $_POST['cli_id'] ?? null;
    $nome = $_POST['cli_nome'];
    $email = $_POST['cli_email'];
    $telefono = $_POST['cli_telefono'];

    if (!empty($id_cliente)) {
        $dbo->update('clienti', 'cli_id', $id_cliente, [
            'cli_nome' => $nome,
            'cli_email' => $email,
            'cli_telefono' => $telefono
        ]);
    } else {
        $dbo->insert('clienti', [
            'cli_nome' => $nome,
            'cli_email' => $email,
            'cli_telefono' => $telefono
        ]);
    }

    header("Location: gestione_clienti.php?success=Azione completata");
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
            <h1>Gestione Clienti</h1>
        </div>

        <!-- Form Aggiunta/Modifica Cliente -->
        <?php if ($ruolo === 'admin'): ?>
            <div class="card card-custom p-3 my-4">
                <h5><?= $edit_cliente ? 'Modifica Cliente' : 'Aggiungi Cliente' ?></h5>
                <form method="post">
                    <input type="hidden" name="cli_id" value="<?= $edit_cliente['cli_id'] ?? '' ?>">
                    <label>Nome:</label>
                    <input type="text" name="cli_nome" required class="form-control mb-2" placeholder="Inserisci nome e cognome" value="<?= $edit_cliente['cli_nome'] ?? '' ?>">
                    <label>Email:</label>
                    <input type="email" name="cli_email" required class="form-control mb-2" placeholder="Inserisci email" value="<?= $edit_cliente['cli_email'] ?? '' ?>">
                    <label>Telefono:</label>
                    <input type="text" name="cli_telefono" class="form-control mb-3" placeholder="Inserisci cellulare" value="<?= $edit_cliente['cli_telefono'] ?? '' ?>">
                    <button type="submit" class="btn btn-success">💾 Salva</button>
                    <?php if ($edit_cliente): ?>
                        <a href="gestione_clienti.php" class="btn btn-secondary">❌ Annulla</a>
                    <?php endif; ?>
                </form>
            </div>
        <?php endif; ?>

        <!-- Barra di ricerca -->
        <form method="GET" class="my-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cerca per nome o email..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">🔍 Cerca</button>
            </div>
        </form>

        <!-- Lista clienti -->
        <div class="card card-custom p-3">
            <h2 class="mb-3">Lista Clienti</h2>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefono</th>
                        <?php if ($ruolo === 'admin'): ?><th>Azioni</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clienti as $cliente): ?>
                        <tr>
                            <td><?= $cliente['cli_id'] ?></td>
                            <td><?= htmlspecialchars($cliente['cli_nome']) ?></td>
                            <td><?= htmlspecialchars($cliente['cli_email']) ?></td>
                            <td><?= htmlspecialchars($cliente['cli_telefono']) ?></td>
                            <?php if ($ruolo === 'admin'): ?>
                                <td>
                                    <a href="?azione=modifica&id=<?= $cliente['cli_id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                                    <a href="?azione=elimina&id=<?= $cliente['cli_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Eliminare il cliente?')">🗑️</a>
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

    <!-- Footer -->
    <?php require_once __DIR__ . '/../inc/footer.php'; ?>
</body>
</html>
