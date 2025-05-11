<?php
// Désactiver l'affichage des erreurs pour éviter toute sortie parasite
ini_set('display_errors', 0);
error_reporting(0);

// Démarrer la session
session_start();

// Inclure les fichiers de configuration
require_once '../../config/config.php';
require_once '../../config/database.php';

// Définir le type de contenu comme JSON
header('Content-Type: application/json');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Préparer la requête pour récupérer les budgets
    $stmt = $conn->prepare("
        SELECT b.*, 
               IF(c.parent_id IS NULL, c.name, p.name) AS main_category_name,
               IF(c.parent_id IS NOT NULL, c.name, NULL) AS sub_category_name,
               GROUP_CONCAT(a.account_name) AS accounts
        FROM budgets b
        JOIN categories c ON b.category_id = c.category_id
        LEFT JOIN categories p ON c.parent_id = p.category_id
        LEFT JOIN budget_accounts ba ON b.budget_id = ba.budget_id
        LEFT JOIN accounts a ON ba.account_id = a.account_id
        WHERE b.user_id = ?
        GROUP BY b.budget_id
    ");
    $stmt->execute([$user_id]);
    $budgets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Traiter chaque budget pour calculer le montant utilisé
    foreach ($budgets as &$budget) {
        $start_date = $budget['start_date'] ?? date('Y-m-01');
        $end_date = $budget['end_date'] ?? date('Y-m-t');
        $category_id = $budget['category_id'];

        // Vérifier si la catégorie est une catégorie principale (parent_id IS NULL)
        $stmt = $conn->prepare("SELECT parent_id FROM categories WHERE category_id = ?");
        $stmt->execute([$category_id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($category['parent_id'] === null) {
            // Si c'est une catégorie principale, inclure toutes les sous-catégories
            $stmt = $conn->prepare("
                SELECT SUM(t.amount) AS used_amount
                FROM transactions t
                JOIN budget_accounts ba ON t.account_id = ba.account_id
                JOIN categories c ON t.category_id = c.category_id
                WHERE ba.budget_id = ? AND (c.category_id = ? OR c.parent_id = ?) AND t.transaction_date BETWEEN ? AND ? AND t.type = 'debit'
            ");
            $stmt->execute([$budget['budget_id'], $category_id, $category_id, $start_date, $end_date]);
        } else {
            // Si c'est une sous-catégorie, utiliser la requête actuelle
            $stmt = $conn->prepare("
                SELECT SUM(t.amount) AS used_amount
                FROM transactions t
                JOIN budget_accounts ba ON t.account_id = ba.account_id
                WHERE ba.budget_id = ? AND t.category_id = ? AND t.transaction_date BETWEEN ? AND ? AND t.type = 'debit'
            ");
            $stmt->execute([$budget['budget_id'], $category_id, $start_date, $end_date]);
        }

        $used = $stmt->fetch(PDO::FETCH_ASSOC);
        $budget['used_amount'] = $used['used_amount'] ?? 0;
    }
    unset($budget); // Libérer la référence

    // Renvoyer les budgets en JSON avec la clé 'budgets'
    echo json_encode(['status' => 'success', 'budgets' => $budgets]);
} catch (Exception $e) {
    // En cas d'erreur, renvoyer un JSON avec le message d'erreur
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>