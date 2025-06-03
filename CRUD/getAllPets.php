<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Load configuration
$config = require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Only GET method is allowed',
    ]);
    exit;
}
    // Create PDO connection using config
    $dsn = "mysql:host={$config['db']['host']};port={$config['db']['port']};dbname={$config['db']['dbname']};charset={$config['db']['charset']}";
    $pdo = new PDO($dsn, $config['db']['username'], $config['db']['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    $sql = "SELECT * FROM animals ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $pets = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Format dates for the frontend
        if ($row['check_date']) {
            $row['check_date'] = date('Y-m-d', strtotime($row['check_date']));
        }
        if ($row['next_checkup']) {
            $row['next_checkup'] = date('Y-m-d', strtotime($row['next_checkup']));
        }
        $pets[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'data' => $pets
    ]);

} catch (Exception $e) {
    http_response_code(500); 
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()]);
}
?>