<?php
session_start();
include '../../config/config.php';
include '../../config/database.php';

// Désactiver l'affichage des erreurs pour éviter les sorties HTML inattendues
error_reporting(0);
ini_set('display_errors', 0);

// Vérification de la session utilisateur
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Préparation de la requête SQL pour récupérer les projets
    $stmt = $conn->prepare("SELECT * FROM goals WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $goals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'goals' => $goals]);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Erreur de base de données : ' . $e->getMessage()]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Erreur inattendue : ' . $e->getMessage()]);
}
?>