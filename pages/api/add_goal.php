<?php
session_start();
include '../../config/config.php';
include '../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit;
}

$user_id = $_SESSION['user_id'];
$goal_name = $_POST['goal_name'] ?? '';
$target_amount = $_POST['target_amount'] ?? 0;
$currency = $_POST['currency'] ?? 'EUR';
$initial_amount = $_POST['initial_amount'] ?? 0;
$due_date = $_POST['due_date'] ?? null;
$comment = $_POST['comment'] ?? '';

// Vérifier si la date d'échéance est vide ou invalide
if (empty($due_date) || $due_date === '0000-00-00') {
    $due_date = null;
}

if (empty($goal_name) || $target_amount <= 0 || empty($currency)) {
    echo json_encode(['status' => 'error', 'message' => 'Données invalides']);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO goals (user_id, goal_name, target_amount, currency, current_amount, due_date, comment) 
                           VALUES (:user_id, :goal_name, :target_amount, :currency, :current_amount, :due_date, :comment)");
    $stmt->execute([
        'user_id' => $user_id,
        'goal_name' => $goal_name,
        'target_amount' => $target_amount,
        'currency' => $currency,
        'current_amount' => $initial_amount,
        'due_date' => $due_date,
        'comment' => $comment
    ]);
    echo json_encode(['status' => 'success', 'message' => 'Projet ajouté avec succès']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur de base de données : ' . $e->getMessage()]);
}
?>