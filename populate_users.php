<?php
require_once __DIR__ . '/config/Database.php';

$dbo = new Database();

$utenti = [
    ['Curatore1', 'curatore1@museo.com', 'curatore123', 'user'],
    ['Curatore2', 'curatore2@museo.com', 'curatore123', 'user'],
    ['Responsabile Mostre', 'responsabile@museo.com', 'mostre123', 'user'],
    ['Assistente', 'assistente@museo.com', 'assistente123', 'user']
];

foreach ($utenti as $utente) {
    $nome = $utente[0];
    $email = $utente[1];
    $password = password_hash($utente[2], PASSWORD_DEFAULT); // Hash password
    $ruolo = $utente[3];

    try {
        $query = "INSERT INTO utenti (ute_nome, ute_email, ute_password, ute_ruolo) 
                  VALUES (:nome, :email, :password, :ruolo)";
        $dbo->query($query);
        $dbo->bind(':nome', $nome);
        $dbo->bind(':email', $email);
        $dbo->bind(':password', $password);
        $dbo->bind(':ruolo', $ruolo);

        $result = $dbo->execute();

        if ($result) {
            echo "✅ Utente $nome inserito con successo!<br>";
        } else {
            echo "❌ Errore nell'inserimento di $nome!<br>";
        }
    } catch (PDOException $e) {
        echo "❌ Errore SQL: " . $e->getMessage() . "<br>";
    }
}
?>
