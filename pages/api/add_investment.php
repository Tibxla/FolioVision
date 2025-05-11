<?php
session_start();
include '../../config/config.php';
include '../../config/database.php';

// Prevent HTML output from interfering with JSON
ob_start(); // Start output buffering

if (!isset($_SESSION['user_id'])) {
    ob_end_clean(); // Clear any buffered output
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_POST['asset_type']) || !isset($_POST['asset_name']) || !isset($_POST['purchase_price']) || !isset($_POST['quantity']) || !isset($_POST['purchase_date'])) {
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Données manquantes']);
    exit;
}

try {
    // Vérifier si $conn est défini
    if (!isset($conn)) {
        throw new Exception('La connexion à la base de données n\'est pas définie');
    }

    $stmt = $conn->prepare("INSERT INTO investments (user_id, asset_type, asset_name, purchase_price, current_price, quantity, purchase_date) VALUES (:user_id, :asset_type, :asset_name, :purchase_price, :current_price, :quantity, :purchase_date)");
    $stmt->execute([
        'user_id' => $user_id,
        'asset_type' => $_POST['asset_type'],
        'asset_name' => $_POST['asset_name'],
        'purchase_price' => $_POST['purchase_price'],
        'current_price' => $_POST['current_price'] ?? null,
        'quantity' => $_POST['quantity'],
        'purchase_date' => $_POST['purchase_date']
    ]);
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Investissement ajouté']);
} catch (Exception $e) {
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Erreur : ' . $e->getMessage()]);
}
?>