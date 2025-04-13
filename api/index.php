<?php
require_once(__DIR__ . '/../config/Database.php');
$dbo = new Database();

define('API_KEY', 'b4st0I5868HdCLAdeotkrSPGdeT1Df9ixpeQpWgD');

// Headers + autenticazione
$headers = getallheaders();
if (!isset($headers['APIKEY']) || $headers['APIKEY'] !== API_KEY) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['errore' => 'API-KEY non corretta']);
    return;
}

// Segmentazione URI e metodo
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_GET['uri'] ?? '';
$uriParts = explode('/', trim($uri, '/'));

$jsonBody = file_get_contents('php://input');
$data = json_decode($jsonBody, true);

try {
    // Routing API
    switch ($uriParts[0]) {

        case 'mostre':
            if ($method === 'GET' && isset($uriParts[1]) && $uriParts[1] === 'attive') {
                $oggi = date('Y-m-d');
                $dbo->query("
                    SELECT m.mos_id, m.mos_nome, m.mos_data_inizio, m.mos_data_fine, COUNT(mo.ope_id) AS numero_opere
                    FROM mostre m
                    LEFT JOIN mostre_opere mo ON m.mos_id = mo.mos_id
                    WHERE m.mos_data_inizio <= :oggi AND m.mos_data_fine >= :oggi
                    GROUP BY m.mos_id
                ");
                $dbo->bind(':oggi', $oggi);
                echo json_encode(['oggi' => $oggi, 'result' => $dbo->resultset()]);
                return;
            }

            if ($method === 'GET' && !isset($uriParts[1])) {
                $dbo->query("SELECT * FROM mostre ORDER BY mos_data_inizio DESC");
                echo json_encode($dbo->resultset());
                return;
            }
            break;

        case 'mostra':
            if ($method === 'GET' && isset($uriParts[1])) {
                $id = (int)$uriParts[1];
                $dbo->query("SELECT mos_nome, mos_data_inizio, mos_data_fine FROM mostre WHERE mos_id = :id");
                $dbo->bind(':id', $id);
                $mostra = $dbo->single();

                if (!$mostra) {
                    http_response_code(404);
                    echo json_encode(['errore' => 'Mostra non trovata']);
                    return;
                }

                $dbo->query("
                    SELECT ope_titolo, ope_autore, ope_anno
                    FROM opere o
                    JOIN mostre_opere mo ON o.ope_id = mo.ope_id
                    WHERE mo.mos_id = :id
                ");
                $dbo->bind(':id', $id);
                $opere = $dbo->resultset();

                echo json_encode(['mostra' => $mostra, 'opere' => $opere]);
                return;
            }
            break;

        case 'opere':
            if ($method === 'GET' && isset($uriParts[1])) {
                if ($uriParts[1] === 'random') {
                    $dbo->query("SELECT * FROM opere ORDER BY RAND() LIMIT 1");
                    echo json_encode($dbo->single());
                    return;
                }

                if ($uriParts[1] === 'recenti') {
                    $dbo->query("SELECT * FROM opere ORDER BY ope_id DESC LIMIT 5");
                    echo json_encode($dbo->resultset());
                    return;
                }

                if ($uriParts[1] === 'autori') {
                    $dbo->query("
                        SELECT ope_autore, COUNT(*) as numero_opere
                        FROM opere
                        GROUP BY ope_autore
                        ORDER BY numero_opere DESC
                    ");
                    echo json_encode($dbo->resultset());
                    return;
                }
            }
            break;

        case 'clienti':
            if ($method === 'GET') {
                $dbo->query("SELECT cli_id, cli_nome, cli_email, cli_telefono FROM clienti ORDER BY cli_nome ASC");
                echo json_encode($dbo->resultset());
                return;
            }

            if ($method === 'PUT' && isset($uriParts[1])) {
                $id = (int)$uriParts[1];
                if (!isset($data['cli_telefono'])) {
                    http_response_code(400);
                    echo json_encode(['errore' => 'Telefono mancante']);
                    return;
                }
        
                $query = "UPDATE clienti SET cli_telefono = :tel WHERE cli_id = :id";
                $dbo->query($query);
                $dbo->bind(':tel', $data['cli_telefono']);
                $dbo->bind(':id', $id);
                $success = $dbo->execute();
        
                echo json_encode([
                    'esito' => $success ? 'Telefono aggiornato con successo' : 'Errore durante l\'aggiornamento'
                ]);
                return;
            }
            break;

        case 'utenti':
            if ($method === 'GET') {
                session_start();
                if (!isset($_SESSION['user_ruolo']) || $_SESSION['user_ruolo'] !== 'admin') {
                    http_response_code(403);
                    echo json_encode(['errore' => 'Accesso riservato agli admin']);
                    return;
                }
                $dbo->query("SELECT ute_id, ute_nome, ute_email, ute_ruolo FROM utenti ORDER BY ute_nome ASC");
                echo json_encode($dbo->resultset());
                return;
            }
            break;

        default:
            http_response_code(404);
            echo json_encode(['errore' => 'Endpoint non trovato']);
            return;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['errore' => $e->getMessage()]);
}
