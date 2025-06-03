<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
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
    ]);

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        throw new Exception('Pet ID is required');
    }    $sql = "UPDATE animals SET 
            animal_name = :animal_name, 
            animal_type = :animal_type, 
            breed = :breed, 
            color = :color, 
            age = :age, 
            gender = :gender, 
            microchip = :microchip, 
            owner_name = :owner_name, 
            owner_phone = :owner_phone, 
            owner_email = :owner_email, 
            check_date = :check_date, 
            next_checkup = :next_checkup, 
            medical_notes = :medical_notes 
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    
    $params = [
        'animal_name' => $data['animal_name'],
        'animal_type' => $data['animal_type'],
        'breed' => $data['breed'] ?? null,
        'color' => $data['color'] ?? null,
        'age' => $data['age'],
        'gender' => $data['gender'],
        'microchip' => $data['microchip'] ?? null,
        'owner_name' => $data['owner_name'],
        'owner_phone' => $data['owner_phone'] ?? null,
        'owner_email' => $data['owner_email'] ?? null,
        'check_date' => $data['check_date'] ?? null,
        'next_checkup' => $data['next_checkup'] ?? null,
        'medical_notes' => $data['medical_notes'] ?? null,
        'id' => $data['id']
    ];    // First check if the pet exists
    $checkSql = "SELECT id FROM animals WHERE id = :id";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute(['id' => $data['id']]);

    if (!$checkStmt->fetch()) {
        sendError('Pet not found', 404);
        exit();
    }

    // Execute the update
    if ($stmt->execute($params)) {
        sendSuccess([
            'message' => 'Pet record updated successfully',
            'id' => $data['id']
        ]);
    } else {
        sendError('Error updating pet record', 500);
    }

} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
    exit();
} catch (Exception $e) {
    sendError($e->getMessage(), 500);
exit();
}
?>