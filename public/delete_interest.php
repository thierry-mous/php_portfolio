<?php
session_start();
require_once __DIR__ . '/../includes/cnx_bdd.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $interestId = $data['interestId'];

    if (empty($interestId)) {
        throw new Exception('Interest ID is required');
    }

    $userId = $_SESSION['user_id'];

    // Delete the interest from the user_interests table
    $stmt = $connexion->prepare("DELETE FROM user_interests WHERE user_id = :user_id AND interest_id = :interest_id");
    $stmt->execute(['user_id' => $userId, 'interest_id' => $interestId]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Une erreur est survenue: ' . $e->getMessage()]);
}