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

// Récupérer l'ID du budget depuis les paramètres GET
$budget_id = isset($_GET['budget_id']) ? intval($_GET['budget_id']) : null;

if (!$budget_id) {
    echo json_encode(['status' => 'error', 'message' => 'ID du budget manquant']);
    exit;
}

try {
    // Préparer la requête pour récupérer les comptes associés au budget
    $stmt = $conn->prepare("
        SELECT a.account_id, a.account_name, IF(ba.budget_id IS NOT NULL, 1, 0) as selected
        FROM accounts a
        LEFT JOIN budget_accounts ba ON a.account_id = ba.account_id AND ba.budget_id = ?
        WHERE a.user_id = ?
    ");
    $stmt->execute([$budget_id, $user_id]);

    // Récupérer tous les comptes avec un indicateur 'selected'
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Vérifier si des comptes sont trouvés
    if ($accounts) {
        echo json_encode(['status' => 'success', 'accounts' => $accounts]);
    } else {
        echo json_encode(['status' => 'success', 'accounts' => []]);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>