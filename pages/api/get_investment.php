<?php
session_start();
include '../../config/config.php';
include '../../config/database.php';

// Vérification de la session utilisateur
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupération de l'investissement_id depuis les paramètres GET
if (!isset($_GET['investment_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'ID de l\'investissement manquant']);
    exit;
}

$investment_id = $_GET['investment_id'];

try {
    // Préparation de la requête SQL pour récupérer l'investissement
    $stmt = $conn->prepare("SELECT * FROM investments WHERE investment_id = :investment_id AND user_id = :user_id");
    $stmt->execute([
        'investment_id' => $investment_id,
        'user_id' => $user_id
    ]);
    $investment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($investment) {
        // Renvoi des données de l'investissement en JSON
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'investment' => $investment]);
    } else {
        // Si aucun investissement n'est trouvé
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Investissement non trouvé ou non autorisé']);
    }
} catch (PDOException $e) {
    // Gestion des erreurs de base de données
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Erreur de base de données : ' . $e->getMessage()]);
}
?>