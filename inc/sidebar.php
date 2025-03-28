<?php
if (!isset($_SESSION)) session_start();

$user_id = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['user_nome'] ?? '';
$user_role = $_SESSION['user_ruolo'] ?? 'user';

$in_azioni = basename(getcwd()) === 'azioni';
$base = $in_azioni ? '../' : '';
?>

<style>
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

.sidebar a:hover,
.sidebar a.active {
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

</style>

<div class="sidebar">
    <div class="user-info border-0 text-center">
        <!-- immagine avatar generata dinamicamente usando l'id utente come parametro per ottenere un'immagine unica -->
        <img src="https://i.pravatar.cc/100?u=<?= $user_id ?>" alt="Avatar" style="width:90px;height:90px;border-radius:50%;border:3px solid #00b4d8;margin-bottom:10px;">

        <!-- Protegge da attacchi XSS -->
        <h5><?= htmlspecialchars($user_name) ?></h5>
        
        <!-- Come capitalize() rende la prima lettera maiuscola -->
        <p><?= ucfirst($user_role) ?></p>
    </div>
    <?php
    $links = [
        ['Dashboard', 'dashboard.php', 'bi-house-door'],
        ['Clienti', 'azioni/gestione_clienti.php', 'bi-person'],
        ['Opere', 'azioni/gestione_opere.php', 'bi-palette'],
        ['Mostre', 'azioni/gestione_mostre.php', 'bi-building'],
    ];

    foreach ($links as [$label, $href, $icon]) {
        echo "<a href='{$base}{$href}'><i class='bi {$icon}'></i> {$label}</a>";
    }

    if ($user_role === 'admin') {
        echo "<a href='{$base}azioni/gestione_utenti.php'><i class='bi bi-key'></i> Utenti</a>";
    }
    ?>
    <a href="<?= $base ?>access/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>
