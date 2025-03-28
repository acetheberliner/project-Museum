<?php
require_once __DIR__ . '/inc/require.php';

session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

// Query per contare le opere per ogni mostra
$query = "
    SELECT m.mos_id, m.mos_nome, m.mos_data_inizio, m.mos_data_fine, COUNT(mo.ope_id) AS num_opere
    FROM mostre m
    LEFT JOIN mostre_opere mo ON m.mos_id = mo.mos_id
    GROUP BY m.mos_id
    ORDER BY m.mos_data_inizio DESC;
";

$dbo->query($query);
$report = $dbo->resultset();
?>

<?php require_once __DIR__ . '/inc/header.php'; ?>

<div class="container my-5">
    <h1 class="text-center mb-4">📊 Report Mostre e Opere</h1>

    <?php if (empty($report)): ?>
        <div class="alert alert-warning text-center">Nessuna mostra disponibile.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Nome Mostra</th>
                        <th>Data Inizio</th>
                        <th>Data Fine</th>
                        <th>Numero di Opere</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['mos_nome']) ?></td>
                            <td><?= date("d/m/Y", strtotime($r['mos_data_inizio'])) ?></td>
                            <td><?= date("d/m/Y", strtotime($r['mos_data_fine'])) ?></td>
                            <td>
                                <span class="badge <?= $r['num_opere'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $r['num_opere'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-center mt-4">
        <a href="dashboard.php" class="btn btn-info">Torna alla Dashboard</a>
    </div>
</div>

<?php require_once __DIR__ . '/inc/footer.php'; ?>
