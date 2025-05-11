<?php
session_start();
include '../../config/config.php';
include '../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_POST['investment_id']) || !isset($_POST['asset_type']) || !isset($_POST['asset_name']) || !isset($_POST['purchase_price']) || !isset($_POST['quantity']) || !isset($_POST['purchase_date'])) {
    echo json_encode(['status' => 'error', 'message' => 'Données manquantes']);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE investments SET asset_type = :asset_type, asset_name = :asset_name, purchase_price = :purchase_price, current_price = :current_price, quantity = :quantity, purchase_date = :purchase_date WHERE investment_id = :investment_id AND user_id = :user_id");
    $stmt->execute([
        'asset_type' => $_POST['asset_type'],
        'asset_name' => $_POST['asset_name'],
        'purchase_price' => $_POST['purchase_price'],
        'current_price' => $_POST['current_price'] ?? null,
        'quantity' => $_POST['quantity'],
        'purchase_date' => $_POST['purchase_date'],
        'investment_id' => $_POST['investment_id'],
        'user_id' => $user_id
    ]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Investissement mis à jour']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Investissement non trouvé ou non autorisé']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur de base de données : ' . $e->getMessage()]);
}
?>