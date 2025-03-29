<?php
require_once __DIR__ . '/../config/Database.php';

$dbo = new Database();

// elenco utenti da inserire
$utenti = [
    ['Admin', 'admin@museo.com', 'admin123', 'admin'],
    ['Curatore1', 'curatore1@museo.com', 'curatore123', 'user'],
    ['Curatore2', 'curatore2@museo.com', 'curatore123', 'user'],
    ['Responsabile Mostre', 'responsabile@museo.com', 'mostre123', 'user'],
    ['Assistente', 'assistente@museo.com', 'assistente123', 'user']
];

foreach ($utenti as [$nome, $email, $plainPassword, $ruolo]) {
    // verifica se esiste già l'utente
    $existing = $dbo->find('utenti', 'ute_email', $email);

    if ($existing) {
        echo "⚠️ Utente <strong>$email</strong> già presente, salto...<br>";
        continue;
    }

    $hashed = password_hash($plainPassword, PASSWORD_DEFAULT); // hash della password per la sicurezza nel db

    try {
        $dbo->insert('utenti', [
            'ute_nome'     => $nome,
            'ute_email'    => $email,
            'ute_password' => $hashed,
            'ute_ruolo'    => $ruolo
        ]);

        echo "✅ Utente <strong>$nome</strong> ($email) inserito con successo.<br>";
    } catch (PDOException $e) {
        echo "❌ Errore con $email: " . $e->getMessage() . "<br>";
    }
}
