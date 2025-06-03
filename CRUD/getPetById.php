<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Load configuration
$config = require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    // Create PDO connection using config
    $dsn = "mysql:host={$config['db']['host']};port={$config['db']['port']};dbname={$config['db']['dbname']};charset={$config['db']['charset']}";
    $pdo = new PDO($dsn, $config['db']['username'], $config['db']['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    if (!isset($_GET['id'])) {
        throw new Exception('Pet ID is required');
    }

    $id = intval($_GET['id']);
    
    $sql = "SELECT * FROM animals WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    
    $pet = $stmt->fetch();
    
    if (!$pet) {
        throw new Exception('Pet not found');
    }

    echo json_encode([
        'status' => 'success',
        'data' => $pet
    ]);

} catch (Exception $e) {
    http_response_code(500);  // Changed from 400 to 500 for server errors
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
