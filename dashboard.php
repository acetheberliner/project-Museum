<?php
require_once __DIR__ . '/inc/require.php';
require_once __DIR__ . '/inc/head.php';

session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: access/login.php");
    exit;
}

$ruolo = $_SESSION['user_ruolo'] ?? 'user';

// Conteggi per statistiche rapide
$num_clienti = count(Cliente::getClienti());
$num_opere = count(Opera::getOpere());
$num_mostre = count(Mostra::getMostre());
$num_utenti = count(Utente::getUtenti());

$cards = [
    ['label' => 'Clienti', 'icon' => 'bi-people', 'count' => $num_clienti],
    ['label' => 'Opere', 'icon' => 'bi-image', 'count' => $num_opere],
    ['label' => 'Mostre', 'icon' => 'bi-bank', 'count' => $num_mostre],
];

if ($ruolo === 'admin') {
    $cards[] = ['label' => 'Utenti', 'icon' => 'bi-person-check', 'count' => $num_utenti];
}
?>

<!DOCTYPE html>
<html lang="it">
<body>

    <!-- Wrapper -->
    <div class="wrapper">

        <!-- Sidebar -->
        <?php require_once __DIR__ . '/inc/sidebar.php'; ?>

        <!-- Contenuto -->
        <div class="content">
            <div class="dashboard-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h1>Benvenuto, <span class="text-info"><?= htmlspecialchars($_SESSION['user_nome']) ?></span> 👋</h1>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="azioni/gestione_opere.php" class="btn btn-modern">+ Aggiungi Nuova Opera</a>
                    <a href="apidoc.php" class="btn btn-outline-primary"><i class="bi bi-journal-bookmark"></i> API DOC</a>
                </div>
            </div>

            <!-- Cards -->
            <div class="row mt-4 g-4">
                <?php foreach ($cards as $card): ?>
                    <div class="col-md-3">
                        <div class="dashboard-card">
                            <i class="bi <?= $card['icon'] ?>"></i>
                            <h4><?= $card['label'] ?></h4>
                            <p class="fs-3"><?= $card['count'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Web Services -->
            <div class="mt-5">
                <h3 class="mb-4">🧪 Web Services</h3>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="dashboard-card" onclick="location.href='webservice/wsMostra.php'" style="cursor:pointer;">
                            <i class="bi bi-binoculars"></i>
                            <h5>Tracking Mostra</h5>
                            <p>Visualizza i dettagli e le opere di una mostra inserendo il suo ID.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-card" onclick="location.href='webservice/wsCliente.php'" style="cursor:pointer;">
                            <i class="bi bi-telephone"></i>
                            <h5>Aggiorna Cliente</h5>
                            <p>Modifica il numero di telefono di un cliente esistente.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php require_once __DIR__ . '/inc/footer.php'; ?>
    </div>
</body>
</html>
