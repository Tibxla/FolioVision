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

// Récupérer les données envoyées via POST
$budget_id = $_POST['budget_id'] ?? null;
$main_category_id = $_POST['main_category_id'] ?? null;
$sub_category_id = $_POST['sub_category_id'] ?? null;
$budget_amount = $_POST['budget_amount'] ?? null;
$period = $_POST['period'] ?? null;
$start_month = $_POST['start_month'] ?? null;
$end_month = $_POST['end_month'] ?? null;
$accounts = $_POST['accounts'] ?? [];
$carry_over_under = isset($_POST['carry_over_under']) ? 1 : 0;
$carry_over_over = isset($_POST['carry_over_over']) ? 1 : 0;

// Valider les champs obligatoires
if (empty($budget_id) || empty($main_category_id) || empty($budget_amount) || empty($period) || empty($start_month)) {
    echo json_encode(['status' => 'error', 'message' => 'Champs obligatoires manquants']);
    exit;
}

// Déterminer la category_id (sous-catégorie si présente, sinon catégorie principale)
$category_id = !empty($sub_category_id) ? $sub_category_id : $main_category_id;

// Convertir les dates
$start_date = date('Y-m-01', strtotime($start_month));
$end_date = !empty($end_month) ? date('Y-m-t', strtotime($end_month)) : null;

try {
    // Mettre à jour le budget dans la table budgets
    $stmt = $conn->prepare("UPDATE budgets SET category_id = ?, budget_amount = ?, period = ?, start_date = ?, end_date = ?, carry_over_under = ?, carry_over_over = ? WHERE budget_id = ? AND user_id = ?");
    $stmt->execute([$category_id, $budget_amount, $period, $start_date, $end_date, $carry_over_under, $carry_over_over, $budget_id, $user_id]);

    // Supprimer les anciens comptes associés
    $stmt = $conn->prepare("DELETE FROM budget_accounts WHERE budget_id = ?");
    $stmt->execute([$budget_id]);

    // Ajouter les nouveaux comptes associés
    foreach ($accounts as $account_id) {
        $stmt = $conn->prepare("INSERT INTO budget_accounts (budget_id, account_id) VALUES (?, ?)");
        $stmt->execute([$budget_id, $account_id]);
    }

    // Renvoyer une réponse de succès
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    // En cas d'erreur, renvoyer un message d'erreur
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>