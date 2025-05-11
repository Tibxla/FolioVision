<?php
session_start();
include '../../config/config.php';
include '../../config/database.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les données JSON envoyées par la requête fetch
$data = json_decode(file_get_contents('php://input'), true);
$budget_id = $data['budget_id'] ?? null;

if (!$budget_id) {
    echo json_encode(['status' => 'error', 'message' => 'ID du budget manquant']);
    exit;
}

// Vérifier que le budget appartient à l'utilisateur
$stmt = $conn->prepare("SELECT user_id FROM budgets WHERE budget_id = ?");
$stmt->execute([$budget_id]);
$budget = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$budget || $budget['user_id'] != $user_id) {
    echo json_encode(['status' => 'error', 'message' => 'Budget non trouvé ou non autorisé']);
    exit;
}

// Supprimer les associations dans budget_accounts
$stmt = $conn->prepare("DELETE FROM budget_accounts WHERE budget_id = ?");
$stmt->execute([$budget_id]);

// Supprimer le budget
$stmt = $conn->prepare("DELETE FROM budgets WHERE budget_id = ?");
if ($stmt->execute([$budget_id])) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la suppression']);
}

?>