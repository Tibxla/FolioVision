<?php
session_start();
include '../../config/config.php';
include '../../config/database.php';

ob_start(); // Démarrer le buffering pour éviter toute sortie parasite

try {
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Utilisateur non connecté');
    }

    $user_id = $_SESSION['user_id'];

    // Vérifier la connexion à la base de données
    if ($conn === null) {
        throw new Exception('Échec de la connexion à la base de données');
    }

    // Préparer et exécuter la requête
    $stmt = $conn->prepare("SELECT * FROM investments WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $investments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Nettoyer le buffer et envoyer la réponse JSON
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'investments' => $investments]);
} catch (Throwable $e) {
    // En cas d'erreur, nettoyer le buffer et renvoyer une erreur JSON
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>