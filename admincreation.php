<?php
require_once __DIR__ . '/config/Database.php';

$dbo = new Database();

$nome = "Admin";
$email = "admin@museo.com";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$ruolo = "admin";

$dbo->insert('utenti', [
    'ute_nome' => $nome,
    'ute_email' => $email,
    'ute_password' => $password,
    'ute_ruolo' => $ruolo
]);

echo "Admin creato con successo!";
?>
