<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', 'On');

require_once __DIR__ . '/../config/Database.php';
$dbo = new Database();
define('API_KEY', 'b4st0I5868HdCLAdeotkrSPGdeT1Df9ixpeQpWgD');

header('Content-Type: application/json');

// 🔐 Autenticazione
function autenticazione() {
    $headers = getallheaders();
    if (empty($headers['APIKEY']) || $headers['APIKEY'] !== API_KEY) {
        http_response_code(403);
        echo json_encode(['errore' => 'Accesso negato.']);
        return false;
    }
    return true;
}

if (!autenticazione()) return;

// ✏️ Estrazione URI e metodo
$uri = $_REQUEST['uri'] ?? '';
$uriParts = explode('/', trim($uri, '/'));
$method = $_SERVER['REQUEST_METHOD'];
$jsonBody = file_get_contents('php://input');
$data = json_decode($jsonBody, true);

// 🧠 Gestione API
try {
    switch ($uriParts[0]) {

        // ✅ API 1: GET /mostre/attive
        case 'mostre':
            if ($method === 'GET' && isset($uriParts[1]) && $uriParts[1] === 'attive') {
                $oggi = date('Y-m-d');
                $query = "
                    SELECT m.mos_id, m.mos_nome, m.mos_data_inizio, m.mos_data_fine, COUNT(mo.ope_id) AS numero_opere
                    FROM mostre m
                    LEFT JOIN mostre_opere mo ON m.mos_id = mo.mos_id
                    WHERE m.mos_data_inizio <= :oggi AND m.mos_data_fine >= :oggi
                    GROUP BY m.mos_id
                    ORDER BY m.mos_data_inizio DESC
                ";
                $dbo->query($query);
                $dbo->bind(':oggi', $oggi);
                echo json_encode([
                    'oggi' => $oggi,
                    'result' => $dbo->resultset()
                ]);
                exit;
            }

            // ✅ API 5: GET /mostre
            if ($method === 'GET' && !isset($uriParts[1])) {
                $dbo->query("SELECT * FROM mostre ORDER BY mos_data_inizio DESC");
                echo json_encode($dbo->resultset());
                exit;
            }

            break;

        // ✅ API 2: GET /mostra/{id}
        case 'mostra':
            if ($method === 'GET' && isset($uriParts[1])) {
                $id = (int)$uriParts[1];
                $dbo->query("SELECT mos_nome, mos_data_inizio, mos_data_fine FROM mostre WHERE mos_id = :id");
                $dbo->bind(':id', $id);
                $mostra = $dbo->single();

                if (!$mostra) {
                    http_response_code(404);
                    echo json_encode(['errore' => 'Mostra non trovata.']);
                    exit;
                }

                $dbo->query("
                    SELECT ope_titolo, ope_autore, ope_anno 
                    FROM opere o
                    JOIN mostre_opere mo ON o.ope_id = mo.ope_id
                    WHERE mo.mos_id = :id
                ");
                $dbo->bind(':id', $id);
                $opere = $dbo->resultset();

                echo json_encode([
                    'mostra' => $mostra,
                    'opere' => $opere
                ]);
                exit;
            }
            break;

        // ✅ API 3: GET /opere/random
        case 'opere':
            if ($method === 'GET' && isset($uriParts[1]) && $uriParts[1] === 'random') {
                $dbo->query("SELECT * FROM opere ORDER BY RAND() LIMIT 1");
                echo json_encode($dbo->single());
                exit;
            }

            // ✅ API 4: GET /opere/recenti
            if ($method === 'GET' && isset($uriParts[1]) && $uriParts[1] === 'recenti') {
                $dbo->query("SELECT * FROM opere ORDER BY ope_id DESC LIMIT 5");
                echo json_encode($dbo->resultset());
                exit;
            }

            // ✅ API 6: GET /opere/autori
            if ($method === 'GET' && isset($uriParts[1]) && $uriParts[1] === 'autori') {
                $dbo->query("
                    SELECT ope_autore, COUNT(*) as numero_opere
                    FROM opere
                    GROUP BY ope_autore
                    ORDER BY numero_opere DESC
                ");
                echo json_encode($dbo->resultset());
                exit;
            }

            break;
        
        // API 3: GET /clienti
        case 'clienti':
            if ($method === 'GET') {
                $dbo->query("SELECT cli_id, cli_nome, cli_email, cli_telefono FROM clienti ORDER BY cli_nome ASC");
                echo json_encode($dbo->resultset());
                exit;
            }
            break;

        // API 4: GET /utenti (solo admin)
        case 'utenti':
            if ($method === 'GET') {
                session_start();
                if (!isset($_SESSION['user_ruolo']) || $_SESSION['user_ruolo'] !== 'admin') {
                    http_response_code(403);
                    echo json_encode(['errore' => 'Accesso riservato agli admin.']);
                    exit;
                }

                $dbo->query("SELECT ute_id, ute_nome, ute_email, ute_ruolo FROM utenti ORDER BY ute_nome ASC");
                echo json_encode($dbo->resultset());
                exit;
            }
            break;

        default:
            http_response_code(404);
            echo json_encode(['errore' => 'Endpoint non trovato']);
            exit;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['errore' => $e->getMessage()]);
}
