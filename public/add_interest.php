<?php
require_once __DIR__ . '/../includes/connected.php';
require_once __DIR__ . '/../includes/cnx_bdd.php';

header('Content-Type: application/json');

if (!$connected || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'error' => 'Unauthorized'
    ]);
    exit;
}

try {
    // Get the data from the request
    $data = json_decode(file_get_contents('php://input'), true);
    $interestId = $data['interestId'];
    $level = $data['level'];

    // Validate input
    if (empty($interestId) || empty($level)) {
        throw new Exception('Interest ID and level are required');
    }

    // Create a new PDO connection
    $connexion = new PDO($db_dsn, $db_user, $db_password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the interest already exists for the user
    $userId = $_SESSION['user_id']; // Assuming user_id is stored in session
    $stmt = $connexion->prepare("SELECT * FROM user_interests WHERE user_id = :user_id AND interest_id = :interest_id");
    $stmt->execute(['user_id' => $userId, 'interest_id' => $interestId]);
    $existingInterest = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingInterest) {
        // Update the existing interest
        $updateStmt = $connexion->prepare("UPDATE user_interests SET level = :level WHERE user_id = :user_id AND interest_id = :interest_id");
        $updateStmt->execute(['level' => $level, 'user_id' => $userId, 'interest_id' => $interestId]);
    } else {
        // Insert a new interest
        $insertStmt = $connexion->prepare("INSERT INTO user_interests (user_id, interest_id, level) VALUES (:user_id, :interest_id, :level)");
        $insertStmt->execute(['user_id' => $userId, 'interest_id' => $interestId, 'level' => $level]);
    }

    echo json_encode([
        'success' => true,
        'interestId' => $interestId,
        'level' => $level
    ]);

} catch (\Exception $e) {
    error_log("Error adding interest: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Une erreur est survenue: ' . $e->getMessage()
    ]);
}