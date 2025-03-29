<?php
require_once __DIR__ . '/../inc/require.php';

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $utente = $dbo->find('utenti', 'ute_email', $email);

    if ($utente && password_verify($password, $utente['ute_password'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $utente['ute_id'];
        $_SESSION['user_nome'] = $utente['ute_nome'];
        $_SESSION['user_ruolo'] = $utente['ute_ruolo'];
        header("Location: ../dashboard.php");
        exit;
    } else {
        $errore = "Email o password errati!";
    }
}

$page_title = 'Login';
require_once __DIR__ . '/../inc/head.php';
?>

<!DOCTYPE html>
<html lang="it">
<body style="font-family: 'Poppins', sans-serif; background: linear-gradient(rgba(0, 0, 0, 0.37), rgba(0, 0, 0, 0.36)), url('https://images.unsplash.com/photo-1554907984-15263bfd63bd?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') center/cover no-repeat; color: #333; height: 100vh; display: flex; align-items: center; justify-content: center;">

    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-5 col-md-8">
                <div class="login-container p-4 rounded" style="background: white; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                    <h2 class="text-center mb-4">🔐 Accedi</h2>
                    
                    <?php if (isset($errore)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($errore) ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Accedi</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block ml-4">
                <img src="https://i.ibb.co/B564gLhJ/Pngtree-man-relaxing-in-art-gallery-6286768.png" alt="" style="width: 90%;">
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
