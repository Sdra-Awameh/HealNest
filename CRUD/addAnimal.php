<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

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
} catch (PDOException $e) {
    sendError('Database connection failed: ' . $e->getMessage(), 500);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method not allowed', 405);
    exit();
}

// Get and validate input data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    sendError('Invalid JSON data', 400);
    exit();
}

// Required fields validation
$requiredFields = ['animal_name', 'animal_type', 'age', 'gender', 'owner_name'];
foreach ($requiredFields as $field) {
    if (empty($data[$field])) {
        sendError("$field is required", 400);
        exit();
    }
}

try {
    // Create the animals table if it doesn't exist
    $createTableSQL = "CREATE TABLE IF NOT EXISTS animals (
        id INT PRIMARY KEY AUTO_INCREMENT,
        animal_name VARCHAR(100) NOT NULL,
        animal_type VARCHAR(50) NOT NULL,
        breed VARCHAR(100),
        color VARCHAR(50),
        age DECIMAL(4,1) NOT NULL,
        gender VARCHAR(10) NOT NULL,
        microchip VARCHAR(50),
        owner_name VARCHAR(100) NOT NULL,
        owner_phone VARCHAR(20),
        owner_email VARCHAR(100),
        check_date DATE,
        next_checkup DATE,
        medical_notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($createTableSQL);

    // Prepare the insert statement
    $stmt = $pdo->prepare("
        INSERT INTO animals (
            animal_name, animal_type, breed, color, age, gender,
            microchip, owner_name, owner_phone, owner_email,
            check_date, next_checkup, medical_notes
        ) VALUES (
            :animal_name, :animal_type, :breed, :color, :age, :gender,
            :microchip, :owner_name, :owner_phone, :owner_email,
            :check_date, :next_checkup, :medical_notes
        )
    ");

    // Execute with all possible fields
    $stmt->execute([
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
        'medical_notes' => $data['medical_notes'] ?? null
    ]);

    $animalId = $pdo->lastInsertId();

    sendSuccess([
        'message' => 'Pet record added successfully',
        'id' => $animalId,
        'pet' => [
            'name' => $data['animal_name'],
            'type' => $data['animal_type'],
            'owner' => $data['owner_name']
        ]
    ]);

} catch (PDOException $e) {
    sendError('Failed to add pet record: ' . $e->getMessage(), 500);
exit();
}