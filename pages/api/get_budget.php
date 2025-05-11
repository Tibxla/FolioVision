<?php
session_start();
include '../../config/config.php';
include '../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

$budget_id = $_GET['budget_id'] ?? null;
if (!$budget_id) {
    echo json_encode(['status' => 'error', 'message' => 'ID du budget manquant']);
    exit;
}

// Requête modifiée avec jointure pour récupérer parent_id
$stmt = $conn->prepare("
    SELECT b.*, c.parent_id
    FROM budgets b
    LEFT JOIN categories c ON b.category_id = c.category_id
    WHERE b.budget_id = ? AND b.user_id = ?
");
$stmt->execute([$budget_id, $_SESSION['user_id']]);
$budget = $stmt->fetch(PDO::FETCH_ASSOC);

if ($budget) {
    // Déterminer main_category_id et sub_category_id
    if ($budget['parent_id'] === null) {
        // Si parent_id est NULL, category_id est la catégorie principale
        $budget['main_category_id'] = $budget['category_id'];
        $budget['sub_category_id'] = null;
    } else {
        // Si parent_id est défini, category_id est une sous-catégorie
        $budget['main_category_id'] = $budget['parent_id'];
        $budget['sub_category_id'] = $budget['category_id'];
    }
    // Supprimer parent_id pour ne pas l’envoyer dans la réponse
    unset($budget['parent_id']);
    echo json_encode(['status' => 'success', 'budget' => $budget]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Budget non trouvé']);
}
?>