<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

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
    ]);    // Allow both POST and DELETE methods
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendError('Method not allowed', 405);
        exit();
    }

    // Get and validate input data
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        sendError('Pet ID is required', 400);
        exit();
    }

    $id = intval($data['id']);

    // First check if the pet exists
    $checkSql = "SELECT id FROM animals WHERE id = :id";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute(['id' => $id]);

    if (!$checkStmt->fetch()) {
        sendError('Pet not found', 404);
        exit();
    }

    // Delete the pet record
    $sql = "DELETE FROM animals WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    if ($stmt->rowCount() > 0) {
        sendSuccess([
            'message' => 'Pet record deleted successfully',
            'id' => $id
        ]);
    } else {
        sendError('Failed to delete pet record', 500);
    }

} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
    exit();
} catch (Exception $e) {
    sendError($e->getMessage(), 500);
    exit();
}