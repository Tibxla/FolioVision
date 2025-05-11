<?php
// Démarre la session PHP pour gérer les données de l'utilisateur connecté
session_start();

// Inclut les fichiers de configuration et de connexion à la base de données
include '../../config/config.php';
include '../../config/database.php';

// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

// Récupère l'identifiant de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Récupère les paramètres GET
$account_id = $_GET['account_id'] ?? null;
$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;

// Vérifie si l'ID du compte est fourni
if (empty($account_id)) {
    echo json_encode(['status' => 'error', 'message' => 'ID du compte manquant']);
    exit;
}

// Initialise la requête SQL de base
$query = "
    SELECT t.*, a.currency,
           IF(c.parent_id IS NULL, c.name, p.name) AS category_name,
           IF(c.parent_id IS NOT NULL, c.name, NULL) AS sub_category_name
    FROM transactions t
    JOIN accounts a ON t.account_id = a.account_id
    LEFT JOIN categories c ON t.category_id = c.category_id
    LEFT JOIN categories p ON c.parent_id = p.category_id
    WHERE t.account_id = ? AND a.user_id = ?
";

// Initialise le tableau des paramètres
$params = [$account_id, $user_id];

// Ajoute le filtre de date si fourni
if (!empty($start_date) && !empty($end_date)) {
    $query .= " AND t.transaction_date BETWEEN ? AND ?";
    $params[] = $start_date;
    $params[] = $end_date;
}

// Ajoute l'ordre de tri
$query .= " ORDER BY t.transaction_date DESC";

// Prépare et exécute la requête avec tous les paramètres
$stmt = $conn->prepare($query);
$stmt->execute($params);

// Récupère les résultats
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Renvoie les transactions en JSON
echo json_encode(['status' => 'success', 'transactions' => $transactions]);
?>