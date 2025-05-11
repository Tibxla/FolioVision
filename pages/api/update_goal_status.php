<?php
session_start();
include '../../config/config.php';
include '../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit;
}

$user_id = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);
$goal_id = $input['goal_id'] ?? '';
$new_status = $input['status'] ?? '';

if (empty($goal_id) || empty($new_status)) {
    echo json_encode(['status' => 'error', 'message' => 'Données manquantes']);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE goals SET status = :status WHERE goal_id = :goal_id AND user_id = :user_id");
    $stmt->execute([
        'status' => $new_status,
        'goal_id' => $goal_id,
        'user_id' => $user_id
    ]);
    echo json_encode(['status' => 'success', 'message' => 'Statut mis à jour avec succès']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur de base de données : ' . $e->getMessage()]);
}
?>